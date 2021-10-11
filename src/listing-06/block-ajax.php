<?php

if( !class_exists( 'WooCommerce' ) ) { echo esc_html__('Please activate WooCommerce plugin','myticket-events'); }else{

$myticket_show_header = true;
if ( $attributes['serverSide'] ){ $kenzapSize = "kenzap-md"; }


$products = new WP_Query( $myticket_args );
if ( $products->have_posts() ) : 

    $states = array();
    while ( $products->have_posts() ) : $products->the_post(); 
        $meta = get_post_meta( get_the_ID() );

        array_push($states, $meta['myticket_datetime_start'][0]);

    endwhile; ?>

    <div class="kpe6 <?php if($attributes['align']) echo "align".$attributes['align']." "; if("kenzap-sm"==$kenzapSize){$kenzapSize="kenzap-xs";} echo esc_attr($kenzapSize); ?> <?php echo esc_attr($attributes['className']); ?> <?php if($attributes['showArrows']) echo "kp-arrows"; ?>" data-zone="<?php echo esc_attr__('Zone','myticket-events'); ?>"  data-row="<?php echo esc_attr__('Row:','myticket-events'); ?>" data-perseat="<?php echo esc_attr__('per seat','myticket-events'); ?>" data-dwidth="<?php echo esc_attr($attributes['dwidth']); ?>" data-mwidth="<?php echo esc_attr($attributes['mwidth']); ?>" data-sminwidth="<?php echo esc_attr($attributes['sminwidth']); ?>" data-ticketspbooking="<?php echo esc_attr($attributes['ticketsPerBooking']); ?>" data-smaxwidth="<?php echo esc_attr($attributes['smaxwidth']); ?>" data-price="<?php if($product!=''){ echo get_woocommerce_currency_symbol().$product->get_price(); } ?>" data-ajax_max_tickets="<?php echo esc_attr__('Not allowed to add more tickets','myticket-events'); ?>" data-ajax_booked="<?php echo esc_attr__('Oops. Some seats were already booked. Please try again!','myticket-events'); ?>" data-id="<?php echo esc_attr($attributes['eventID']); ?>" data-carturl="<?php echo esc_url($carturl); ?>" data-checkouturl="<?php echo esc_url($checkouturl); ?>" data-ajax_error="<?php echo esc_attr__('Oops. Something went wrong. Please try again later.','myticket-events'); ?>"  data-ajax="<?php echo esc_attr($ajaxurl); ?>" style="--bc:<?php echo esc_attr($attributes['backgroundColor']); ?>;--tc:<?php echo esc_attr($attributes['textColor']); ?>;--mc:<?php echo esc_attr($attributes['mainColor']); ?>;--borderRadius:<?php echo esc_attr($attributes['borderRadius']); ?>px;--sc:<?php echo esc_attr($attributes['subColor']); ?>;<?php if($attributes['img1']!='' && $attributes['img1']!='none'){ echo '--img1:url('.esc_url($attributes['img1']).');'; }else{ echo '--img1:url('.MYTICKET_URL."dist/images/location.svg".');'; } ?> <?php echo ($kenzapStyles);//escaped in src/commonComponents/container/container-cont.php ?>" >
        
        <div class="kenzap-container" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px;">
            
            <div class="kp-content">

                <?php $daymonthyear = $daymonthyear_sub = ''; $e = 0; $ee = 1;
                $daymonthyear_prev = '';
                while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

                    $id = get_the_ID();
                    $time = date_i18n( get_option( 'time_format' ), intval( $meta['myticket_datetime_start'][0] ) );
                    $time_arr = array();
                    if ( strpos( strtoupper(get_option( 'time_format' )), "A") != -1 )
                        $time_arr = explode( ' ', $time );

                    $day = date_i18n( 'd', intval( $meta['myticket_datetime_start'][0] ) );
                    $month = date_i18n( 'M', intval( $meta['myticket_datetime_start'][0] ) );
                    $year = date_i18n( 'Y', intval( $meta['myticket_datetime_start'][0] ) );  

                    //get coordinates
                    $coordinates = isset($meta['myticket_coordinates'][0])?str_replace(' ', '', $meta['myticket_coordinates'][0]):"";

                    // generate CTA button code
                    $btn_text_arr = [];
                    $btn_text_arr[0] = __( 'Get Ticket', 'myticket-events' );
                    $btn_text_arr[1] = __( 'Sold Out', 'myticket-events' );
                    $btn_text_arr[2] = __( 'View More', 'myticket-events' );

                    //get button link
                    $meta = get_post_meta( $id );
                    $stock = $meta['_stock'][0];

                    //get button status
                    if ( $meta['_stock_status'][0] == 'instock' ) :
                        $btn_text = $btn_text_arr[0];
                    elseif ( $meta['_stock_status'][0] == 'outofstock' ) :
                        $btn_text = $btn_text_arr[1];
                    endif;
                
                    //if not product type simple force final page
                    $_product = wc_get_product( $id );
                    $link = get_permalink( $id );

                    //override woocommerce product with custom link
                    if ( strlen($meta['myticket_link'][0]) > 0 ){
                        $link = $meta['myticket_link'][0];
                        $btn_text = $btn_text_arr[2];
                    } ?>

                    <div class="event-box">
                        <div class="event-date">
                            <div class="post-date">
                                <span class="day"><?php echo esc_html($day); ?></span>
                                <span class="month"><?php echo esc_html($month); ?></span>
                                <span class="year"><?php echo esc_html($year); ?></span>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3><a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a></h3>
                            <div class="kp-excerpt"><?php the_excerpt(88); ?></div>
                            <?php if(!empty($coordinates)){ ?>
                            <div class="location">
                                <span><i class="event-location"></i> <?php echo esc_html( $meta['myticket_address'][0] ); ?></span><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr($coordinates); ?>"><?php esc_html_e( ' View Map', 'myticket' );?></a> 
                            </div>
                            <?php } ?>
                        </div>
                        <div class="event-action">

                            <a class="join-now" href="<?php echo esc_url( $link ); ?>" <?php echo wp_kses( $extra, array( 
                                'a' => array(
                                    'href' => array(),
                                    'title' => array()
                                ),
                                'br' => array(),
                                'b' => array(),
                                'tr' => array(),
                                'th' => array(),
                                'td' => array(),
                                'em' => array(),
                                'span' => array(
                                    'id' => array(),
                                    'class' => array(),),
                                'i' => array( 
                                    'id' => array(),
                                    'class' => array(),),
                                'strong' => array(),
                                'span' => array(
                                    'href' => array(),
                                    'class' => array(),
                                ),
                                'div' => array(
                                    'id' => array(),
                                    'class' => array(),
                                ),
                            ) );
                        
                            ?> ><?php echo esc_html( $btn_text ); ?></a>
                            <?php if($stock<$attributes['low_stock'] && $stock>0){ ?>
                                <span class="limited"><?php echo esc_html__( 'Limited', 'myticket-events' ); echo " ".$stock." "; echo esc_html__( 'seats left!', 'myticket-events' );?></span>
                            <?php } ?>
                        </div>
                    </div>

                <?php endwhile; ?>

            </div>

            <input type="hidden" id="myticket_post_count" value="<?php echo esc_attr($products->found_posts);?>">
            <input type="hidden" id="myticket_max_num_pages" value="<?php echo esc_attr($products->max_num_pages);?>">
            <input type="hidden" id="myticket_max_page_records" value="<?php echo esc_attr($products->query_vars['posts_per_page']); ?>">
            <input type="hidden" id="myticket_current_records" value="<?php echo esc_attr($i);?>">
            <input type="hidden" id="myticket_current_page" value="<?php echo max( 1, get_query_var('paged') );?>">

        </div>
    </div>

    <?php else: ?>

        <?php echo esc_html__( 'No records to display. Go to Products > Add New.', 'myticket-events' );  ?>

    <?php endif ?>
<?php }