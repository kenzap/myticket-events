<?php defined( 'ABSPATH' ) or exit;

//header('Content-Type: text/html; charset=UTF-8');

//load mpdf and WooCommerce variables
require_once MYTICKET_PATH . 'inc/mpdf/vendor/mpdf/mpdf/mpdf.php';
$config = [
    'mode' => '+aCJK', 
    // "allowCJKoverflow" => true, 
    "autoScriptToLang" => true,
    // "allow_charset_conversion" => false,
	"autoLangToFont" => true,
	'debug' => true,
];

$mpdf                           = new mPDF($config);
$order                          = new WC_Order( $order_id );
$uploads                        = wp_get_upload_dir();
$ticketDir                      = $uploads['basedir']."/tickets";
$formatted_shipping_address     = $order->get_formatted_shipping_address();
$formatted_billing_address      = $order->get_formatted_billing_address();
$line_items                     = $order->get_items( 'line_item' );
$item_id                        = 0;

//make sure that ticket directory exists
wp_mkdir_p($ticketDir);

###########################
## TICKET TEMPLATE START ##
###########################

ob_start();

# use font-family:sun-extA; to activate chinese characters 
# <table width="100%" cellpadding="0" cellspacing="0" style="font-family:sun-extA;">

?>

<table width="100%" cellpadding="0" cellspacing="0" >
	<tr class="top">
		<td width="50%">
            <?php
			/* Ajax urls */
			$ajaxurl = '';
			if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
			    $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else {
			    $ajaxurl .= admin_url( 'admin-ajax.php');
            } ?>
            <barcode code="<?php echo "myticket:".esc_attr($order_id).",".esc_url($ajaxurl).",".esc_attr($item_id).",0,0"; ?>" size="2.4" type="QR" error="M" disableborder="1" class="barcode" />
		</td>
	
		<td width="50%" style="text-align:right;">

			<img width="150px" src="https://kenzap.com/static/logo.svg" />
			<br/>
			<br/>
        <?php
			
			printf( '<h2>%s #'.esc_html($order_id).'</h2>', esc_html__( 'Ticket ID:', 'myticket-events' ) );
			printf( esc_html__( 'Order Date: %s', 'myticket-events' ), date_i18n( get_option('date_format'), strtotime($order->get_date_created()) ) );
			printf( '<br />' );
			printf( esc_html__( 'Order Number: %s', 'myticket-events' ), esc_html($order_id) );
            printf( '<br />' );
            printf( esc_html__( 'Payment Method: %s', 'myticket-events' ), $order->get_payment_method_title() );
            printf( '<br />' );
			printf( esc_html__( 'Billing Address: %s', 'myticket-events' ), $order->get_formatted_billing_address() );
			
		?>
		</td>
	</tr>
</table>

