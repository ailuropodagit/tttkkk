
$(document).ready(function () {
    $('.auto-submit-star').rating({
        required: true,
        callback: function (value, link) {
            var item_id = $('#item_id').val();
            var post_url = 'http://' + $(location).attr('hostname') + '/keppo/all/user_rating';
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: "json",
                data: "&refer_id=" + item_id + "&rate_val=" + value + "&refer_type=adv",
                success: function (e) {
                    $.jGrowl(e.code + "<br>" + e.msg, {position: 'center'});
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                    alert(errorThrown);
                }
            });
        }
    });
});

function click_like(user_id) {    
    var post_url = 'http://'+$(location).attr('hostname') + '/keppo/all/user_click_like/' + user_id + '/adv';

    $.ajax({
        type: 'POST',
        url: post_url,
        dataType: "html",
        success: function(data) { 
            $('.like-it').replaceWith(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            alert(errorThrown);
        }
    });

}