<?php

if( !class_exists( 'WooCommerce' ) ) { echo esc_html__('Please activate WooCommerce plugin','myticket-events'); }else{

$myticket_show_header = true;

$products = new WP_Query( $myticket_args );
if ( $products->have_posts() ) : 

    $states = array();
    while ( $products->have_posts() ) : $products->the_post(); 
        $meta = get_post_meta( get_the_ID() );

        array_push($states, $meta['myticket_datetime_start'][0]);

    endwhile; ?>

    <div class="schedule-content">
        <div class="kenzap-container <?php if("kenzap-sm"==$kenzapSize){$kenzapSize="kenzap-xs";} echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($maxwidth);?>px">

            <?php if ( $myticket_show_header ) : ?>

                <?php if ( count($states) ) {  

                    $monthsyearsdays = $days = $dofs = $months = $years = array();
                    foreach ( $states as $state ) {

                        $dof = date_i18n( 'l', intval( $state ) );
                        $day = date_i18n( 'd', intval( $state ) );
                        $month = date_i18n( 'M', intval( $state ) );
                        $year = date_i18n( 'Y', intval( $state ) );
                        if ( !in_array($day.$month.$year, $monthsyearsdays, true) ){

                            array_push($monthsyearsdays, $day.$month.$year);
                            array_push($dofs, $dof);
                            array_push($days, $day);
                            array_push($months, $month);
                            array_push($years, $year);
                        }
                    }
                } ?>

                <ul class="event-tabs owl-carousel <?php echo esc_attr($kenzapSize); ?>-carousel">
                    <?php $i = 1;
                    if ( count($monthsyearsdays) ) { 
                        foreach ( $monthsyearsdays as $monthsyearsday ) { 

                            $dof = $dofs[$i-1];
                            $day = $days[$i-1];
                            $year = $years[$i-1];
                            $month = $months[$i-1]; ?>
                            <li role="presentation" <?php if ( $i==1 ){ echo 'class="active"'; } ?>>
                                <a href="#tab<?php echo  esc_attr($monthsyearsday);?>"  data-toggle="tab">
                                    <strong><?php echo esc_attr($dof); ?></strong>
                                    <?php echo esc_attr($day); ?>
                                    <span><?php echo esc_attr($month.' '.$year); ?></span>
                                </a>
                            </li>
                            <?php $i++;
                        } 
                    } ?>
                </ul>

            <?php endif; ?>

            <div class="event-tab-content">
                <?php 
                $daymonthyear = $daymonthyear_sub = ''; $e = 0; $ee = 1;
                $daymonthyear_prev = '';
                while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); //print_r($meta); 

                    $id = get_the_ID();
                    $time = date_i18n( get_option( 'time_format' ), intval( $meta['myticket_datetime_start'][0] ) );
                    $time_arr = array();
                    if ( strpos( strtoupper(get_option( 'time_format' )), "A") != -1 )
                        $time_arr = explode( ' ', $time );

                    $day = date_i18n( 'd', intval( $meta['myticket_datetime_start'][0] ) );
                    $month = date_i18n( 'M', intval( $meta['myticket_datetime_start'][0] ) );
                    $year = date_i18n( 'Y', intval( $meta['myticket_datetime_start'][0] ) );  

                    if ( $day.$month.$year != $daymonthyear ) :

                        $e++; $ee = 1;
                        $daymonthyear_sub = '';
                        if ( $daymonthyear != '' ){ echo '</div></div>'; } ?>

                        <div  class="tab-pane <?php if ( $daymonthyear == '' ){ echo 'active'; } $daymonthyear = $day.$month.$year; ?>" id="tab<?php echo esc_attr($daymonthyear); ?>">
                            <div id="tab_main_cont<?php echo esc_attr( $e ); ?>" class="kenzap-row" >

                        <?php 
                    endif;

                    $stock = $meta['_stock'][0];
                    if ( $stock == '' ){
                        $stock = esc_attr__( 'Unlimited Tickets', 'myticket' );
                    }else{
                        $stock = $stock.' '.esc_attr__( ' Tickets Left', 'myticket' );
                    }
                    if ( $meta['_stock_status'][0] == 'outofstock' ) {
                        $stock = esc_attr__( 'No Tickets Left', 'myticket' );
                    }
                    ?>
                
                    <div class="kenzap-col-3">
                        <ul data-date="<?php echo esc_attr($daymonthyear); ?>"  class="schedule-tabs tab_left_<?php echo esc_attr($daymonthyear); ?>">

                            <li <?php if ( $ee == 1 ) { echo 'class="active"'; } ?> >
                                <a href="#tab<?php echo esc_attr($e.'-hr'.$ee); ?>" aria-controls="tab<?php echo esc_attr($e.'-hr'.$ee); ?>"  data-toggle="tab">
                                    <span class="schedule-time">
                                        <?php if ( sizeof($time_arr) > 0 ){
                                            echo esc_attr($time_arr[0]).' <strong>'.esc_attr($time_arr[1]).'</strong>'; 
                                        }else{
                                            echo esc_attr($time);
                                        } ?>
                                    </span>
                                    <span class="schedule-title"><?php the_title(); ?></span>
                                    <span class="schedule-ticket-info"><?php echo esc_attr($stock); ?></span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    
                    <div class="kenzap-col-9">
                        <div id="tab_right_<?php echo esc_attr($daymonthyear.'_'.$ee); ?>" class="schedule-tab-content tab_right_<?php echo esc_attr($daymonthyear); ?>" >
                     
                            <div class="tab-pane <?php if ( $ee == 1 ) { echo 'active'; } ?>" id="tab<?php echo esc_attr($e.'-hr'.$ee); ?>">
                                <?php the_post_thumbnail( 'myticket-schedule', array( 'class' => 'img-responsive' ) ); ?>
                                <div class="full-event-info">
                                    <div class="full-event-info-header">
                                        <h2><?php the_title(); ?></h2>
                                        <span class="ticket-left-info"><?php echo esc_attr($stock); ?></span>
                                        <div class="clearfix"></div>
                                        <span class="event-date-info"><?php echo date_i18n( 'l, M d Y | '.get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) ); ?></span>
                                        <span class="event-venue-info"><?php echo esc_html( $meta['myticket_address'][0] ); ?></span>
                                    </div>
                                    <div class="full-event-info-content">
                                        <?php the_excerpt(); ?>
                                        <?php $btn_text_arr = [];
                                        $btn_text_arr[0] = __( 'Get Ticket', 'myticket-events' );
                                        $btn_text_arr[1] = __( 'Sold Out', 'myticket-events' );
                                        $btn_text_arr[2] = __( 'View More', 'myticket-events' );

                                        //get button link
                                        $meta = get_post_meta( $id );

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
                                    
                                        <a class="book-ticket" href="<?php echo esc_url( $link ); ?>" <?php echo wp_kses( $extra, array( 
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

                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                   

                    <?php $ee++; $daymonthyear_prev = $daymonthyear;

                endwhile; ?> 

                            <!-- tabpanel -->
                            </div>
                        <!-- row -->
                        </div>
                </div>
            </div>       

            <input type="hidden" id="myticket_post_count" value="<?php echo esc_attr($products->found_posts);?>">
            <input type="hidden" id="myticket_max_num_pages" value="<?php echo esc_attr($products->max_num_pages);?>">
            <input type="hidden" id="myticket_max_page_records" value="<?php echo esc_attr($products->query_vars['posts_per_page']); ?>">
            <input type="hidden" id="myticket_current_records" value="<?php echo esc_attr($i);?>">
            <input type="hidden" id="myticket_current_page" value="<?php echo max( 1, get_query_var('paged') );?>">

        </div>
    </div>

    <?php endif; ?>
<?php }