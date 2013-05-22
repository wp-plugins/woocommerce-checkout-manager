<?php
/*

Plugin Name: WooCommerce Checkout Manager
Plugin URI: http://www.trottyzone.com/product/woocommerce-checkout-manager
Description: Manages WooCommerce Checkout fields
Version: 1.2
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


// Hook will fire upon activation - we are using it to set default option values
register_activation_hook( __FILE__, 'wccs_activation_function' );



// Add options and populate default values on first load
function wccs_activation_function() {

	// populate plugin options array
	$wccs_plugin_options = array(
		'billing_form'      => '0',
		);

       // create field in WP_options to store all plugin data in one field
	add_option( 'wccs_plugin_options', $wccs_plugin_options );

	

}


// Hook for adding admin menus
if ( is_admin() ){ // admin actions

  // Hook for adding admin menu
  add_action( 'admin_menu', 'wccs_admin_menu' );


// Display the 'Settings' link in the plugin row on the installed plugins list page
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wccs_admin_plugin_actions', -10);


} else { // non-admin enqueues, actions, and filters
// hook to get option values and dynamically render css to support the tab classes
		

}


// action function for above hook
function wccs_admin_menu() {

    // Add a new submenu under Settings:
    add_options_page('WooCommerce Checkout Manager','WooCommerce Checkout Manager', 'manage_options', 'wccs_settings', 'wccs__options_page');

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


    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

// Read their posted value
add_option('select_all_rm', TRUE);
add_option('wccs_opt_1', TRUE);   
add_option('wccs_opt_2', TRUE);   
add_option('wccs_opt_3', TRUE);   
add_option('wccs_opt_4', TRUE);   
add_option('wccs_opt_5', TRUE);   
add_option('wccs_opt_6', TRUE);   
add_option('wccs_opt_7', TRUE);   
add_option('wccs_opt_8', TRUE);   
add_option('wccs_opt_9', TRUE);   
add_option('wccs_opt_10', TRUE);   
add_option('wccs_opt_11', TRUE);   
add_option('wccs_opt_12', TRUE); 
add_option('wccs_rq_1', TRUE);   
add_option('wccs_rq_2', TRUE);   
add_option('wccs_rq_3', TRUE);   
add_option('wccs_rq_4', TRUE);   
add_option('wccs_rq_5', TRUE);   
add_option('wccs_rq_6', TRUE);   
add_option('wccs_rq_7', TRUE);   
add_option('wccs_rq_8', TRUE);   
add_option('wccs_rq_9', TRUE);   
add_option('wccs_rq_10', TRUE);   
add_option('wccs_rq_11', TRUE);   
add_option('wccs_rq_12', TRUE);        
       

        // Save the posted value in the database
update_option('select_all_rm', (bool) $_POST["select_all_rm"]);
update_option('wccs_opt_1', (bool) $_POST["wccs_opt_1"]);
update_option('wccs_opt_2', (bool) $_POST["wccs_opt_2"]);
update_option('wccs_opt_3', (bool) $_POST["wccs_opt_3"]);
update_option('wccs_opt_4', (bool) $_POST["wccs_opt_4"]);
update_option('wccs_opt_5', (bool) $_POST["wccs_opt_5"]);
update_option('wccs_opt_6', (bool) $_POST["wccs_opt_6"]);
update_option('wccs_opt_7', (bool) $_POST["wccs_opt_7"]);
update_option('wccs_opt_8', (bool) $_POST["wccs_opt_8"]);
update_option('wccs_opt_9', (bool) $_POST["wccs_opt_9"]);
update_option('wccs_opt_10', (bool) $_POST["wccs_opt_10"]);
update_option('wccs_opt_11', (bool) $_POST["wccs_opt_11"]);
update_option('wccs_opt_12', (bool) $_POST["wccs_opt_12"]);
update_option('wccs_rq_1', (bool) $_POST["wccs_rq_1"]);
update_option('wccs_rq_2', (bool) $_POST["wccs_rq_2"]);
update_option('wccs_rq_3', (bool) $_POST["wccs_rq_3"]);
update_option('wccs_rq_4', (bool) $_POST["wccs_rq_4"]);
update_option('wccs_rq_5', (bool) $_POST["wccs_rq_5"]);
update_option('wccs_rq_6', (bool) $_POST["wccs_rq_6"]);
update_option('wccs_rq_7', (bool) $_POST["wccs_rq_7"]);
update_option('wccs_rq_8', (bool) $_POST["wccs_rq_8"]);
update_option('wccs_rq_9', (bool) $_POST["wccs_rq_9"]);
update_option('wccs_rq_10', (bool) $_POST["wccs_rq_10"]);
update_option('wccs_rq_11', (bool) $_POST["wccs_rq_11"]);
update_option('wccs_rq_12', (bool) $_POST["wccs_rq_12"]);

?>

<div class="updated"><p><strong><?php _e('settings saved.'); ?></strong></p></div>

<?php 

    }



    // Now display the settings editing screen

    echo '<div class="wrap">';
    
    // icon for settings
       screen_icon(); 

    // header

    echo "<h2>" . __( 'WooCommerce Checkout Manager') . "</h2>";

    // settings form 
    
    ?>

<div style="float:right;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <div class="paypal-donations">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="business" value="Y4PYHHG6NRZNW">
<input type="hidden" name="item_name" value="Donation"><input type="hidden" name="rm" value="0"><input type="hidden" name="currency_code" value="USD"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online."><img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">    </div>
</form></div>


<form name="form" method="post" action="" id="frm1">


<script type="text/javascript">

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rm').click(function() {
            jQuery('.rm').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rm').click(function() {
            jQuery('.rm,#select_all_rm').attr('checked', false);
        });
});
});


jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rq').click(function() {
            jQuery('.rq').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rq').click(function() {
            jQuery('.rq,#select_all_rq').attr('checked', false);
        });
});
});

</script>

<table class="widefat" border="1" style="margin-top:20px;">

<thead>
	<tr>
		<th>Field Name</th>
		<th>Remove Field</th>		
		<th>Not Required?</th>
	</tr>
</thead>
<tfoot>
    <tr>
	        <th>Field Name</th>
		<th>Remove Field</th>		
		<th>Not Required?</th>
    </tr>
</tfoot>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<td><strong>Select "All"</strong></td>
		<td><input name="select_all_rm" type="checkbox" id="select_all_rm" value="checkboxrm" <?php if (get_option('select_all_rm')) echo "checked='checked'"; ?> /></td>
                <td><input name="select_all_rq" type="checkbox" id="select_all_rq" value="checkboxrq" <?php if (get_option('select_all_rq')) echo "checked='checked'"; ?> /></td>
		</tr>
</tbody>


<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>First Name</td>
     <td><input name="wccs_opt_1" type="checkbox" class="rm" value="checkbox1" <?php if (get_option('wccs_opt_1')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_1" type="checkbox" class="rq" value="checkboxrq1" <?php if (get_option('wccs_rq_1')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Last Name</td>
     <td><input name="wccs_opt_2" type="checkbox" class="rm" value="checkbox2" <?php if (get_option('wccs_opt_2')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_2" type="checkbox" class="rq" value="checkboxrq2" <?php if (get_option('wccs_rq_2')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Company</td>
     <td><input name="wccs_opt_3" type="checkbox" class="rm" value="checkbox3" <?php if (get_option('wccs_opt_3')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_3" type="checkbox" class="rq" value="checkboxrq3" <?php if (get_option('wccs_rq_3')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Address 1</td>
     <td><input name="wccs_opt_4" type="checkbox" class="rm" value="checkbox4" <?php if (get_option('wccs_opt_4')) echo "checked='checked'"; ?></td>
     <td><input name="wccs_rq_4" type="checkbox" class="rq" value="checkboxrq4" <?php if (get_option('wccs_rq_4')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Address 2</td>
     <td><input name="wccs_opt_5" type="checkbox" class="rm" value="checkbox5" <?php if (get_option('wccs_opt_5')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_5" type="checkbox" class="rq" value="checkboxrq5" <?php if (get_option('wccs_rq_5')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>City</td>
     <td><input name="wccs_opt_6" type="checkbox" class="rm" value="checkbox6" <?php if (get_option('wccs_opt_6')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_6" type="checkbox" class="rq" value="checkboxrq6" <?php if (get_option('wccs_rq_6')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Postal Code</td>
     <td><input name="wccs_opt_7" type="checkbox" class="rm" value="checkbox7" <?php if (get_option('wccs_opt_7')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_7" type="checkbox" class="rq" value="checkboxrq7" <?php if (get_option('wccs_rq_7')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Country</td>
     <td><input name="wccs_opt_8" type="checkbox" class="rm" value="checkbox8" <?php if (get_option('wccs_opt_8')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_8" type="checkbox" class="rq" value="checkboxrq8" <?php if (get_option('wccs_rq_8')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>State</td>
     <td><input name="wccs_opt_9" type="checkbox" class="rm" value="checkbox9" <?php if (get_option('wccs_opt_9')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_9" type="checkbox" class="rq" value="checkboxrq9" <?php if (get_option('wccs_rq_9')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Phone</td>
     <td><input name="wccs_opt_10" type="checkbox" class="rm" value="checkbox10" <?php if (get_option('wccs_opt_10')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_10" type="checkbox" class="rq" value="checkboxrq10" <?php if (get_option('wccs_rq_10')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Email</td>
     <td><input name="wccs_opt_11" type="checkbox" class="rm" value="checkbox11" <?php if (get_option('wccs_opt_11')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_11" type="checkbox" class="rq" value="checkboxrq11" <?php if (get_option('wccs_rq_11')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>

<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
     <td>Order Comments</td>
     <td><input name="wccs_opt_12" type="checkbox" class="rm" value="checkbox12" <?php if (get_option('wccs_opt_12')) echo "checked='checked'"; ?> /></td>
     <td><input name="wccs_rq_12" type="checkbox" class="rq" value="checkboxrq12" <?php if (get_option('wccs_rq_12')) echo "checked='checked'"; ?> /></td>
   </tr>
</tbody>


<tbody>
   <tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<td><strong>Deselect "All"</strong></td>
		<td><input name="deselect_all_rm" type="checkbox" id="deselect_all_rm" value="checkboxrm" <?php if (get_option('deselect_all_rm')) echo "checked='checked'"; ?> /></td>
                <td><input name="deselect_all_rq" type="checkbox" id="deselect_all_rq" value="checkboxrq" <?php if (get_option('deselect_all_rq')) echo "checked='checked'"; ?> /></td>
		</tr>
</tbody>
 



<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
Their is no need to select check boxes from both "Not Required?" and "Remove Field" columns. Please select from either.
</form>
</table>

<?php
}


// Build array of links for rendering in installed plugins list
function wccs_admin_plugin_actions($links) {

	$links[] = '<a href="options-general.php?page=wccs_settings">'.__('Settings').'</a>';
	return $links;

}

// ================== First Name ===================
function wccs_override_checkout_fields1($fields1) {
if ( TRUE== get_option('wccs_opt_1') ) 
unset($fields1['billing']['billing_first_name']);
return $fields1;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields1' );
 
function wccs_override_required_fields1( $address_fields1 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields1['billing_first_name']['required'] = false;
	return $address_fields1;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields1', 10, 1 );

// ========== Last Name ==============
function wccs_override_checkout_fields2($fields2) {
if ( TRUE== get_option('wccs_opt_2') ) 
unset($fields2['billing']['billing_last_name']);
return $fields2;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields2' );

function wccs_override_required_fields2( $address_fields2 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields2['billing_last_name']['required'] = false;
	return $address_fields2;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields2', 10, 1 );

// ============ Company ==============
function wccs_override_checkout_fields3($fields3) {
if ( TRUE==  get_option('wccs_opt_3') ) 
unset($fields3['billing']['billing_company']);
return $fields3;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields3' );

function wccs_override_required_fields3( $address_fields3 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields3['billing_company']['required'] = false;
	return $address_fields3;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields3', 10, 1 );

// ============ Address 1 ==============
function wccs_override_checkout_fields4($fields4) {
if ( TRUE==  get_option('wccs_opt_4') ) 
unset($fields4['billing']['billing_address_1']);
return $fields4;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields4' );

function wccs_override_required_fields4( $address_fields4 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields4['billing_address_1']['required'] = false;
	return $address_fields4;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields4', 10, 1 );

// ============ Address 2 ===============
function wccs_override_checkout_fields5($fields5) {
if ( TRUE==  get_option('wccs_opt_5') ) 
unset($fields5['billing']['billing_address_2']);
return $fields5;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields5' );

function wccs_override_required_fields5( $address_fields5 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields5['billing_address_2']['required'] = false;
	return $address_fields5;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields5', 10, 1 );

// ============= City ================
function wccs_override_checkout_fields6($fields6) {
if ( TRUE==  get_option('wccs_opt_6')  ) 
unset($fields6['billing']['billing_city']);
return $fields6;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields6' );

function wccs_override_required_fields6( $address_fields6 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields6['billing_city']['required'] = false;
	return $address_fields6;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields6', 10, 1 );

// ============= Postal Code ================
function wccs_override_checkout_fields7($fields7) {
if ( TRUE==  get_option('wccs_opt_7')  ) 
unset($fields7['billing']['billing_postcode']);
return $fields7;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields7' );

function wccs_override_required_fields7( $address_fields7 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields7['billing_postcode']['required'] = false;
	return $address_fields7;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields7', 10, 1 );

// =================== Country==================
function wccs_override_checkout_fields8($fields8) {
if ( TRUE==  get_option('wccs_opt_8')  ) 
unset($fields8['billing']['billing_country']);
return $fields8;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields8' );

function wccs_override_required_fields8( $address_fields8 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields8['billing_country']['required'] = false;
	return $address_fields8;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields8', 10, 1 );

// ================= State ===================
function wccs_override_checkout_fields9($fields9) {
if ( TRUE==  get_option('wccs_opt_9') ) 
unset($fields9['billing']['billing_state']);
return $fields9;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields9' );

function wccs_override_required_fields9( $address_fields9 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields9['billing_state']['required'] = false;
	return $address_fields9;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields9', 10, 1 );

// =============== Phone ======================
function wccs_override_checkout_fields10($fields10) {
if ( TRUE== get_option('wccs_opt_10') ) 
unset($fields10['billing']['billing_phone']);
return $fields10;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields10' );

function wccs_override_required_fields10( $address_fields10 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields10['billing_phone']['required'] = false;
	return $address_fields10;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields10', 10, 1 );

// ====================  Email ========================
function wccs_override_checkout_fields11($fields11) {
if ( TRUE== get_option('wccs_opt_11') ) 
unset($fields11['billing']['billing_email']);
return $fields11;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields11' );

function wccs_override_required_fields11( $address_fields11 ) {
if ( TRUE== get_option('wccs_rq_1') ) 
	$address_fields11['billing_email']['required'] = false;
	return $address_fields11;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields11', 10, 1 );

// ===================== Order Comments ==========================
function wccs_override_checkout_fields12($fields12) {
if ( TRUE==  get_option('wccs_opt_12') )
unset($fields12['order']['order_comments']);
return $fields12;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields12' );

function wccs_override_required_fields12( $address_fields12 ) {
if ( TRUE== get_option('wccs_rq_12') ) 
	$address_fields12['order_comments']['required'] = false;
	return $address_fields12;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields12', 10, 1 );

// ======================= Custom Css ====================
function custom_css_add_on_msssc() {
if ( TRUE== get_option('select_all_rm') ) 
echo '<style type="text/css">
#customer_details {
display:none;
}
</style>';
}
add_filter( 'wp_head' , 'custom_css_add_on_msssc' );