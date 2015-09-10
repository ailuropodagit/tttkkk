function click_like(user_id) {    
    var post_url = 'http://'+$(location).attr('hostname') + '/keppo/all/user_click_like/' + user_id + '/adv';

    $.ajax({
        type: 'POST',
        url: post_url,
        dataType: "html",
        success: function() { 
            $('.like-it').delay(2500).replaceWith(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            alert(errorThrown);
        }
    });

}