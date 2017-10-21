<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Sell My Home Lead Capture
 * Plugin URI:        playfrey.tech
 * Description:       This plugin creates a shortcode that adds a form to the site, as well as a means of collecting leads. Using the shortcode "[sellmyhome_form]" to insert the form. 
 * Version:           v1.1.12
 * Author:            Devon Godfrey
 * Author URI:        http://playfrey.tech
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/devonhg/sellmyhomeleads

	*IMPORTANT*
	do a "find/replace" accross the directory for "SELLMYHOME" and replace
	with your plugin name. 

	Plugin slug: SELLMYHOME

*/

//Set the global plugin url
    $glb_smh_pluginuri = plugin_dir_url( __FILE__ );

//Include the core class of the post type api and the rest of the plugin files.
    include_once('pt-api/class-core.php');
    include_once('form/form_functions.php');
    include_once('form/form.php');
    include_once("form/form_shortcode.php");
    include_once("form/form_includes.php");
    register_activation_hook( __FILE__, 'SELLMYHOME_ptapi_activate' );

//Create Post-Type Object
    $pt_leads = new SELLMYHOME_post_type( "Leads", "Lead", "Leads that fill out the lead form.", "", true, null, false ); 

    $pt_leads->reg_tax("Lead Types", "Lead Type");
    $pt_leads->reg_tax("Property Values", "Property Value");
    $pt_leads->reg_tax("Property Types", "Property Type");
    $pt_leads->reg_tax("Purchase Times", "Purchase Time");
    $pt_leads->reg_tax("Mortgage Options", "Mortgage Option");

    $pt_leads->reg_tax("Cities", "City", false);

    $pt_leads->reg_meta("Address", "The address of the lead.");
    $pt_leads->reg_meta("Phone", "The leads contact number.");
    $pt_leads->reg_meta("Email", "The leads email.");

//Clear out all frontend content
    $pt_leads->remove_hook_single();
    $pt_leads->remove_hook_archive();
    $pt_leads->remove_hook_sc();

//Set up default taxonomy values
    function SELLMYHOME_init_categories(){

        //Lead Type Defaults
            $cust_tax = "tax_lead_type_leads";
            SELLMYHOME_add_category( "buy", $cust_tax ); 
            SELLMYHOME_add_category( "sell", $cust_tax ); 
            SELLMYHOME_add_category( "both", $cust_tax );

        //Property Values Defaults  
            $cust_tax = "tax_property_value_leads";
            SELLMYHOME_add_category( "(10)Under $100K", $cust_tax ); 
            SELLMYHOME_add_category( "(20)$100K to $200K", $cust_tax ); 
            SELLMYHOME_add_category( "(30)$200K to $400K", $cust_tax ); 
            SELLMYHOME_add_category( "(40)$400K to $600K", $cust_tax ); 
            SELLMYHOME_add_category( "(50)$600K to $1M", $cust_tax ); 
            SELLMYHOME_add_category( "(60)1M Plus", $cust_tax ); 
        
        //Property Type Defaults
            $cust_tax = "tax_property_type_leads";
            SELLMYHOME_add_category( "(10)Single Family", $cust_tax ); 
            SELLMYHOME_add_category( "(20)Condo", $cust_tax ); 
            SELLMYHOME_add_category( "(30)Land/Lot", $cust_tax );
            SELLMYHOME_add_category( "(40)Commercial", $cust_tax );

        //Property Type Defaults
            $cust_tax = "tax_purchase_time_leads";
            SELLMYHOME_add_category( "(10)Immediately", $cust_tax ); 
            SELLMYHOME_add_category( "(20)1 Month or Less", $cust_tax ); 
            SELLMYHOME_add_category( "(30)2 – 3 Months", $cust_tax );
            SELLMYHOME_add_category( "(40)3 – 6 Months", $cust_tax );
            SELLMYHOME_add_category( "(50)6 – 9 Months", $cust_tax );
            SELLMYHOME_add_category( "(60)9 Months or Later", $cust_tax );

        //Property Type Defaults
            $cust_tax = "tax_mortgage_option_leads";
            SELLMYHOME_add_category( "(10)All Cash", $cust_tax ); 
            SELLMYHOME_add_category( "(20)Haven’t Applied", $cust_tax ); 
            SELLMYHOME_add_category( "(30)Pre-Qualified", $cust_tax );
            SELLMYHOME_add_category( "(40)Pre-Approved", $cust_tax );
            SELLMYHOME_add_category( "(50)Not Sure", $cust_tax );

    }add_action('admin_init','SELLMYHOME_init_categories');

    /*    
    function SELLMYHOME_post_status_new( $new_status, $old_status, $post ) { 
        if ( $post->post_type == 'pt_leads' && $new_status == 'publish' && $old_status  != $new_status ) {
            $post->post_status = 'private';
            wp_update_post( $post );
        }
    }add_action( 'transition_post_status', 'SELLMYHOME_post_status_new', 10, 3 );
    */