<table width="100%" cellpadding="5" cellspacing="1" style="padding-top:80px;font-size:12px;">
	<thead width="100%">
		<tr class="heading" bgcolor="#eeeeee;">
			<th>
				<?php echo esc_html__( 'Ticket', 'myticket-events' ); ?>
			</th>

			<th>
				<?php echo esc_html__( 'Qty', 'myticket-events' ); ?>
			</th>

			<th>
				<?php echo esc_html__( 'Price', 'myticket-events' ); ?>
			</th>
		</tr>
	</thead>
	<tbody width="100%" style="text-align:center;font-size:14px;">
	<?php foreach ( $line_items as $item_id => $item ) { ?>
		<tr class="item">
			<td width="50%">

				<?php 
				/* use this code to display image
				$product   = wc_get_product( $item->get_product_id() );
				$image_id  = $product->get_image_id();
				$image_url = wp_get_attachment_image_url( $image_id, 'medium' );

				echo '<img style="max-height:100px;" src="'.esc_url($image_url).'" />';
				*/
				
				?>
				
				<?php 


					/*
					Use this code to view all available keys. Usefull when using non english locales and want to add new lines to the list below
					wc_display_item_meta( $item, array("echo" => true) ); 
					*/

					echo '<div style="font-size:16px;" >'.esc_html( $item['name'] ).'</div>';  
					echo '<div style="font-size:12px;" >';

						// comment in or out these fields to reflect on final PDF
						$date 		= wc_get_order_item_meta( $item_id, "date"); 	echo '<b>'.esc_html__( 'Date:', 'myticket-events' )."</b> ".esc_html($date)."<br>";
						$time 		= wc_get_order_item_meta( $item_id, "time"); 	echo '<b>'.esc_html__( 'Time:', 'myticket-events' )."</b> ".esc_html($time)."<br>";
						$venue 		= wc_get_order_item_meta( $item_id, "venue"); 	echo '<b>'.esc_html__( 'Venue:', 'myticket-events' )."</b> ".esc_html($venue)."<br>";
						// $address 	= wc_get_order_item_meta( $item_id, "address"); echo '<b>'.esc_html__( 'Address:', 'myticket-events' )."</b> ".esc_html($address)."<br>";
						// $zone 		= wc_get_order_item_meta( $item_id, "zone"); 	echo '<b>'.esc_html__( 'Zone:', 'myticket-events' )."</b> ".esc_html($zone)."<br>";
						$seat 		= wc_get_order_item_meta( $item_id, "seat"); 	echo '<b>'.esc_html__( 'Seat:', 'myticket-events' )."</b> ".esc_html($seat)."<br>";
						$row 		= wc_get_order_item_meta( $item_id, "row"); 	echo '<b>'.esc_html__( 'Row:', 'myticket-events' )."</b> ".esc_html($row)."<br>";

					echo '</div>';
		
				?>
			</td>

			<td style="text-align:center;">
				<?php echo esc_html( $item['qty'] ); ?>
			</td>

			<td style="text-align:center;">
				<?php echo $order->get_formatted_line_subtotal( $item ); ?>
			</td>
		</tr>
	<?php } ?>

	<tr class="spacer">
		<td></td>
	</tr>


	</tbody>
</table>

<table width="100%" cellpadding="3" cellspacing="0" style="padding-top:60px;font-size:12px;">

    <?php $first = true;
    foreach ( $order->get_order_item_totals() as $key => $total ) {
		$class = str_replace( '_', '-', $key );
		?>

		<tr class="total">
			<td></td>
			<td style="<?php echo (($first)?"border-bottom:3px solid #333;":""); $first = false;?> text-align:right;width:240px;" class="border <?php echo esc_attr( $class ); ?>"><?php echo esc_html( $total['label'] ); ?> <?php echo wp_kses_post( $total['value'] ); ?></td>
		</tr>

	<?php } ?>

</table>

