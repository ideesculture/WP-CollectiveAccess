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
});
jQuery(document).ready(function() {
    jQuery('img.attachment-thumbnail').each(function() {
        var getimage_table = jQuery(this).data("table");
        var getimage_id = jQuery(this).data("id");
        var jquery_this = jQuery(this);
        jQuery.ajax({
            type:'POST',
            dataType: 'text',
            url:'<?php print get_site_url(); ?>/wp-content/plugins/WP-CollectiveAccess/collectiveaccess_ajax_handler.php',
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
});
