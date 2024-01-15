<?php

namespace app\models;

use yii\base\Model;
use yii\data\SqlDataProvider;
use Yii;

class Image extends Model
{
    const REQUEST_URL = 'https://picsum.photos/id/%d/%d/%d';
    public $id;
    public $result;

    public $height;
    public $width;

    public function rules()
    {
        return [
            ['id', 'integer', 'min' => 1],
            ['result', 'boolean'],
            ['result', 'default', 'value' => false],
            ['id', 'required'],
            ['id', 'existsInDb'],
        ];
    }

    /**
     * проверка наличия в базе
     *
     * @param      <type>  $attr   The attribute
     */
    public function existsInDb($attr)
    {
        $inDb = intval(Yii::$app->db->createCommand('SELECT count(*) FROM {{%images}} where id = :id', ['id' => $this->$attr])->queryScalar());
        if ($inDb > 0) {
            $this->addError($attr, "Результаты по картинке {$this->$attr} присутствуют в базе");
        }
    }

    public function saveResult()
    {
        if (!$this->validate()) {
            return false;
        }

        Yii::$app->db->createCommand('insert into {{%images}} values (:id, :res)', [
            'id' => $this->id,
            'res' => $this->result,
        ])->execute();
        return true;
    }

    public static function listResults()
    {
        return new SqlDataProvider([
            'sql' => 'select * from {{%images}} where result  is not null',
        ]);
    }

    /**
     * получить объект с ноаой картинкой, которой ещё не было
     *
     * @return     Image  ( description_of_the_return_value )
     */
    public static function newImage($id=null)
    {
        $img = new Image([
            'height' => getenv('img_height'),
            'width' => getenv('img_width'),
        ]);
        if (isset($id)) {
            $img->id = $id;
            return $img;
        }
        // проверка сущствовоания картинки ..

        // $ids = [1120, 1020];
        $cycle = false;
        do {
            if ($cycle) {
                usleep(rand(1000, 3000) * 1000);
            }
            $img->id = rand(getenv('img_id_start'), getenv('img_id_end'));// $ids[$i];

            $cycle = true;

        } while (!$img->validate() || !$img->checkExistsImage());

        return $img;

    }

    /**
     * удаление результата голосования по картике
     *
     * @param      int   $id     The identifier
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public static function dropResult(int $id)
    {
        Yii::$app->db->createCommand('delete from {{%images}} where id = :id', ['id' => $id])->execute();
        return true;
    }

    /**
     * Инициализируем Curl для проверки наличия картинки или для запроса самой картинки
     *
     * @param      bool    $getData  Если True - запрос самой картинки
     *
     * @return     <type>  The curl.
     */
    private function getCurl($getData=true)
    {
        $cu = curl_init();
        $cookFile = Yii::getAlias('@runtime/cook.txt');
        curl_setopt_array($cu, [
            CURLOPT_URL => $this->url,
            CURLOPT_COOKIEFILE => $cookFile,
            CURLOPT_COOKIEJAR => $cookFile,
            CURLOPT_POST => false,
            // CURLOPT_PROXY => '117.250.3.58:8080',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $proxy = getenv('request_proxy');
        if ($proxy !== false) {
            curl_setopt($cu, CURLOPT_PROXY, $proxy);
        }
        if ($getData) {
            curl_setopt($cu, CURLOPT_FOLLOWLOCATION, true);
        }
        return $cu;
    }

    /**
     * проверка существования картинки
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public function checkExistsImage()
    {

        // $cu = curl_init('http://streamhd4k.com');
        // $cu = curl_init('https://ya.ru');
        // $cu = curl_init('https://2ip.ru');

        // 194.5.175.110:3128
        $cu = $this->getCurl(false);
        curl_exec($cu);
        $isNotFound = curl_getinfo($cu, CURLINFO_HTTP_CODE) == 404;
        // картинка не найдена на сайте .. помечаем её чтобы повторно не нарываться
        if ($isNotFound) {
            Yii::$app->db->createCommand('insert into {{%images}} (id) values (:id)', [':id' => $this->id])->execute();
        }
        curl_close($cu);

        return !$isNotFound;
    }

    /**
     * Формирование url на сайт истоника картинок
     *
     * @return     <type>  The url.
     */
    public function getUrl()
    {
        return sprintf(static::REQUEST_URL, $this->id, $this->width, $this->height);
    }

    /**
     * Вернуть контент запрошенной картинки в base64 для отображения в img теге ...
     *
     * @return     <type>  The source.
     */
    public function getSrc()
    {
        $cu = $this->getCurl();
        $data = curl_exec($cu);
        $info = curl_getinfo($cu);

        // Yii::info($info, 'info');
        // Yii::info(base64_encode($data), 'len');
        $res = sprintf('data:%s;base64, %s', curl_getinfo($cu, CURLINFO_CONTENT_TYPE), base64_encode($data));
        curl_close($cu);
        return $res;
    }

}