<?php

//Create the form shortcode
    function sellmyhome_form_sc( $atts ) {
        $a = shortcode_atts( array(
            'emails' => '',
            'background-color' => '',
            'background-opacity' => ''
        ), $atts );
        sellmyhome_form( $a['emails'] ); 
    }
    add_shortcode( 'sellmyhome_form', 'sellmyhome_form_sc' );

    function sellmyhome_button_sc( $atts ){
        $a = shortcode_atts( array(
            'text' => 'Get Started Today'
        ), $atts );

        ob_start(); 
        ?>
        	<button class="form-nav-to"> <?php echo $a["text"] ?></button>
        <?php
        return ob_get_clean();
    }
     add_shortcode( 'sellmyhome_navigate', 'sellmyhome_button_sc' );