<?php

//Include the form.js and form.css
    function SELLMYHOME_plugin_scripts() {
        global $glb_smh_pluginuri;
        //if ( is_singular() ) {
           wp_enqueue_script( 'sellmyhome_formjs', $glb_smh_pluginuri . '/form/form.js', array ( 'jquery' ), 1.1, true);
           wp_enqueue_script( 'sellmyhome_validatejsrules', $glb_smh_pluginuri . '/form/validate.js', array ( 'jquery' ), 1.1, true);
           wp_enqueue_script( 'jquery_validation','http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', array ( 'jquery' ), 1.1, true);
           wp_enqueue_style( 'sellmyhome_formcss', $glb_smh_pluginuri . '/form/form.css', array (), 1.1, false);
           //wp_enqueue_style( 'sellmyhome_formcss_reset', $glb_smh_pluginuri . '/form/form-reset.css', array (), 1.1, false);
           wp_enqueue_style( 'font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), 1.1, false);
           wp_enqueue_script( 'jqueryui', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js", array ( 'jquery' ), 1.1, true);
        //}
    }
    add_action( 'wp_enqueue_scripts', 'SELLMYHOME_plugin_scripts' );