<?php foreach ( $line_items as $item_id => $item ) { 
	
	$arg['echo'] = false;

	// fields to display
	$name 		= wc_get_order_item_meta( $item_id, "name");
	$date 		= wc_get_order_item_meta( $item_id, "date");
	$time 		= wc_get_order_item_meta( $item_id, "time");
	$venue 		= wc_get_order_item_meta( $item_id, "venue"); 
	$address 	= wc_get_order_item_meta( $item_id, "address"); 
	$seat 		= wc_get_order_item_meta( $item_id, "seat"); 
	$row 		= wc_get_order_item_meta( $item_id, "row"); 
	
	?>

	<pagebreak>

	<table width="100%" cellpadding="0" cellspacing="0">
		<tr class="top">
			<td width="50%">
				<?php
				/* Ajax urls */
				$ajaxurl = '';
				if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
					$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
				} else {
					$ajaxurl .= admin_url( 'admin-ajax.php');
				} ?>
				<barcode code="<?php echo "myticket:".esc_attr($order_id).",".esc_url($ajaxurl).",".esc_attr($item_id).",0,0"; ?>" size="2.4" type="QR" error="M" disableborder="1" class="barcode" />
			</td>
		
			<td width="50%" style="text-align:right;">
			<?php
				printf( '<h2>%s</h2>', esc_html( $name ) );
				printf( '<p style="font-size:14px;">%s #'.esc_html($order_id).'/'.esc_html($item_id).'</p>', esc_html__( 'Ticket ID:', 'myticket-events' ) );
				printf( '<br />' );
				printf( '<br />' );
				printf( '<span>'.esc_html__( 'Event:', 'myticket-events' ).'</span> %s', esc_html( $item['name'] ) );
				printf( '<br />' );
				printf( '<span>'.esc_html__( 'Venue:', 'myticket-events' ).'</span> %s', esc_html( $venue ) );
				printf( '<br />' );
				printf( '<span>'.esc_html__( 'Time:', 'myticket-events' ).'</span> %s', esc_html( $time ) );
				printf( '<br />' );
				printf( '<br />' );
				printf( '<br />' );
				printf( '<br />' );
				printf( '<br />' );
				printf( '<br />' );
			?>
			</td>
		</tr>
	</table>

	<table width="100%" cellpadding="5" cellspacing="1" style="padding-top:80px;font-size:12px;">
		<thead width="100%">
			<tr class="heading" bgcolor="#eeeeee;">
				<th>
					<?php echo esc_html__( 'Ticket', 'myticket-events' ); ?>
				</th>

				<th>
					<?php echo esc_html__( 'Qty', 'myticket-events' ); ?>
				</th>

				<th>
					<?php echo esc_html__( 'Price', 'myticket-events' ); ?>
				</th>
			</tr>
		</thead>
		<tbody width="100%" style="text-align:center;">
	
			<tr class="item">
				<td width="50%">
				
					<?php echo '<div style="font-size:16px;" >'.esc_html( $item['name'] ).'</div>';  
					echo '<div style="font-size:12px;" >';

					// comment in or out these fields to reflect on final PDF
					$date 		= wc_get_order_item_meta( $item_id, "date"); 	echo '<b>'.esc_html__( 'Date:', 'myticket-events' )."</b> ".esc_html($date)."<br>";
					$time 		= wc_get_order_item_meta( $item_id, "time"); 	echo '<b>'.esc_html__( 'Time:', 'myticket-events' )."</b> ".esc_html($time)."<br>";
					$venue 		= wc_get_order_item_meta( $item_id, "venue"); 	echo '<b>'.esc_html__( 'Venue:', 'myticket-events' )."</b> ".esc_html($venue)."<br>";
					// $address 	= wc_get_order_item_meta( $item_id, "address"); echo '<b>'.esc_html__( 'Address:', 'myticket-events' )."</b> ".esc_html($address)."<br>";
					// $zone 		= wc_get_order_item_meta( $item_id, "zone"); 	echo '<b>'.esc_html__( 'Zone:', 'myticket-events' )."</b> ".esc_html($zone)."<br>";
					$seat 		= wc_get_order_item_meta( $item_id, "seat"); 	echo '<b>'.esc_html__( 'Seat:', 'myticket-events' )."</b> ".esc_html($seat)."<br>";
					$row 		= wc_get_order_item_meta( $item_id, "row"); 	echo '<b>'.esc_html__( 'Row:', 'myticket-events' )."</b> ".esc_html($row)."<br>";

					echo '</div>'; ?>
				</td>

				<td style="text-align:center;">
					<?php echo esc_html( $item['qty'] ); ?>
				</td>

				<td style="text-align:center;">
					<?php echo $order->get_formatted_line_subtotal( $item ); ?>
				</td>
			</tr>

			<tr class="spacer">
				<td></td>
			</tr>

		</tbody>
	</table>

<?php } ?>

<?php
$html = ob_get_contents();
ob_end_clean();

#########################
## TICKET TEMPLATE END ##
#########################
// $html = iconv('UTF-8', 'UTF-8//IGNORE', $html);
// $html = iconv('UTF-8', 'UTF-8//TRANSLIT', $html);
// $mpdf->SetAutoFont();
// $mpdf->autoScriptToLang = true;
// $mpdf->autoLangToFont   = true;
// $mpdf->onlyCoreFonts   = false;
$mpdf->autoLangToFont = true;

//$mpdf->mode = '+aCJK';
//die;
$mpdf->WriteHTML($html);

//print to file and return its path
if ($to_file){
    
	$mpdf->Output($ticketDir."/".$order_id.".pdf",'F');
    return $ticketDir."/".$order_id.".pdf";
    
//print to browser
}else{
    $mpdf->Output();
}

?>