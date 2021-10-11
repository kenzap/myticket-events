<?php defined( 'ABSPATH' ) or exit;

// load mpdf and WooCommerce variables
require_once MYTICKET_PATH . 'inc/mpdf/vendor/mpdf/mpdf/mpdf.php';

$mpdf                           = new mPDF(['debug' => true]);
$order                          = new WC_Order( $order_id );
$uploads                        = wp_get_upload_dir();
$ticketDir                      = $uploads['basedir']."/tickets";
$formatted_shipping_address     = $order->get_formatted_shipping_address();
$formatted_billing_address      = $order->get_formatted_billing_address();
$line_items                     = $order->get_items( 'line_item' );
$name                           = wc_get_order_item_meta($order_item_id, "name");
$file                           = sanitize_file_name($ticketDir."/".$order_id."_".$order_item_id.".pdf");

// make sure that ticket directory exists
wp_mkdir_p($ticketDir);

// Ajax urls 
$ajaxurl = '';
if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
    $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
} else {
    $ajaxurl .= admin_url( 'admin-ajax.php');
}

$header = '<!--mpdf
    <htmlpageheader name="header">
        <table width="100%" style="font-family: sans-serif;padding-left:20px;padding-top:190px;">
            <tr>
                <td width="50%" style="color:#111111;margin-top:150px;text-align:center;">
                    <barcode code="'."myticket:".esc_attr($order_id).",".esc_url($ajaxurl).",".esc_attr($order_item_id).",0,0".'" size="1.3" type="QR" error="M" disableborder="1" class="barcode" />
                    <br/>
                    <br/>
                    <br/>
                    <span style="width:50px;font-weight:bold;font-size:20pt;text-align:center;">'.str_replace( ' ', '<br/>',  esc_html($name) ).'</span><br />
                    <br/>
                 </td>
                <td width="50%" style="text-align: right; vertical-align: top;">
       
                </td>
            </tr>
        </table>
    </htmlpageheader>
    
mpdf-->

<style>
    @page {
        margin-top: 0cm;
        margin-bottom: 0cm;
        margin-left: 0cm;
        margin-right: 0cm;
        footer: html_letterfooter2;
        background-color: pink;
        background-image: url("' . plugins_url( 'background.jpg', __FILE__ ). '");
        background-repeat: no-repeat;
        background-size: cover; 
    }
  
    @page :first {
        margin-top: 8cm;
        margin-bottom: 4cm;
        header: html_header;
        footer: _blank;
        resetpagenum: 1;
        background-color: lightblue;
    }

</style>';

$mpdf->img_dpi = 150;
$mpdf->WriteHTML($header);

// print to file and return its path
if ($to_file){
    
    $mpdf->Output($file,'F');
    return $file;
    
// print to browser
}else{
    $mpdf->Output();
}

?>