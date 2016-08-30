/**
 * Created by chenhao on 2016/6/15.
 */
function ckPage(url,p){
    var search = jQuery('#search').val();
    var search1 = jQuery('#search1').val();
    if(!search){
        search = '';
    }
    if(!search1){
        search1 = '';
    }
    if(search == "" && search1 == "") {
        jQuery.ajax({
            type: 'GET',
            data: 'p=' + p,
            url : url,
            success: function (msg) {
                jQuery('#list').html(msg)
            }
        })
    }else{
        if(search1 == "") {
            jQuery.ajax({
                type: 'GET',
                data: 'p=' + p + '&search=' + search,
                url: url,
                success: function (msg) {
                    jQuery('#list').html(msg);
                    jQuery('#search').val(search);
                }
            })
        }else if( search == "" ){
            jQuery.ajax({
                type: 'GET',
                data: 'p=' + p + '&search1=' + search1,
                url: url,
                success: function (msg) {
                    jQuery('#list').html(msg);
                    jQuery('#search1').val(search1);
                }
            })
        }else{
            jQuery.ajax({
                type: 'GET',
                data: 'p=' + p + '&search1=' + search1 + '&search=' + search,
                url: url,
                success: function (msg) {
                    jQuery('#list').html(msg);
                    jQuery('#search').val(search);
                    jQuery('#search1').val(search1);
                }
            })
        }
    }
}