<?php
/*

Plugin Name: WooCommerce Checkout Manager
Plugin URI: http://www.trottyzone.com/product/woocommerce-checkout-manager
Description: Manages WooCommerce Checkout fields
Version: 2.0
Author: Ephrain Marchan
Author URI: http://www.trottyzone.com
License: GPLv2 or later
*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/


if ( ! defined( 'ABSPATH' ) ) die();

register_activation_hook( __FILE__, 'wccs_install' );
register_uninstall_hook( __FILE__, 'wccs_uninstall' );
function wccs_uninstall() {
	delete_option( 'wccs_settings' );
}

function wccs_install() {
	$defaults = array( 'buttons' => array( array(
			'label'  => __( 'Example Label' ),
			'placeholder' => __( 'Example placeholder' )
			
		)
	) );

	add_option( 'wccs_settings', apply_filters( 'wccs_defaults', $defaults ) );
}

// Hook for adding admin menus
if ( is_admin() ){ // admin actions

  // Hook for adding admin menu
  add_action( 'admin_menu', 'wccs_admin_menu' );


// Display the 'Settings' link in the plugin row on the installed plugins list page
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wccs_admin_plugin_actions', -10);


add_action( 'admin_init', 'wccs_register_setting' );


} else { // non-admin enqueues, actions, and filters
// hook to get option values and dynamically render css to support the tab classes
		

}


// action function for above hook
function wccs_admin_menu() {

    // Add a new submenu under Settings:
    add_options_page('WooCommerce Checkout Manager','WooCommerce Checkout Manager', 'manage_options', __FILE__ , 'wccs__options_page');

}

function wccs_register_setting() {	
register_setting( 'wccs_options', 'wccs_settings' );
}


// fc_settings_page() displays the page content 
function wccs__options_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }


// variables for the field and option names 
    $hidden_field_name = 'mccs_submit_hidden';
	

// read options values
$options = get_option( 'wccs_settings' );

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        // Save the posted value in the database
update_option( 'wccs_settings', $options );

?>

<div class="updated"><p><strong><?php _e('settings saved.'); ?></strong></p></div>

<?php 

    }



    // Now display the settings editing screen

    echo '<div class="wrap">';
    
    // icon for settings
    echo '<div id="icon-themes" class="icon32"><br></div>';

    // header

    echo "<h2>" . __( 'WooCommerce Checkout Manager') . "</h2>";

    // settings form 
    
    ?>

<div style="float:right;">
<form class="custom_tty" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="WS7EEU66VUQE8">
<table>
<tr><td><input type="hidden" name="on0" value="Apreciation Machine">Appreciation Machine</td></tr><tr><td><select name="os0">
	<option value="Contribution -">Contribution - $5.00 USD</option>
	<option value="Buy Me Beer (^0^) -">Buy Me Beer (^0^) - $10.00 USD</option>
	<option value="Sponsor Hosting -">Sponsor Hosting - $15.00 USD</option>
	<option value="Ultimate Appreciation (^_^) -">Ultimate Appreciation (^_^) - $30.00 USD</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>


<form name="form" method="post" action="options.php" id="frm1">

<?php
        	settings_fields( 'wccs_options' );
			$options = get_option( 'wccs_settings' );
		?>




<table class="widefat" border="1" >

<thead>
	<tr>
		<th>Field Name</th>
		<th>Remove Field Entirely</th>		
		<th>Remove Required Attribute</th>
	</tr>
</thead>
<tfoot>
    <tr>
	        <th>Field Name</th>
		<th>Remove Field Entirely</th>		
		<th>Remove Required Attribute</th>
    </tr>
</tfoot>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<td><strong>Select All Check boxes in this Column</strong></td>
		<td><input name="wccs_settings[checkness][select_all_rm]" type="checkbox" id="select_all_rm" value="1" <?php if (  1 == ($options['checkness']['select_all_rm'])) echo "checked='checked'"; ?> /></td>
                <td><input name="wccs_settings[checkness][select_all_rq]" type="checkbox" id="select_all_rq" value="1" <?php if (  1 == ($options['checkness']['select_all_rq'])) echo "checked='checked'"; ?> /></td>
		</tr>
</tbody>


<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>First Name</td>
     <td><input name="wccs_settings[checkness][wccs_opt_1]" type="checkbox" class="rm" value="1" 
     <?php if (  1 == ($options['checkness']['wccs_opt_1']) ) echo 'checked="checked"'; ?>   /></td>

     <td><input name="wccs_settings[checkness][wccs_rq_1]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_1'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Last Name</td>
     <td><input name="wccs_settings[checkness][wccs_opt_2]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_2'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_2]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_2'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Company</td>
     <td><input name="wccs_settings[checkness][wccs_opt_3]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_3'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_3]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_3'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Address 1</td>
     <td><input name="wccs_settings[checkness][wccs_opt_4]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_4'])) echo "checked='checked'"; ?></td>
     <td><input name="wccs_settings[checkness][wccs_rq_4]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_4'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Address 2</td>
     <td><input name="wccs_settings[checkness][wccs_opt_5]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_5'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_5]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_5'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>City</td>
     <td><input name="wccs_settings[checkness][wccs_opt_6]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_6'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_6]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_6'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Postal Code</td>
     <td><input name="wccs_settings[checkness][wccs_opt_7]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_7'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_7]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_7'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Country</td>
     <td><input name="wccs_settings[checkness][wccs_opt_8]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_8'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_8]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_8'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>State</td>
     <td><input name="wccs_settings[checkness][wccs_opt_9]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_9'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_9]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_9'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Phone</td>
     <td><input name="wccs_settings[checkness][wccs_opt_10]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_10'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_10]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_10'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Email</td>
     <td><input name="wccs_settings[checkness][wccs_opt_11]" type="checkbox" class="rm" value="1" <?php if (get_option('wccs_opt_11')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_11]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_11'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Order Comments</td>
     <td><input name="wccs_settings[checkness][wccs_opt_12]" type="checkbox" class="rm" value="1" <?php if (  1 == ($options['checkness']['wccs_opt_12'])) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_settings[checkness][wccs_rq_12]" type="checkbox" class="rq" value="1" <?php if (  1 == ($options['checkness']['wccs_rq_12'])) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>


<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<td><strong>Deselect Check boxes in this Column</strong></td>
		<td><input name="wccs_settings[checkness][deselect_all_rm]" type="checkbox" id="deselect_all_rm" value="1" <?php if (  1 == ($options['checkness']['deselect_all_rm'])) echo "checked='checked'"; ?> /></td>
                <td><input name="wccs_settings[checkness][deselect_all_rq]" type="checkbox" id="deselect_all_rq" value="1" <?php if (  1 == ($options['checkness']['deselect_all_rq'])) echo "checked='checked'"; ?> /></td>
		</tr>
</tbody>
 



<?php submit_button( __( 'Save Changes', 'woocommerce-checkout-manager' ) ); ?>
<div style="float:right;">Personal WordPress Themes and Plugins Support: <a href="http://www.trottyzone.com/forums/forum/website-support/">Visit Our Forum</a>.</div>
<div style="margin:5px 0 15px 10px;color:green;font-size:18px;float:left;">:: Checkout Page Section ::</div>
</table>


<div style="margin:20px 0 15px 10px;color:green;font-size:18px;">:: Receipt Section ::</div>
<table class="widefat" border="1">

<thead>
	<tr>
		<th>Field Name</th>
		<th>New Value?</th>		
	</tr>
</thead>


<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Footer Content: </td>
     <td style="width: 80%;"><textarea name="wccs_settings[footer][mccs_receipt_footer]" style="width: 100%;height:200px;"><?php echo esc_attr($options['footer']['mccs_receipt_footer']); ?></textarea></td>
   </tr>
</tbody>
</table>


<div style="margin:20px 0 15px 10px;color:green;font-size:18px;">:: Add New Field Section ::</div>

<style type="text/css">
.wccs-clone {
display:none;
}
.wccs-order {
cursor:move;
}
.wccs-table > tbody > tr > td {
	background: #fff;
    border-right: 1px solid #ededed;
    border-bottom: 1px solid #ededed;
    padding: 8px;
    position: relative;
}

.wccs-table > tbody > tr:last-child td { border-bottom: 0 none; }
.wccs-table > tbody > tr td:last-child { border-right: 0 none; }
.wccs-table > thead > tr > th { border-right: 1px solid #e1e1e1; }
.wccs-table > thead > tr > th:last-child { border-right: 0 none; }

.wccs-table tr td.wccs-order,
.wccs-table tr th.wccs-order {
	width: 16px;
	text-align: center;
	vertical-align: middle;
	color: #aaa;
	text-shadow: #fff 0 1px 0;
}

.wccs-table .wccs-remove {
	width: 16px;
	vertical-align: middle;
}
.wccs-table input[type="text"] {
width: 100%;
}

.wccs-table-footer {
	position: relative;
	overflow: hidden;
	margin-top: 10px;
	padding: 8px 0;
}
</style>

<table class="widefat wccs-table" border="1">

<thead>
	<tr>
<th style="width:5%;" class="wccs-order" title="<?php esc_attr_e( 'Change order' , 'woocommerce-checkout-manager' ); ?>"></th>
		<th>Label</th>
		<th>Placeholder</th>
                <th width="12%">Required Attribute</th>
                <th scope="col" title="<?php esc_attr_e( 'Remove button', 'woocommerce-checkout-manager' ); ?>"><!-- remove --></th>		
	</tr>
</thead>
<tbody>


<?php
                	if ( !empty ( $options['buttons'] ) ) :

					// Loop through all the buttons
                	for ( $i = 0; $i < count( $options['buttons'] ); $i++ ) :

                		if ( ! isset( $options['buttons'][$i] ) )
                			break;


                ?>



   <tr valign="top" class="wccs-row">
<td class="wccs-order" title="<?php esc_attr_e( 'Change order', 'woocommerce-checkout-manager' ); ?>"><?php echo $i + 1; ?></td>

<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
 

          <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][label]"  
          value="<?php echo esc_attr( $options['buttons'][$i][label] ); ?>" /></td>

          <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][placeholder]"  
          value="<?php echo esc_attr( $options['buttons'][$i][placeholder] ); ?>" /></td>

<td><input name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox" value="true" <?php if (  true == ($options['buttons'][$i][checkbox])) echo "checked='checked'"; ?> /></td>

<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Remove Field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>
   </tr>

<?php endfor; endif; ?>
<!-- Empty -->

                    <?php $i = 999; ?>

<tr valign="top" class="wccs-clone" >

<td class="wccs-order" title="<?php esc_attr_e( 'Change order' , 'woocommerce-checkout-manager' ); ?>"><?php echo $i; ?></td>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

       <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][label]"  
       title="<?php esc_attr_e( 'Label of the New Field', 'woocommerce-checkout-manager' ); ?>" value="" /></td>

       <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][placeholder]"  
       title="<?php esc_attr_e( 'Placeholder - Preview of Data to Input', 'woocommerce-checkout-manager' ); ?>" value=" " /></td>

   <td><input name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox" 
    title="<?php esc_attr_e( 'Add/Remove Required Attribute', 'woocommerce-checkout-manager' ); ?>" value=" " /></td>

<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Remove Field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>

   </tr>
</tbody>

</table>

<div class="wccs-table-footer">
<a href="javascript:;" id="wccs-add-button" class="button-secondary"><?php _e( '+ Add New Field' , 'woocommerce-checkout-manager' ); ?></a>
</div>

</form>
<?php
}



// Build array of links for rendering in installed plugins list
function wccs_admin_plugin_actions($links) {

	$wccs_plugin_links = array(
          '<a href="options-general.php?page=woocommerce-checkout-manager/woocommerce-checkout-manager.php">'.__('Settings').'</a>',
           '<a href="http://www.trottyzone.com/forums/forum/website-support/">'.__('Support').'</a>', 
                             );

	return array_merge( $wccs_plugin_links, $links );

}


function wccs_scripts( $hook_suffix ) {
	if ( $hook_suffix == 'settings_page_woocommerce-checkout-manager/woocommerce-checkout-manager' ) {
		
		wp_enqueue_script( 'script_wccs', plugins_url( 'script_wccs.js', __FILE__ ), array( 'jquery' ), '1.2' );
		
	}
}

add_action( 'admin_enqueue_scripts', 'wccs_scripts' );


// ================== First Name ===================
function wccs_override_checkout_fields1($fields1) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_1'] ) )
unset($fields1['billing']['billing_first_name']);
return $fields1;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields1' );
 
function wccs_override_required_fields1( $address_fields1 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_1'] ) ) 
	$address_fields1['billing_first_name']['required'] = false;
	return $address_fields1;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields1', 10, 1 );

// ========== Last Name ==============
function wccs_override_checkout_fields2($fields2) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_2'] ) ) 
unset($fields2['billing']['billing_last_name']);
return $fields2;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields2' );

function wccs_override_required_fields2( $address_fields2 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_2'] ) ) 
	$address_fields2['billing_last_name']['required'] = false;
	return $address_fields2;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields2', 10, 1 );

// ============ Company ==============
function wccs_override_checkout_fields3($fields3) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_3'] ) ) 
unset($fields3['billing']['billing_company']);
return $fields3;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields3' );

function wccs_override_required_fields3( $address_fields3 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_3'] ) ) 
	$address_fields3['billing_company']['required'] = false;
	return $address_fields3;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields3', 10, 1 );

// ============ Address 1 ==============
function wccs_override_checkout_fields4($fields4) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_4'] ) ) 
unset($fields4['billing']['billing_address_1']);
return $fields4;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields4' );

function wccs_override_required_fields4( $address_fields4 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_4'] ) ) 
	$address_fields4['billing_address_1']['required'] = false;
	return $address_fields4;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields4', 10, 1 );

// ============ Address 2 ===============
function wccs_override_checkout_fields5($fields5) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_5'] ) ) 
unset($fields5['billing']['billing_address_2']);
return $fields5;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields5' );

function wccs_override_required_fields5( $address_fields5 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_5'] ) ) 
	$address_fields5['billing_address_2']['required'] = false;
	return $address_fields5;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields5', 10, 1 );

// ============= City ================
function wccs_override_checkout_fields6($fields6) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_6'] )  ) 
unset($fields6['billing']['billing_city']);
return $fields6;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields6' );

function wccs_override_required_fields6( $address_fields6 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_6'] ) ) 
	$address_fields6['billing_city']['required'] = false;
	return $address_fields6;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields6', 10, 1 );

// ============= Postal Code ================
function wccs_override_checkout_fields7($fields7) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_7'] )  ) 
unset($fields7['billing']['billing_postcode']);
return $fields7;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields7' );

function wccs_override_required_fields7( $address_fields7 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_7'] ) ) 
	$address_fields7['billing_postcode']['required'] = false;
	return $address_fields7;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields7', 10, 1 );

// =================== Country==================
function wccs_override_checkout_fields8($fields8) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_8'] )  ) 
unset($fields8['billing']['billing_country']);
return $fields8;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields8' );

function wccs_override_required_fields8( $address_fields8 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_8'] ) ) 
	$address_fields8['billing_country']['required'] = false;
	return $address_fields8;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields8', 10, 1 );

// ================= State ===================
function wccs_override_checkout_fields9($fields9) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_9'] ) ) 
unset($fields9['billing']['billing_state']);
return $fields9;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields9' );

function wccs_override_required_fields9( $address_fields9 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_9'] ) ) 
	$address_fields9['billing_state']['required'] = false;
	return $address_fields9;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields9', 10, 1 );

// =============== Phone ======================
function wccs_override_checkout_fields10($fields10) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_10'] ) ) 
unset($fields10['billing']['billing_phone']);
return $fields10;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields10' );

function wccs_override_required_fields10( $address_fields10 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_10'] ) ) 
	$address_fields10['billing_phone']['required'] = false;
	return $address_fields10;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields10', 10, 1 );

// ====================  Email ========================
function wccs_override_checkout_fields11($fields11) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_11'] ) ) 
unset($fields11['billing']['billing_email']);
return $fields11;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields11' );

function wccs_override_required_fields11( $address_fields11 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_11'] ) ) 
	$address_fields11['billing_email']['required'] = false;
	return $address_fields11;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields11', 10, 1 );

// ===================== Order Comments ==========================
function wccs_override_checkout_fields12($fields12) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_opt_12'] ) )
unset($fields12['order']['order_comments']);
return $fields12;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields12' );

function wccs_override_required_fields12( $address_fields12 ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_12'] ) ) 
	$address_fields12['order_comments']['required'] = false;
	return $address_fields12;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields12', 10, 1 );

// ======================= Custom Css ====================
function custom_css_add_on_msssc() {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['select_all_rm'] ) ) 
echo '<style type="text/css">
#customer_details {
display:none;
}
</style>';
}
add_filter( 'wp_head' , 'custom_css_add_on_msssc' );

// ====================== reciept hook  ===================
function custom_email_setup_wc_footer() {
$options = get_option( 'wccs_settings' );
		echo ''.$options['footer']['mccs_receipt_footer'].'';
	}
add_action( 'woocommerce_email_footer_text', 'custom_email_setup_wc_footer' );
remove_filter( 'woocommerce_email_footer_text', 'strip_tags' );



// =============== Add the field to the checkout =====================
function wccs_custom_checkout_field( $checkout ) {

$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) : ?>

<?php
					$i = 0;

					// Loop through each button
					foreach ( $options['buttons'] as $btn ) :

						$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

?>				
<?php

if ( ! empty( $btn['label'] ) )

   woocommerce_form_field( ''.$btn['label'].'' , array(
        'type'          => 'text',
        'class'         => array('wccs-field-class wccs-form-row-wide'), 
        'label'         => __( ''.$btn['label'].''),
        'required'  => $btn['checkbox'],
        'placeholder'       => __( ''.$btn['placeholder'].''),

        ), $checkout->get_value( ''.$btn['label'].'' )); 

?>
<?php 
$i++;
					endforeach;
?>
<?php
endif;

}
add_action('woocommerce_after_order_notes', 'wccs_custom_checkout_field');

// ============================== Update the order meta with field value ==============================
function wccs_custom_checkout_field_update_order_meta( $order_id ) {

$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) : 
					$i = 0;

					// Loop through each button
					foreach ( $options['buttons'] as $btn ) :

						$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( ! empty( $btn['label'] ) )
    if ( $_POST[ ''.$btn['label'].'' ])
 update_post_meta( $order_id, ''.$btn['label'].'' , esc_attr( $_POST[ ''.$btn['label'].'' ] ));
$i++;
					endforeach;
endif;
}
add_action('woocommerce_checkout_update_order_meta', 'wccs_custom_checkout_field_update_order_meta');

// =============== Add to email (working) =====================
add_filter('woocommerce_email_order_meta_keys', 'wccs_custom_checkout_field_order_meta_keys');
 
function wccs_custom_checkout_field_order_meta_keys( $keys ) {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) : 
					$i = 0;
					// Loop through each button
					foreach ( $options['buttons'] as $btn ) :

						$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( ! empty( $btn['label'] ) )
	$keys[] = ''.$btn['label'].'';
$i++;
					endforeach;
return $keys;
endif;
}
// ================ Style the Email =======================
add_action('woocommerce_email_after_order_table', 'wccs_custom_style_checkout_email');
function wccs_custom_style_checkout_email() {
echo '<h2>Additional information</h2>';

}