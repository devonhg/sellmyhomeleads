<?php

//Create the form shortcode
    function sellmyhome_form_sc( $atts ) {
        $a = shortcode_atts( array(
            'emails' => ''
        ), $atts );
        sellmyhome_form( $a['emails'] ); 
    }
    add_shortcode( 'sellmyhome_form', 'sellmyhome_form_sc' );