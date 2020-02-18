<?php
add_action('init', 'sw_cpost_itineraries');

function sw_cpost_itineraries(){

register_post_type( 'sw-itineraries',
    array(
            'labels' => array(
                    'name' => __( 'itineraries' ),
                    'singular_name' => __( 'itinerary' )
            ),
    'public' => true,
    'has_archive' => true,
    'show_in_menu' => false
    )
);

remove_post_type_support( 'sw-itineraries', 'editor' );

}

