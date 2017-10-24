<?php

//Create the form shortcode
    function sellmyhome_form_sc( $atts ) {
        $a = shortcode_atts( array(
            'emails' => '',
            'social' => "",
            'email_body' => "We have received your request and are working on matching you with a top local agent who can best meet your needs. The agent will be contacting you soon. If you have any questions, please reach out to us at help@sellmyhomemi.com and a team member will assist you.",
            'submit_message' => "Thank you, we will be in touch shortly."
        ), $atts );
        sellmyhome_form( $a['emails'], $a['social'], $a['email_body'], $a['submit_message'] ); 
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