<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */

add_action('wp_ajax_nopriv_myticket_filter_list_ajax', 'myticket_events_filter_list_ajax');
add_action('wp_ajax_myticket_filter_list_ajax', 'myticket_events_filter_list_ajax');
if ( ! function_exists( 'myticket_events_filter_list_ajax' ) ) {
    function myticket_events_filter_list_ajax() {
        global $woocommerce;
        global $myticket_args;
        global $myticket_pagination;
        global $myticket_pagenum_link;
        global $myticket_search_value;
        global $paged;

        $ppp                      = (isset($_POST['ppp'])) ? sanitize_text_field($_POST['ppp']) : 3;
        $cat                      = (isset($_POST['cat'])) ? sanitize_text_field($_POST['cat']) : "";
        $tag                      = (isset($_POST['product_tag'])) ? sanitize_text_field($_POST['product_tag']) : "";
        $events_per_page          = (isset($_POST['events_per_page'])) ? sanitize_text_field($_POST['events_per_page']) : 0;
        $events_relation          = (isset($_POST['events_relation'])) ? sanitize_text_field($_POST['events_relation']) : "AND";
        $offset                   = (isset($_POST['offset'])) ? sanitize_text_field($_POST['offset']) : 0;
        $product_list             = (isset($_POST['product_list'])) ? sanitize_text_field($_POST['product_list']) : "";
        $product_order            = (isset($_POST['product_order'])) ? sanitize_text_field($_POST['product_order']) : "";
        $product_pricing_low      = (isset($_POST['product_pricing_low'])) ? sanitize_text_field($_POST['product_pricing_low']) : 0;
        $paged                    = (isset($_POST['paged'])) ? sanitize_text_field($_POST['paged']) : 1;
        $pagenum_link             = (isset($_POST['pagenum_link'])) ? sanitize_text_field($_POST['pagenum_link']) : "";
        $product_pricing_high     = (isset($_POST['product_pricing_high'])) ? sanitize_text_field($_POST['product_pricing_high']) : 1000000;
        $myticket_pagenum_link    = (isset($_POST['pagenum_link'])) ? sanitize_text_field($_POST['pagenum_link']) : '';
        $myticket_pagination      = (isset($_POST['pagination'])) ? sanitize_text_field($_POST['pagination']) : '';
        $myticket_search_value    = (isset($_POST['search_value'])) ? sanitize_text_field($_POST['search_value']) : '';
        $myticket_search_location = (isset($_POST['search_location'])) ? sanitize_text_field($_POST['search_location']) : '';
        $myticket_search_time     = (isset($_POST['search_time'])) ? sanitize_text_field($_POST['search_time']) : '';
        $myticket_category_list   = (isset($_POST['product_category_list'])) ? sanitize_text_field($_POST['product_category_list']) : '';
        $myticket_type            = (isset($_POST['product_type'])) ? sanitize_text_field($_POST['product_type']) : '';
        $ft_image                 = (isset($_POST['ft_image'])) ? sanitize_text_field($_POST['ft_image']) : false;

        if ( !is_numeric($events_per_page) ) $events_per_page = 0;
        if ( !is_numeric($offset) ) $offset = 0;
        if ( !is_numeric($product_pricing_low) ) $product_pricing_low = 0;
        if ( !is_numeric($product_pricing_high) ) $product_pricing_high = 1000000;
        
        $product_pricing_arr =  array(
            'key' => '_price',
            'value' => array(intval(preg_replace('/[^0-9]/', '', $product_pricing_low)), intval(preg_replace('/[^0-9]/', '', $product_pricing_high))),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC'
        );

        $myticket_args = array(
            'posts_per_page' => $events_per_page,
            'post_type' => array('product'), //, 'product_variation'
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'paged' => $paged,
            'tax_query' => array('relation'=>$events_relation), 
            'meta_query' => array (
                array (
                  'key' => 'myticket_title',
                  'value' => '',
                  'compare' => '!=',
                )
            ),
            'orderby' => 'meta_value_num',
            'order' => 'ASC',         
        );

        //product order sorting
        switch ( $product_order ) {
            case 'lowestprice':
                $myticket_args['meta_key'] = '_price';  
                $myticket_args['orderby'] = array( 'meta_value_num' => 'ASC' );
            break;
            case 'highestprice':
                $myticket_args['meta_key'] = '_price';  
                $myticket_args['orderby'] = array( 'meta_value_num' => 'DESC' );  
            break;
            // case 'newest':
            //     $myticket_args['orderby'] = array( 'date' => 'DESC' );   
            // break;
            case 'newest':
                $myticket_args['orderby'] = 'meta_value_num';  
                $myticket_args['order'] = 'ASC';
                $myticket_args['orderby_meta_key'] = 'myticket_datetime_start';
            break;
            case 'popularity':
                $myticket_args['orderby'] = 'meta_value_num';  
                $myticket_args['order'] = 'DESC';
                $myticket_args['orderby_meta_key'] = 'total_sales';
            break;
            case 'rating':
                $myticket_args['orderby'] = 'meta_value_num';  
                $myticket_args['order'] = 'DESC';
                $myticket_args['orderby_meta_key'] = '_wc_average_rating';
            break;
            case 'alphabetical':
                $myticket_args['orderby'] = 'title';
                $myticket_args['order'] = 'ASC';
            break;
            default:
                $myticket_args['orderby'] = 'meta_value_num';  
                $myticket_args['order'] = 'ASC';
                $myticket_args['orderby_meta_key'] = 'myticket_datetime_start';
            break;
        }

        //select event period
        if ( strlen( $myticket_search_time ) == 0 ) {
            switch ( $myticket_type ){

                case "upcomming":

                    $temp =  array(
                        'key'       => 'myticket_datetime_start',
                        'compare'   => '>',
                        'value'     => time(),
                        'type'      => 'NUMERIC'
                    );
                    array_push( $myticket_args['meta_query'], $temp ); 

                break; 
                case "past":

                    $temp =  array(
                        'key'       => 'myticket_datetime_start',
                        'compare'   => '<',
                        'value'     => time(),
                        'type'      => 'NUMERIC'
                    );
                    array_push( $myticket_args['meta_query'], $temp ); 

                break; 
            }
        }

        //product search query
        if ( strlen( $myticket_search_value ) > 0 ) {

            $myticket_args['s'] = $myticket_search_value;
        }

        //product search location
        if ( strlen( $myticket_search_location ) > 0 ) {

            $myticket_args['meta_key'] = 'myticket_title';
            $myticket_args['meta_value'] = $myticket_search_location;
        }

        //product search time period
        if ( strlen( $myticket_search_time ) > 0 ) {

            $dates_arr = explode( "_", $myticket_search_time );

            $first = mktime(0,0,0,intval($dates_arr[0]),1,intval($dates_arr[1]));
            $last = mktime(23,59,00,intval($dates_arr[0])+1,0,intval($dates_arr[1]));

            $myticket_period_arr =  array(
                'key' => 'myticket_datetime_start',
                'value' => array(intval($first), intval($last)),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            );

            array_push( $myticket_args['meta_query'], $myticket_period_arr ); 
        }

        //filter by categories
        if ( strlen( $myticket_category_list ) > 1 ) {
            $myticket_category_list = trim($myticket_category_list,",");
            $myticket_category_list_arr = explode(",", $myticket_category_list);
            foreach($myticket_category_list_arr as $category){

                $product_cat_arr = array(
                    'taxonomy'     => 'product_cat',
                    'field'        => 'name',
                    'terms'        => esc_attr( $category)
                );
                array_push( $myticket_args['tax_query'], $product_cat_arr );
            }
        }

        array_push( $myticket_args['meta_query'], $product_pricing_arr ); 

        ob_start();

        require plugin_dir_path(__FILE__) . 'block-ajax.php'; 

        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer);
    }
}