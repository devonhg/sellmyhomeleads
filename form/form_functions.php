<?php

function SELLMYHOME_clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

//This function maintains thedefault categories
function SELLMYHOME_add_category( $cat_name, $taxonomy ){
    //Check if term exists before attempting to create
    if( !term_exists( $cat_name, $taxonomy ) ){
        $my_cat = array('cat_name' => $cat_name, 
                        'category_description' => 'Default, Permanent Category', 
                        'category_nicename' => SELLMYHOME_clean($cat_name), 
                        'category_parent' => '',
                        'taxonomy' => $taxonomy);

        // Create the category
        wp_insert_category($my_cat);  
    }      
} 
