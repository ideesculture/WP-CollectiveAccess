jQuery(document).ready(function(){
    jQuery('p.facetname').click(function(){
       jQuery(this).next().slideToggle();
       jQuery(this).toggleClass('collapsed');
       return true;
    });
    jQuery('span.subfacetname').click(function(){
        var facetref = jQuery(this).data().facettype + '-' + jQuery(this).data().facetname;
        console.log(facetref);
        jQuery('p.subfacetcontent.' + facetref).slideToggle();
        jQuery(this).toggleClass('collapsed');
        return true;
    });        
    jQuery('img.attachment-thumbnail').each(function() {
        var getimage_table = jQuery(this).data("table");
        var getimage_id = jQuery(this).data("id");
        var jquery_this = jQuery(this);
        jQuery.ajax({
            type:'POST',
            dataType: 'text',
            url:'<?php print plugins_url( 'collectiveaccess_ajax_handler.php' ,  WP_CA_MAIN_FILE); ?>',
            data: {
                action:'getimage',
                table:getimage_table,
                id:getimage_id
            },
            success:function(response){
                if(response != '-1') {
                    console.log(response);
                    jquery_this.attr('src',JSON.parse(response));
                } else {
                    jquery_this.removeAttr('src');
                }
            }
        });
    });
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
