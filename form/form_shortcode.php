<?php

//Create the form shortcode
    function sellmyhome_form_sc( $atts ) {
        $a = shortcode_atts( array(
            'emails' => '',
            'background-color' => '',
            'background-opacity' => '',
            'social' => ""
        ), $atts );
        sellmyhome_form( $a['emails'], $a['social'] ); 
    }
    add_shortcode( 'sellmyhome_form', 'sellmyhome_form_sc' );

    function sellmyhome_button_sc( $atts ){
        $a = shortcode_atts( array(
            'text' => 'Get Started Today',
            'href' => "#msform"
        ), $atts );

        ob_start(); 
        ?>
        	<a href='<?php echo $a["href"] ?>'><button class="form-nav-to"> <?php  echo $a["text"]  ?></button></a>
        <?php
        return ob_get_clean();
    }
     add_shortcode( 'sellmyhome_navigate', 'sellmyhome_button_sc' );