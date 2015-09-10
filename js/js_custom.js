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