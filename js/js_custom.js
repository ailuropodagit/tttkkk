$(document).ready(function () {
    if ($('.phone_blur').length) {
        $('.phone_blur').blur(function () {
            var value = $(this).val();
            var text_length = value.length;
            
            if (text_length === 8) {
                value = value.slice(0, 1) + " " + value.slice(1, 4) + " " + value.slice(4);
            }
            else if (text_length === 9) {
                value = value.slice(0, 2) + " " + value.slice(2, 5) + " " + value.slice(5);
            }
            else if (text_length === 10) { 
                value = value.slice(0, 2) + " " + value.slice(2, 6) + " " + value.slice(6); 
            }
            else if (text_length === 11) { 
                value = value.slice(0, 3) + " " + value.slice(3, 7) + " " + value.slice(7); 
            }
            else if (text_length === 12) { 
                value = value.slice(0, 4) + " " + value.slice(4, 8) + " " + value.slice(8); 
            }
            else {
                value = value.match(/.{1,3}/g).join(" ");
            }
            $(this).val(value);
        }).focus(function () {
            var value = $(this).val();
            value = value.replace(/\s/g, '');
            $(this).val(value);
        });
    }
});

$(document).ready(function () {
    if ($('.auto-submit-star').length) {
        $('.auto-submit-star').rating({
            required: true,
            callback: function (value, link) {
                var keppopath = '/keppo/';
                var hostname = $(location).attr('hostname');
                if (hostname.indexOf('keppo') >= 0){
                    keppopath = '/';
                }
                var item_id = $('#item_id').val();
                var item_type = $('#item_type').val();
                var post_url = 'http://' + hostname + keppopath + 'all/user_rating';
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: "json",
                    data: "&refer_id=" + item_id + "&rate_val=" + value + "&refer_type=" + item_type,
                    success: function (e) {
                        $.jGrowl(e.code + "<br>" + e.msg, {position: 'center',life: 30000});
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //alert(textStatus);
                        //alert(errorThrown);
                    }
                });
            }
        });
    }
});

function click_like(user_id) {
    var keppopath = '/keppo/';
    var hostname = $(location).attr('hostname');
    if (hostname.indexOf('keppo') >= 0){
        keppopath = '/';
    }
    var item_type = $('#item_type').val();
    var post_url = 'http://' + hostname + keppopath + 'all/user_click_like/' + user_id + '/' + item_type;
    $.ajax({
        type: 'POST',
        url: post_url,
        dataType: "json",
        success: function (e) {
            $.jGrowl(e.code + "<br>" + e.msg, {position: 'center',life: 30000});
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

function toggle_visibility(id) {
    var e = document.getElementById(id);
    if (e.style.display == 'block') {
        e.style.display = 'none';
    } else {
        e.style.display = 'block';
    }
}