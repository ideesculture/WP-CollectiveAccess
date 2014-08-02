jQuery(document).ready(function() {
    jQuery('#wpca_hierarchy').on('click','.toggler.fold',function() {
        console.log(jQuery(this));
        var container_table = jQuery(this).parent().data("table");
        var container_id = jQuery(this).parent().data("id");
        jQuery(this).removeClass('fold').addClass('loading spin');
        jQuery.ajax({
            type:'POST',
            dataType: 'json',
            url:'<?php print plugins_url( 'collectiveaccess_ajax_handler.php' ,  WP_CA_MAIN_FILE); ?>',
            data: {
                action:'getchildren',
                table:container_table,
                id:container_id
            },
            success:function(response){
                if(response != '-1') {
                    jQuery('#wpca-parent-container_'+container_table+'_'+container_id).append(response);
                    jQuery('#wpca-parent-container_'+container_table+'_'+container_id+' > .toggler').removeClass('loading spin').addClass('unfold');
                } else {
                    jQuery('#wpca-parent-container_'+container_table+'_'+container_id+' > .toggler').removeClass('loading spin').addClass('empty');
                }
            }
        });
       
    });
    jQuery('#wpca_hierarchy').on('click','.toggler.unfold',function() {    
        console.log(jQuery(this));
        jQuery(this).parent().children('.wpca-parent-container').remove();
        jQuery(this).removeClass('unfold').addClass('fold');
    });
});
