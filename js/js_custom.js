
$(document).ready(function () {
    $('.auto-submit-star').rating({
        required: true,
        callback: function (value, link) {
            var item_id = $('#item_id').val();
            var item_type = $('#item_type').val();
            var post_url = 'http://' + $(location).attr('hostname') + '/keppo/all/user_rating';
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: "json",
                data: "&refer_id=" + item_id + "&rate_val=" + value + "&refer_type="+ item_type,
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
    var item_type = $('#item_type').val();
    var post_url = 'http://'+$(location).attr('hostname') + '/keppo/all/user_click_like/' + user_id + '/'+ item_type;
    $.ajax({
        type: 'POST',
        url: post_url,
        dataType: "json",
        success: function(e) { 
            $.jGrowl(e.code + "<br>" + e.msg, {position: 'center'});
            $('.like-it').replaceWith(e.like_url);            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            alert(errorThrown);
        }
    });
}

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function checkbox_showhide(the_checkbox, the_div)
{
    var checkBox = document.getElementById(the_checkbox);
    if (checkBox.checked == true)
    {
        document.getElementById(the_div).style.display = 'inline';
    } else {
        document.getElementById(the_div).style.display = 'none';
    }
}