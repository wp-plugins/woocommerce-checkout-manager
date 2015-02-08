<?php
/*
Plugin Name: WooCommerce Checkout Manager
Plugin URI: http://www.trottyzone.com/product/woocommerce-checkout-manager-pro
Description: Manages WooCommerce Checkout fields
Version: 3.6.6
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

load_plugin_textdomain('woocommerce-checkout-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');


register_activation_hook( __FILE__, 'wccs_install' ); 
add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', 'return 1800;') );
add_filter( 'wcdn_order_info_fields', 'wccm_woocommerce_delivery_notes_compat', 10, 2 );
add_action( 'admin_enqueue_scripts', 'wccs_scripts' );
add_action('woocommerce_after_checkout_billing_form', 'wccs_add_title');
add_action('woocommerce_after_checkout_billing_form', 'wccs_custom_checkout_field');
add_action('woocommerce_checkout_update_order_meta', 'wccs_custom_checkout_field_update_order_meta');
add_action('woocommerce_email_after_order_table', 'wccs_custom_style_checkout_email');
add_action('woocommerce_checkout_process', 'wccs_custom_checkout_field_process');
add_action('woocommerce_order_details_after_order_table', 'wccs_custom_checkout_details');
add_action('wp_head','display_front_wccs');


function wccs_install() {
    
    $defaults = array( 'buttons' => array( 
                                        array(
                                            'label'  => __( 'Example Label', 'woocommerce-checkout-manager' ),
    		                                'placeholder' => __( 'Example placeholder', 'woocommerce-checkout-manager' ),
			                                'cow' => __('myfld1', 'woocommerce-checkout-manager'),
			                                'option_a' =>  __('option 1', 'woocommerce-checkout-manager'),
			                                'option_b' =>  __('option 2', 'woocommerce-checkout-manager')
		                                  )
	                               ),
                                        'checkness' => array( 
                                                        'checkbox1' => true,
                                                        'checkbox12' => true
                                                        )
                );
                
	add_option( 'wccs_settings', apply_filters( 'wccs_defaults', $defaults ) );
}



if ( is_admin() ){ 
        add_action( 'admin_menu', 'wccs_admin_menu' );
	    add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wccs_admin_plugin_actions', -10);
        add_action( 'admin_init', 'wccs_register_setting' );
} else { 
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style( 'jquery-ui-style', (is_ssl()) ? 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' : 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		add_action('wp_enqueue_script', 'wccs_non_admin_scripts');
}

function wccs_non_admin_scripts() {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style( 'jquery-ui-style', (is_ssl()) ? 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' : 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
}


function wccs_admin_menu() {
        add_submenu_page( 'woocommerce', __('Woocommerce Checkout Manager', 'woocommerce-checkout-manager'), __('WooCCM', 'woocommerce-checkout-manager'), 'manage_options', __FILE__ , 'wccs__options_page');
}


function wccs_register_setting() {	
        register_setting( 'wccs_options', 'wccs_settings', 'wccs_options_validate' );
}


function wccs__options_page() {
    if ( !current_user_can('manage_options') ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    
$hidden_field_name = 'mccs_submit_hidden';
$options = get_option( 'wccs_settings' );


    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {   
        update_option( 'wccs_settings', $options );
        
        ?> <div class="updated"><p><strong><?php _e('settings saved.'); ?></strong></p></div>
        <?php 
    }

    echo '<div class="wrap">';
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2>'. __( 'WooCommerce Checkout Manager', 'woocommerce-checkout-manager' ) .'';
    
    ?>
    
    <!-- RSS FEED -->
    <div class="wooccm_feed">
        <?php include_once( ABSPATH . WPINC . '/feed.php' );
                $rss = fetch_feed( 'http://www.trottyzone.com/category/news-feed/' );

                    if ( ! is_wp_error( $rss ) ) :

                        // Figure out how many total items there are, but limit it to 5. 
                        $maxitems = $rss->get_item_quantity( 1 ); 
                    
                        // Build an array of all the items, starting with element 0 (first element).
                        $rss_items = $rss->get_items( 0, $maxitems );

                    endif;
                    
                    if ( $maxitems == 0 ) :
                      else : 
                          
                        // Loop through each feed item and display each item as a hyperlink.
                        foreach ( $rss_items as $item ) : ?>
    
                            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                                title="<?php printf( __( 'Posted %s', 'woocommerce-checkout-manager' ), $item->get_date('j F Y | g:i a') ); ?>">
                                <?php echo esc_html( $item->get_title() ); ?>
                            </a>
    
                       <?php endforeach;
                    endif; ?>

    </div></h2>
    <!-- FEED -->
    
    
    <form name="form" method="post" action="options.php" id="frm1">
    <?php settings_fields( 'wccs_options' );
		  $options = get_option( 'wccs_settings' ); ?>
          
            <table class="widefat" border="1" style="margin-bottom:10px;">
            <thead>
            	<tr>
            		<th><?php _e('Field Name', 'woocommerce-checkout-manager');  ?></th>
            		<th><?php _e('Remove Field Entirely', 'woocommerce-checkout-manager');  ?></th>		
            		<th><?php _e('Remove Required Attribute', 'woocommerce-checkout-manager');  ?></th>
                            <th class="wccs_replace"><?php _e('Replace Label Name', 'woocommerce-checkout-manager');  ?></th>
                            <th class="wccs_replace"><?php _e('Replace Placeholder Name', 'woocommerce-checkout-manager');  ?></th>
            	</tr>
            </thead>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            		<td><strong><?php _e('Select All Check boxes in this Column', 'woocommerce-checkout-manager');  ?></strong></td>
            		<td><input name="wccs_settings[checkness][select_all_rm]" type="checkbox" id="select_all_rm" value="1" <?php echo (isset($options['checkness']['select_all_rm'])) ? "checked='checked'" : ""; ?> /></td>
                    <td><input name="wccs_settings[checkness][select_all_rq]" type="checkbox" id="select_all_rq" value="1" <?php echo (isset($options['checkness']['select_all_rq'])) ? "checked='checked'" : ""; ?> /></td>
            <td></td>
            <td></td>
            		</tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('First Name', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_1]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_1'])) ? "checked='checked'" : ""; ?>   /></td>
                 
                 <td><input name="wccs_settings[checkness][wccs_rq_1]" type="checkbox" class="rq" value="1" <?php echo (isset($options['checkness']['wccs_rq_1'])) ? "checked='checked'" : ""; ?> /></td>
                 
                 
            <td><input type="text" name="wccs_settings[replace][label]"  
                      value="<?php echo esc_attr( $options['replace']['label'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Last Name', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_2]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_2'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input name="wccs_settings[checkness][wccs_rq_2]" type="checkbox" class="rq" value="1" <?php echo (isset($options['checkness']['wccs_rq_2'])) ? "checked='checked'" : ""; ?> /></td>
            <td><input type="text" name="wccs_settings[replace][label1]"  
                      value="<?php echo esc_attr( $options['replace']['label1'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder1]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder1'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Country', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_8]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_8'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input name="wccs_settings[checkness][wccs_rq_8]" type="checkbox" class="rq" value="1" <?php echo (isset($options['checkness']['wccs_rq_8'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input type="text" name="wccs_settings[replace][label2]"  
                      value="<?php echo esc_attr( $options['replace']['label2'] ); ?>" /></td>
                <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Phone', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_10]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_10'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input name="wccs_settings[checkness][wccs_rq_10]" type="checkbox" class="rq" value="1" <?php echo (isset($options['checkness']['wccs_rq_10'])) ? "checked='checked'" : ""; ?> /></td>
            <td><input type="text" name="wccs_settings[replace][label3]"  
                      value="<?php echo esc_attr( $options['replace']['label3'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder3]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder3'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Email', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_11]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_11'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input name="wccs_settings[checkness][wccs_rq_11]" type="checkbox" class="rq" value="1" <?php echo (isset($options['checkness']['wccs_rq_11'])) ? "checked='checked'" : ""; ?> /></td>
            <td><input type="text" name="wccs_settings[replace][label4]"  
                      value="<?php echo esc_attr( $options['replace']['label4'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder4]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder4'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Company', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_3]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_3'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td><input type="text" name="wccs_settings[replace][label5]"  
                      value="<?php echo esc_attr( $options['replace']['label5'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder5]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder5'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Order Notes', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_12]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_12'])) ? "checked='checked'" : ""; ?> /></td>
            <td></td>
            <td><input type="text" name="wccs_settings[replace][label11]"  
                      value="<?php echo esc_attr( $options['replace']['label11'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder11]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder11'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Address 1', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_4]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_4'])) ? "checked='checked'" : ""; ?></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Address 2', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_5]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_5'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('City', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_6]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_6'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Postal Code', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_7]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_7'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('State', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[checkness][wccs_opt_9]" type="checkbox" class="rm" value="1" <?php echo (isset($options['checkness']['wccs_opt_9'])) ? "checked='checked'" : ""; ?> /></td>
                <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <?php submit_button( __( 'Save Changes', 'woocommerce-checkout-manager' ) ); ?>
            <div class="wooccm_title"><?php _e('Billing Section', 'woocommerce-checkout-manager');  ?></div>
            </table>
            <table class="widefat" border="1" >
            <thead>
            	<tr>
            		<th><?php _e('Field Name', 'woocommerce-checkout-manager');  ?></th>
            		<th><?php _e('Remove Field Entirely', 'woocommerce-checkout-manager');  ?></th>		
            		<th><?php _e('Remove Required Attribute', 'woocommerce-checkout-manager');  ?></th>
                            <th class="wccs_replace"><?php _e('Replace Label Name', 'woocommerce-checkout-manager');  ?></th>
                            <th class="wccs_replace"><?php _e('Replace Placeholder Name', 'woocommerce-checkout-manager');  ?></th>
            	</tr>
            </thead>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            		<td><strong><?php _e('Select All Check boxes in this Column', 'woocommerce-checkout-manager');  ?></strong></td>
            		<td><input name="wccs_settings[check][select_all_rm_s]" type="checkbox" id="select_all_rm_s" value="1" <?php echo (isset($options['check']['select_all_rm_s'])) ? "checked='checked'" : ""; ?> /></td>
                            <td><input name="wccs_settings[check][select_all_rq_s]" type="checkbox" id="select_all_rq_s" value="1" <?php echo (isset($options['check']['select_all_rq_s'])) ? "checked='checked'" : ""; ?> /></td>
            <td></td>
            <td></td>
            		</tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('First Name', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_1_s]" type="checkbox" class="rm_s" value="1" 
                 <?php echo (isset($options['check']['wccs_opt_1_s'])) ? "checked='checked'" : ""; ?>   /></td>
                 <td><input name="wccs_settings[check][wccs_rq_1_s]" type="checkbox" class="rq_s" value="1" <?php echo (isset($options['check']['wccs_rq_1_s'])) ? "checked='checked'" : ""; ?> /></td>
            <td><input type="text" name="wccs_settings[replace][label_s]"  
                      value="<?php echo esc_attr( $options['replace']['label_s'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder_s]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder_s'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Last Name', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_2_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_2_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td><input name="wccs_settings[check][wccs_rq_2_s]" type="checkbox" class="rq_s" value="1" <?php echo (isset($options['check']['wccs_rq_2_s'])) ? "checked='checked'" : ""; ?> /></td>
            <td><input type="text" name="wccs_settings[replace][label_s1]"  
                      value="<?php echo esc_attr( $options['replace']['label_s1'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder_s1]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder_s1'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Company', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_3_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_3_s'])) ? "checked='checked'" : ""; ?> /></td>
            <td></td>
            <td><input type="text" name="wccs_settings[replace][label_s2]"  
                      value="<?php echo esc_attr( $options['replace']['label_s2'] ); ?>" /></td>
            <td><input type="text" name="wccs_settings[replace][placeholder_s2]"  
                      value="<?php echo esc_attr( $options['replace']['placeholder_s2'] ); ?>" /></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Country', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_8_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_8_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td><input type="text" name="wccs_settings[replace][label_s7]"  
                      value="<?php echo esc_attr( $options['replace']['label_s7'] ); ?>" /></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Address 1', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_4_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_4_s'])) ? "checked='checked'" : ""; ?></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Address 2', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_5_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_5_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('City', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_6_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_6_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('Postal Code', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_7_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_7_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <tbody>
               <tr>
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                 <td><?php _e('State', 'woocommerce-checkout-manager');  ?></td>
                 <td><input name="wccs_settings[check][wccs_opt_9_s]" type="checkbox" class="rm_s" value="1" <?php echo (isset($options['check']['wccs_opt_9_s'])) ? "checked='checked'" : ""; ?> /></td>
                 <td></td>
            <td></td>
            <td></td>
               </tr>
            </tbody>
            <div class="wooccm_title"><?php _e('Shipping Section', 'woocommerce-checkout-manager');  ?></div>
            </table>
            
            <div style="padding:5px 10px;font-weight:700;color:green;" class="clear_dwccm">


                <div class="updated clear_wccm data">
                        <div class="clear_wccm title">
                            <a href="http://www.trottyzone.com/product/woocommerce-checkout-manager-pro/"><?php _e('TRY THE PRO VERSION', 'woocommerce-checkout-manager');  ?></a><br /><span class="small"><?php _e('includes these field types', 'woocommerce-checkout-manager');  ?></span> &#8594;
                        </div>
                        <ul>
                            <li><?php _e('Text Area', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Password', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Mult-Select', 'woocommerce-checkout-manager'); ?></li>
                        </ul>
                        <ul>
                            <li><?php _e('Radio Button (Unlimited Options)', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Select Options (Unlimited Options)', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Time Picker', 'woocommerce-checkout-manager'); ?></li>
                        </ul>
                        <ul>
                            <li><?php _e('Text/ Html Swapper', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Color Picker', 'woocommerce-checkout-manager'); ?></li>
                            <li><?php _e('Heading', 'woocommerce-checkout-manager'); ?></li>
                        </ul>
                        <ul>
                            <li><?php _e('Check Box (Put your own options)', 'woocommerce-checkout-manager'); ?></li>
                            <li><strong><a href="http://www.trottyzone.com/product/woocommerce-checkout-manager-pro/"><?php _e('more features. . .', 'woocommerce-checkout-manager'); ?></a></strong></li>
                        </ul>
                </div>
        </div>
        
        <div class="wooccm_title" ><?php _e('Add New Field Section', 'woocommerce-checkout-manager');  ?></div>
            <table class="widefat" border="1" >
                <thead>
                   <tr>
                        <th><?php _e('Enable Title - Additional information', 'atc-menu');  ?></th>		
                        <th><input style="float:left;" name="wccs_settings[checkness][checkbox12]" type="checkbox" value="true" <?php echo (isset($options['checkness']['checkbox12'])) ? "checked='checked'": ""; ?> /></th>
                        <th><?php _e('Checkout Page', 'atc-menu');  ?></th>
                        <th><input style="float:left;" name="wccs_settings[checkness][checkbox1]" type="checkbox" value="true" <?php echo (isset($options['checkness']['checkbox1'])) ? "checked='checked'": ""; ?> /></th>
                        <th><?php _e('Checkout Details and Email Receipt', 'atc-menu');  ?></th>
                    </tr>
                </thead>
            </table>

            <table class="widefat wccs-table" border="1">
                <thead>
                	<tr>
                        <th style="width:3%;" class="wccs-order" title="<?php esc_attr_e( 'Change order' , 'woocommerce-checkout-manager' ); ?>">#</th>
                		<th><?php _e('Label' , 'woocommerce-checkout-manager' ); ?></th>
                		<th><?php _e('Placeholder' , 'woocommerce-checkout-manager' ); ?></th>
                        <th><?php _e('Option A' , 'woocommerce-checkout-manager' ); ?></th>
                        <th><?php _e('Options B' , 'woocommerce-checkout-manager' ); ?></th>
                        <th width="5%"><?php _e('Choose Type' , 'woocommerce-checkout-manager' ); ?></th>
                        <th width="5%"><?php _e('Abbreviation' , 'woocommerce-checkout-manager' ); ?></th>
                        <th width="5%"><?php _e('Required' , 'woocommerce-checkout-manager' ); ?></th>
                        <th style="text-align:center;" scope="col" title="<?php esc_attr_e( 'Remove button', 'woocommerce-checkout-manager' ); ?>">X</th>		
        	        </tr>
                </thead>
                <tbody>

                <?php if ( isset ( $options['buttons'] ) ) :
                	for ( $i = 0; $i < count( $options['buttons'] ); $i++ ) :
                		if ( ! isset( $options['buttons'][$i] ) )
                			break; ?>
                            
                   <tr valign="top" class="wccs-row">
                            <td class="wccs-order" title="<?php esc_attr_e( 'Change order', 'woocommerce-checkout-manager' ); ?>"><?php echo $i + 1; ?></td>
                    
                            <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][label]"  
                            value="<?php echo esc_attr($options['buttons'][$i]['label']); ?>" /></td>
                              
                            <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][placeholder]"  
                            value="<?php echo esc_attr($options['buttons'][$i]['placeholder']); ?>" /></td>
                          
                            <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_a]"  
                            value="<?php echo esc_attr( $options['buttons'][$i]['option_a'] ); ?>" /></td>
                          
                            <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_b]"  
                            value="<?php echo esc_attr( $options['buttons'][$i]['option_b'] ); ?>" /></td>
                          
                            <td>
                                <select name="wccs_settings[buttons][<?php echo $i; ?>][type]" >  <!--Call run() function-->
                                     <option value="text" <?php selected( $options['buttons'][$i]['type'], 'text' ); ?>>Text Input</option>
                                     <option value="select" <?php selected( $options['buttons'][$i]['type'], 'select' ); ?>>Select Options</option>
                                     <option value="date" <?php selected( $options['buttons'][$i]['type'], 'date' ); ?>>Date Picker</option>
                                     <option value="checkbox" <?php selected( $options['buttons'][$i]['type'], 'checkbox' ); ?>>Checkbox (1, 0)</option>    
                                </select>
                            </td>
                            
                            <td><input type="text" maxlength="10" name="wccs_settings[buttons][<?php echo $i; ?>][cow]"  
                            value="<?php echo esc_attr($options['buttons'][$i]['cow']); ?>" readonly="readonly" /></td>
                          
                            <td style="text-align:center;"><input style="float:none;" name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox" value="true" <?php echo (isset($options['buttons'][$i]['checkbox'])) ? "checked='checked'": ""; ?> /></td>
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
                               title="<?php esc_attr_e( 'Placeholder - Preview of Data to Input', 'woocommerce-checkout-manager' ); ?>" value="" /></td>
                               <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_a]" 
                               title="<?php esc_attr_e( 'Insert Option A', 'woocommerce-checkout-manager' ); ?>" value="" /></td>
                               <td><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_b]" 
                               title="<?php esc_attr_e( 'Insert Option B', 'woocommerce-checkout-manager' ); ?>" value="" /></td>
                        <td>
                        <select name="wccs_settings[buttons][<?php echo $i; ?>][type]" >  <!--Call run() function-->
                             <option value="text" >Text Input</option>
                             <option value="select" >Select Options</option>
                             <option value="date" >Date Picker</option>
                             <option value="checkbox" >Checkbox (1, 0)</option>    
                        </select>
                        </td>
                        <td><input type="text" maxlength="5" name="wccs_settings[buttons][<?php echo $i; ?>][cow]"  
                               title="<?php esc_attr_e( 'Abbreviation (No spaces)', 'woocommerce-checkout-manager' ); ?>" value="" readonly="readonly" /></td>
                           <td style="text-align:center;"><input style="float:none;" name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox" 
                            title="<?php esc_attr_e( 'Add/Remove Required Attribute', 'woocommerce-checkout-manager' ); ?>" value="" /></td>
                        <td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Remove Field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>
                    </tr>
                </tbody>
        </table>
        
        <div class="wccs-table-footer">
            <a href="javascript:;" id="wccs-add-button" class="button-secondary"><?php _e( '+ Add New Field' , 'woocommerce-checkout-manager' ); ?></a>
        </div>
</form>

<?php }




// Build array of links for rendering in installed plugins list
function wccs_admin_plugin_actions($links) {
	$wccs_plugin_links = array(
          '<a href="admin.php?page=woocommerce-checkout-manager/woocommerce-checkout-manager.php">'.__('Settings').'</a>',
           '<a href="http://www.trottyzone.com/product/woocommerce-checkout-manager-pro/">'.__('Pro Version').'</a>', 
                             );
	return array_merge( $wccs_plugin_links, $links );
}

function wccs_scripts( $hook_suffix ) {
	if ( $hook_suffix == 'woocommerce_page_woocommerce-checkout-manager/woocommerce-checkout-manager' ) {
		wp_enqueue_style( 'wooccm-style',  plugins_url( 'style_wccs.css', __FILE__ ) );
        wp_enqueue_script( 'script_wccs', plugins_url( 'script_wccs.js', __FILE__ ), array( 'jquery' ), '1.2' );
		if(!wp_script_is('jquery-ui-sortable', 'queue')){
                wp_enqueue_script('jquery-ui-sortable');
        }
	}
}


function wccs_override_checkout_fields($fields) {
$options = get_option( 'wccs_settings' );

if (  1 == ($options['checkness']['wccs_opt_1'] ) ) {
    unset($fields['billing']['billing_first_name']);
}
if ( ! empty( $options['replace']['placeholder'] ) ) {
     $fields['billing']['billing_first_name']['placeholder'] = wpml_string_wccm($options['replace']['placeholder']);
}
if ( ! empty( $options['replace']['label'] ) ) {
     $fields['billing']['billing_first_name']['label'] = wpml_string_wccm($options['replace']['label']);
}
if (  1 == ($options['checkness']['wccs_opt_2'] ) ) {
    unset($fields['billing']['billing_last_name']);
} 
if ( ! empty( $options['replace']['placeholder1'] ) ) {
    $fields['billing']['billing_last_name']['placeholder'] = wpml_string_wccm($options['replace']['placeholder1']);
}
if ( ! empty( $options['replace']['label1'] ) ) {
    $fields['billing']['billing_last_name']['label'] = wpml_string_wccm($options['replace']['label1']);
}
if (  1 == ($options['checkness']['wccs_opt_3'] ) ) {
    unset($fields['billing']['billing_company']);
}
if ( ! empty( $options['replace']['placeholder2'] ) ) {
    $fields['billing']['billing_company']['placeholder'] = wpml_string_wccm($options['replace']['placeholder2']);
}
if ( ! empty( $options['replace']['label5'] ) ) {
    $fields['billing']['billing_company']['label'] = wpml_string_wccm($options['replace']['label5']);
}
if (  1 == ($options['checkness']['wccs_opt_4'] ) ) {
    unset($fields['billing']['billing_address_1']);
}
if (  1 == ($options['checkness']['wccs_opt_5'] ) ) {
    unset($fields['billing']['billing_address_2']);
}
if (  1 == ($options['checkness']['wccs_opt_6'] ) ) { 
    unset($fields['billing']['billing_city']);
}
if (  1 == ($options['checkness']['wccs_opt_7'] ) ) { 
    unset($fields['billing']['billing_postcode']);
}
if (  1 == ($options['checkness']['wccs_opt_8'] )  ) {
    unset($fields['billing']['billing_country']);
}
if (  1 == ($options['checkness']['wccs_opt_9'] ) ) {
    unset($fields9['billing']['billing_state']);
}
if (  1 == ($options['checkness']['wccs_opt_10'] ) ) {
    unset($fields10['billing']['billing_phone']);
}
if ( ! empty( $options['replace']['label2'] ) ) {
     $fields['billing']['billing_country']['label'] = wpml_string_wccm($options['replace']['label2']);
}
if ( ! empty( $options['replace']['placeholder3'] ) ) {
     $fields['billing']['billing_phone']['placeholder'] = wpml_string_wccm($options['replace']['placeholder3']);
}
if ( ! empty( $options['replace']['label3'] ) ) {
     $fields['billing']['billing_phone']['label'] = wpml_string_wccm($options['replace']['label3']);
}
if (  1 == ($options['checkness']['wccs_opt_11'] ) ) {
    unset($fields11['billing']['billing_email']);
}
if ( ! empty( $options['replace']['placeholder4'] ) ) {
     $fields['billing']['billing_email']['placeholder'] = wpml_string_wccm($options['replace']['placeholder4']);
}
if ( ! empty( $options['replace']['label4'] ) ) {
     $fields['billing']['billing_email']['label'] = wpml_string_wccm($options['replace']['label4']);
}
if (  1 == ($options['checkness']['wccs_opt_12'] ) ) {
    unset($fields12['order']['order_comments']);
}
if ( ! empty( $options['replace']['placeholder11'] ) ) {
     $fields['order']['order_comments']['placeholder'] = wpml_string_wccm($options['replace']['placeholder11']);
}
if ( ! empty( $options['replace']['label11'] ) ) {
     $fields['order']['order_comments']['label'] = wpml_string_wccm($options['replace']['label11']);
}
if (  1 == ($options['check']['wccs_opt_1_s'] ) ) {
unset($fields1['shipping']['shipping_first_name']);
}
if ( ! empty( $options['replace']['placeholder_s'] ) ) {
     $fields['shipping']['shipping_first_name']['placeholder'] = wpml_string_wccm($options['replace']['placeholder_s']);
}
if ( ! empty( $options['replace']['label_s'] ) ) {
     $fields['shipping']['shipping_first_name']['label'] = wpml_string_wccm($options['replace']['label_s']);
}
if (  1 == ($options['check']['wccs_opt_2_s'] ) ) {
unset($fields2['shipping']['shipping_last_name']);
}
if ( ! empty( $options['replace']['placeholder_s1'] ) ) {
     $fields['shipping']['shipping_last_name']['placeholder'] = wpml_string_wccm($options['replace']['placeholder_s1']);
}
if ( ! empty( $options['replace']['label_s1'] ) ) {
     $fields['shipping']['shipping_last_name']['label'] = wpml_string_wccm($options['replace']['label_s1']);
}
if (  1 == ($options['check']['wccs_opt_3_s'] ) ) {
unset($fields3['shipping']['shipping_company']);
}
if ( ! empty( $options['replace']['placeholder_s2'] ) ) {
     $fields['shipping']['shipping_company']['placeholder'] = wpml_string_wccm($options['replace']['placeholder_s2']);
}
if ( ! empty( $options['replace']['label_s2'] ) ) {
     $fields['shipping']['shipping_company']['label'] = wpml_string_wccm($options['replace']['label_s2']);
}
if (  1 == ($options['check']['wccs_opt_4_s'] ) ) {
     unset($fields['shipping']['shipping_address_1']);
}
if (  1 == ($options['check']['wccs_opt_5_s'] ) ) {
     unset($fields['shipping']['shipping_address_2']);
}
if (  1 == ($options['check']['wccs_opt_6_s'] ) ) {
     unset($fields['shipping']['shipping_city']);
}
if (  1 == ($options['check']['wccs_opt_7_s'] ) ) {
     unset($fields['shipping']['shipping_postcode']);
}
if (  1 == ($options['check']['wccs_opt_8_s'] ) ) {
     unset($fields['shipping']['shipping_country']);
}
if ( ! empty( $options['replace']['label_s7'] ) ) {
     $fields['shipping']['shipping_country']['label'] = wpml_string_wccm($options['replace']['label_s7']);
}
if (  1 == ($options['check']['wccs_opt_9_s'] ) ) {
     unset($fields['shipping']['shipping_state']);
}
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields' );





function wccs_override_required_fields( $address_fields ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['checkness']['wccs_rq_2'] ) ) {
    $address_fields['billing_last_name']['required'] = false;
}
if (  1 == ($options['checkness']['wccs_rq_1'] ) ) {
    $address_fields['billing_first_name']['required'] = false;
}
if (  1 == ($options['checkness']['wccs_rq_8'] ) ) {
	$address_fields['billing_country']['required'] = false;
}
if (  1 == ($options['checkness']['wccs_rq_10'] ) ) {
	$address_fields['billing_phone']['required'] = false;
}
if (  1 == ($options['checkness']['wccs_rq_11'] ) ) {
    $address_fields['billing_email']['required'] = false;
}
return $address_fields;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields', 10, 1 );



function wccs_shipping_required_fields( $address_fields ) {
$options = get_option( 'wccs_settings' );
if (  1 == ($options['check']['wccs_rq_1_s'] ) ) {
    $address_fields['shipping_first_name']['required'] = false;
}
if (  1 == ($options['check']['wccs_rq_2_s'] ) ) {
	$address_fields['shipping_last_name']['required'] = false;
}
return $address_fields;
}
add_filter( 'woocommerce_shipping_fields', 'wccs_shipping_required_fields', 10, 1 );





function wccs_add_title() {
$options = get_option( 'wccs_settings' );
    if (true == ($options['checkness']['checkbox12']) )  
        echo '<div class="add_info_wccs"><br><h3>' . __( 'Additional information', 'woocommerce-checkout-manager' ) . '</h3></div>';
}



// =============== Add the field to the checkout =====================
function wccs_custom_checkout_field( $checkout ) {
$options = get_option( 'wccs_settings' );
    if ( count( $options['buttons'] ) > 0 ) : 
		foreach ( $options['buttons'] as $btn ) :

                if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'text') ) {
                   woocommerce_form_field( ''.$btn['cow'].'' , array(
                        'type'          => 'text',
                        'class'         => array('wccs-field-class wccs-form-row-wide'), 
                        'label'         =>  wpml_string_wccm(''.$btn['label'].''),
                        'required'  => $btn['checkbox'],
                        'placeholder'       => wpml_string_wccm(''.$btn['placeholder'].''),
                        ), $checkout->get_value( ''.$btn['cow'].'' )); 
                }
                if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'select') ) {
                   woocommerce_form_field( ''.$btn['cow'].'' , array(
                        'type'          => 'select',
                        'class'         => array('wccs-field-class wccs-form-row-wide'), 
                        'label'         =>  wpml_string_wccm(''.$btn['label'].''),
                        'options'     => array(
                             '' => __('Select below', 'woocommerce-checkout-manager' ),
                             ''.wpml_string_wccm(''.$btn['option_a'].'').'' => ''.wpml_string_wccm(''.$btn['option_a'].'').'',
                             ''.wpml_string_wccm(''.$btn['option_b'].'').'' => ''.wpml_string_wccm(''.$btn['option_b'].'').''
                                                                 ),
                        'required'  => $btn['checkbox'],
                        'placeholder'       => wpml_string_wccm(''.$btn['placeholder'].''),
                        ), $checkout->get_value( ''.$btn['cow'].'' )); 
                }
                if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'date') ) {
                        echo '<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery(".MyDate-'.$btn['cow'].' #'.$btn['cow'].'").datepicker({
                                        dateFormat : "dd-mm-yy"
                                    });
                                });
                                </script>';
                woocommerce_form_field( ''.$btn['cow'].'' , array(
                        'type'          => 'text',
                        'class'         => array('wccs-field-class MyDate-'.$btn['cow'].' wccs-form-row-wide'), 
                        'label'         =>  wpml_string_wccm(''.$btn['label'].''),
                        'required'  => $btn['checkbox'],
                        'placeholder'       => wpml_string_wccm(''.$btn['placeholder'].''),
                        ), $checkout->get_value( ''.$btn['cow'].'' )); 
                }
                if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'checkbox') ) {
                woocommerce_form_field( ''.$btn['cow'].'' , array(
                        'type'          => 'checkbox',
                        'class'         => array('wccs-field-class wccs-form-row-wide'), 
                        'label'         =>  wpml_string_wccm(''.$btn['label'].''),
                        'required'  => $btn['checkbox'],
                        'placeholder'       => wpml_string_wccm(''.$btn['placeholder'].''),
                        ), $checkout->get_value( ''.$btn['cow'].'' )); 
                }
		endforeach;
endif;
}


// ============================== Update the order meta with field value ==============================
function wccs_custom_checkout_field_update_order_meta( $order_id ) {
$options = get_option( 'wccs_settings' );
    if ( count( $options['buttons'] ) > 0 ) : 
			foreach ( $options['buttons'] as $btn ) :
                if ( ! empty( $btn['cow'] ) )
                    if ( $_POST[ ''.$btn['cow'].'' ])
                 update_post_meta( $order_id, ''.$btn['cow'].'' , esc_attr( $_POST[ ''.$btn['cow'].'' ] ));
            endforeach;
    endif;
}

// =============== Add to email (working) =====================
add_filter('woocommerce_email_order_meta_keys', 'wccs_custom_checkout_field_order_meta_keys');
function wccs_custom_checkout_field_order_meta_keys( $keys ) {
$options = get_option( 'wccs_settings' );
    if ( count( $options['buttons'] ) > 0 ) : 
		foreach ( $options['buttons'] as $btn ) :
            if ( ! empty( $btn['cow'] ) )
	            $keys[''.wpml_string_wccm($btn['label']).''] = ''.$btn['cow'].'';
		endforeach;
    return $keys;
    endif;
}


// ================ Style the Email =======================
function wccs_custom_style_checkout_email() {
$options = get_option( 'wccs_settings' );
    if (true == ($options['checkness']['checkbox1']) )  
        echo '<h2>' . __( 'Additional information', 'woocommerce-checkout-manager' ) . '</h2>';
}


// ============== Process the checkout (if needed activate) ==================
function wccs_custom_checkout_field_process() {
global $woocommerce;
$options = get_option( 'wccs_settings' );
    if ( count( $options['buttons'] ) > 0 ) : 
		foreach ( $options['buttons'] as $btn ) :
            if ( (!$_POST[ ''.$btn['cow'].'' ] )  && (true == ($btn['checkbox']) ) )
                $woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field', 'woocommerce-checkout-manager' ) . ' ');
		endforeach;
    endif;
}

function wccs_options_validate( $input ) {
$options = get_option( 'wccs_settings' );
	
	foreach( $input['buttons'] as $i => $btn ) :

        if( function_exists( 'icl_register_string' ) ) {
            
        	icl_register_string('WooCommerce Checkout Manager', ''.$btn['label'].'', ''.$btn['label'].'');
        	icl_register_string('WooCommerce Checkout Manager', ''.$btn['placeholder'].'', ''.$btn['placeholder'].'');
        	icl_register_string('WooCommerce Checkout Manager', ''.$btn['option_a'].'', ''.$btn['option_a'].'');
        	icl_register_string('WooCommerce Checkout Manager', ''.$btn['option_b'].'', ''.$btn['option_b'].'');
        
         }

	if( empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) { 
                 		unset( $input['buttons'][$i], $btn );
	     } 

                    	if ( empty( $btn['cow'] ) && (!empty( $btn['label'] ) || !empty( $btn['placeholder'] )) ) {
				$newNum = $i + 1;
                	        $input['buttons'][$i]['cow'] = 'myfield'.$newNum.'';
                    	} 

	endforeach;
    
    
    foreach( $input['replace'] as $type => $value ) :
    if( function_exists( 'icl_register_string' ) ) {
		if(!empty($value) && $value!=''){
			icl_register_string('WooCommerce Checkout Manager', ''.$value.'', ''.$value.'');
		}
	  }
	endforeach;
    

    
	$input['buttons'] = array_values( $input['buttons'] );
return $input;
}



function wccs_custom_checkout_details( $order_id ) {
$options = get_option( 'wccs_settings' );

    if (true == ($options['checkness']['checkbox1']) )  {
        echo '<h2>' . __( 'Additional information', 'woocommerce-checkout-manager' ) . '</h2>';
    }
    
    if ( count( $options['buttons'] ) > 0 ) : 
		foreach ( $options['buttons'] as $btn ) :      
            echo '<dt>'.wpml_string_wccm($btn['label']).':</dt><dd>'.get_post_meta( $order_id->id , ''.$btn['cow'].'', true).'</dd>';
		endforeach;
    endif;
}


function display_front_wccs() {
echo '<style type="text/css">
        .wccs-field-class {
            float:left;
            width: 47%;
        }
        .wccs-field-class:nth-child(2n+2) {
            padding-right: 3.4% !important;
        }
        .add_info_wccs {
            clear: both;
        }
      </style>';
}

// =============== Make compatible with WooCommerce Delivery Notes ===========
function wccm_woocommerce_delivery_notes_compat( $fields, $order ) {
$options = get_option( 'wccs_settings' );
$new_fields = array();

    if ( count( $options['buttons'] ) > 0 ) : 
		foreach ( $options['buttons'] as $btn ) :
					
            if( get_post_meta( $order->id, ''.$btn['cow'].'', true ) ) {
                $new_fields[''.$btn['cow'].''] = array( 
                    'label' => ''.wpml_string_wccm($btn['label']).'',
                    'value' => get_post_meta( $order->id, ''.$btn['cow'].'', true )
                );
            }
		endforeach;
    endif;
 
return array_merge( $fields, $new_fields );
}

// ================ Add field names to WPML String Translation ===============
function wpml_string_wccm($input) {
    if (function_exists( 'icl_t' )) {
        return icl_t('WooCommerce Checkout Manager', ''.$input.'', ''.$input.'');
    } else {
        return $input;
    }
}