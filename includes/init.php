<?php

// Register the Custom Leads Post Type
function register_cpt_leads() {
 
    $labels = array(
        'name' => _x( 'Contact Form 7 – Save My Leads', 'leads' ),
        'singular_name' => _x( 'Lead', 'leads' ),
        'add_new' => _x( 'Add New', 'leads' ),
        'add_new_item' => _x( 'Add New Lead', 'leads' ),
        'edit_item' => _x( 'Edit Lead', 'leads' ),
        'new_item' => _x( 'New Lead', 'leads' ),
        'view_item' => _x( 'View Lead', 'leads' ),
        'search_items' => _x( 'Search Leads', 'leads' ),
        'not_found' => _x( 'No leads found', 'leads' ),
        'not_found_in_trash' => _x( 'No leads found in Trash', 'leads' ),
        'parent_item_colon' => _x( 'Parent Lead:', 'leads' ),
        'menu_name' => _x( 'Leads CF7', 'leads' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Leads that created by "Contact Form 7" submit',
        'supports' => array( 'title' ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 50,
        'menu_icon' => 'dashicons-id-alt',
        //        'taxonomies' => array( 'genres' ,'post_tag'),
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
        ),
        'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts
    );
    register_post_type( 'leads', $args );
//    register_taxonomy_for_object_type( 'post_tag', 'leads' );
}
 
add_action( 'init', 'register_cpt_leads' );
