<?php

if( !class_exists( 'WooCommerce' ) ) { echo esc_html__('Please activate WooCommerce plugin','myticket-events'); }else{

    $products = new WP_Query( $myticket_args );
    $i = 0;
    if ( $products->have_posts() ) : ?>

        <?php while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); $i++; ?>

            <?php 
            $product = wc_get_product( get_the_ID() );

            $_regular_price = $meta['_regular_price'][0];
            if ( $_regular_price == '' ){ $_regular_price = '0'; } ?>

            <div class="search-result-item <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ){ echo 'sale '; } if ( $meta['_stock_status'][0] == 'outofstock' ){ echo 'sold-out '; } ?>">
                <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ){ ?><div class="ribbon"><span><?php esc_html_e('Sale', 'myticket-events'); ?></span></div><?php } ?> 
                <div class="kenzap-row">
                    <div class="search-result-item-info kenzap-col-9">
                        <h3><?php the_title();?></h3>
                        <ul class="kenzap-row">
                            <li class="kenzap-col-6">
                                <span><?php esc_html_e('Venue', 'myticket-events'); ?></span>
                                <?php echo esc_attr( $meta['myticket_title'][0] ); ?>
                            </li>
                            <li class="kenzap-col-3">
                                <span><?php echo date_i18n( "l", intval( $meta['myticket_datetime_start'][0] ) ); ?></span>
                                <?php echo date_i18n( get_option( 'date_format' ), intval( esc_attr($meta['myticket_datetime_start'][0]) ) ); ?>
                            </li>
                            <li class="kenzap-col-3">
                                <span><?php esc_html_e('Time', 'myticket-events'); ?></span>
                                <?php echo date_i18n( get_option( 'time_format' ), intval( esc_attr($meta['myticket_datetime_start'][0]) ) ); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="search-result-item-price kenzap-col-3">
                        <span><?php esc_html_e('Price From', 'myticket-events'); ?></span>

                        <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ) : ?>
                            <strong><span><?php echo get_woocommerce_currency_symbol().$product->get_regular_price(); ?></span><?php echo get_woocommerce_currency_symbol().$product->get_price(); ?></strong>
                        <?php else: ?>
                            <strong><?php echo get_woocommerce_currency_symbol().$product->get_price(); ?></strong>
                        <?php endif; ?>

                        <?php $btn_text_arr = [];
                        $btn_text_arr[0] = __( 'Book Ticket', 'myticket-events' );
                        $btn_text_arr[1] = __( 'Sold Out', 'myticket-events' );
                        $btn_text_arr[2] = __( 'View More', 'myticket-events' );
                            
                        $id = get_the_ID();
                        $extra = 'class="get-ticket"';

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
                        } ?>
                    
                        <a href="<?php echo esc_url( $link ); ?>" <?php echo wp_kses( $extra, array( 
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
        
        <?php endwhile; ?>
        <input type="hidden" id="myticket_post_count" value="<?php echo esc_attr($products->found_posts);?>">
        <input type="hidden" id="myticket_max_num_pages" value="<?php echo esc_attr($products->max_num_pages);?>">
        <input type="hidden" id="myticket_max_page_records" value="<?php echo esc_attr($products->query_vars['posts_per_page']); ?>">
        <input type="hidden" id="myticket_current_records" value="<?php echo esc_attr($i);?>">
        <input type="hidden" id="myticket_current_page" value="<?php echo max( 1, get_query_var('paged') );?>">
    <?php endif; ?>

    <?php if( $myticket_pagination && $products->max_num_pages>1 ){  ?>

        <div class="search-result-footer">
            <ul class="kp-pagination">
                <?php if($paged>1){ echo '<li><span class="page-numbers kpp" data-page="'.esc_attr(intVal($paged)-1).'">'.esc_html__( 'Prev', 'myticket-events' ).'</span></li>'; } ?>
                <?php for($i=1; $i<($products->max_num_pages+1); $i++){ ?>
                    
                    <li><span aria-current="page" data-page="<?php echo esc_attr($i); ?>" class="page-numbers <?php if ($paged==$i){ echo "current";} ?>" ><?php echo esc_html($i); ?></span></li>

                <?php } ?>
                <?php if($paged<$products->max_num_pages){ echo '<li><span class="page-numbers kpn" data-page="'.esc_attr(intVal($paged)+1).'">'.esc_html__( 'Next', 'myticket-events' ).'</span></li>'; } ?>
 
            </ul>
        </div>

        <?php
    } 

}
   