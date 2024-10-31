$j = jQuery.noConflict();

jQuery(document).ready(function() {
    adminbar_outer_height = $j('#wpadminbar').outerHeight();


$j('#pluginsaz span').click(function() {

    // <tr> has no property offset().top, so we have to use the first <td> instead
    id = $j(this).attr('id').replace(/-link$/, '');
    id = $j("tr[data-slug='"+id+"']").find("td:first");

    if (id != '#inactive') {

        $j(window).scrollTop($j(id).offset().top - adminbar_outer_height - 5);
        border_width = $j('.wide-fat');
        // console.log(border_width);
        $j(id).find('td, th').each(function () {
            the_class = $j(this).attr('class').split(' ')[0];
            // console.log(id);
        });


    }
});

});
