$(function(){
    $('button.next').on('click', (e) => {
        let bul = $(e.target);
        $.post(bul.data('url'), (ret) => {
            if (ret.next) {
                location.href = ret.next;
            }
            console.log(ret);
        });

    });
})