let controls;

function getNewImage(imgRes = {})
{
    controls.addClass('disabled');
    console.log(imgRes);
    $.post('get-next-image', imgRes, (res) => {

        $('img.img').attr({
            src: res['src'],
            width: res['width'],
            height: res['height'],
        })
        $('span.image-id').html(res.id);
        controls.data({'img-id': res['src'].match(/base64,\s*$/i) ? 0 : res.id });

    }).fail(() => {
        controls.data({'img-id': 0});
    }).always(() => {
        let errControl = controls.filter('.id-err');
        if (
            errControl.data('img-id') == 0 && errControl.parent().hasClass('visually-hidden') ||
            errControl.data('img-id') > 0 && !errControl.parent().hasClass('visually-hidden')
        ) {
            controls.each((ind, el) => {
                $(el).parent().toggleClass('visually-hidden');
            });
        }

        // controls.each((ind, el) => {
        //     let elj = $(el);
        //     let parent = elj.parent();
        //     if (!parent.hasClass('id-err')) {
        //         return;
        //     }
        //     // ошибка

        //     //
        //     console.log('err');

        //     if (parent.hasClass('id-su')) {
        //         parent.addClass('visually-hidden');
        //     } else {
        //         parent.removeClass('visually-hidden');
        //     }
        // });
        controls.removeClass('disabled');
    });
}

$(function(){
    controls = $('span.control-buttons');


    controls.on('click', (e) => {
        let btn = $(e.target);
        // ничего не делаем .. кнопки заблокированы
        if (btn.hasClass('disabled')) {
            return;
        }
        getNewImage(btn.data());
        // console.log(e.target, {}, $(e.target).data('action'));

    });
    getNewImage();

})