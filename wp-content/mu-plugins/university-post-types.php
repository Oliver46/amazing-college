<?php
//Event Post Type
function university_post_types(){
    
    //Register Campuses Post Type
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'supports' => array ('title', 'editor', 'excerpt'),
        'rewrite' => array ('slug' => 'campuses'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus' 
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));
    //Register Professor Post Type
    register_post_type('professor', array(
        'show_in_rest' => true, //show in rest api
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        //'rewrite' => array('slug' => 'professors'), //we don't need the slug
        //'has_archive' => true, //Display all Professors
        'public' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'labels' => array (
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor' 
        )
    ));

    //Regiter Event Post Type
    register_post_type('event', array(
        'capability_type' => 'event', // Works with Plugin Member by MemberPress
        'map_meta_cap' => true, // Works with Plugin Member by MemberPress
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true, //make it visible to editor, and visitors
        'menu_icon' => 'dashicons-calendar', // icon
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        )
    ));
    //Regiter Program Post Type
    register_post_type('program', array(
    'supports' => array('title', /*'editor'*/),
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true, //make it visible to editor, and visitors
        'menu_icon' => 'dashicons-awards', // icon
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Programs',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        )
    ));
    //Regiter My Note Post Type
    register_post_type('note', array(
        'show_in_rest' => true, // rest api | default url/wp-json/wp/v2/note
        'capability_type' => 'note', // grant permission that applys to this blog type
        'map_meta_cap' => true, // Enforce permission
        'support' => array('title', 'editor'),
        'public' => false, //private  and specific for each user account | do not show on search results | will not display in the admin dashboard
        'show_ui' => true, //show in the admin dashboard
        'labels' => array(
           'name' => 'Notes',
           'add_new_item' => 'Add New Note',
           'edit_item' => 'Edit Note',
           'all_items' => 'All Notes',
           'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));

    //Regiter My Like (Heart) Post Type | Each user can like a professor once
    register_post_type('like', array(
        //'show_in_rest' => true, // rest api | default url/wp-json/wp/v2/note
        //'capability_type' => 'note', // grant permission that applys to this blog type
        //'map_meta_cap' => true, // Enforce permission
        'support' => array('title'),
        'public' => false, //private  and specific for each user account | do not show on search results | will not display in the admin dashboard
        'show_ui' => true, //show in the admin dashboard
        'labels' => array(
           'name' => 'Likes',
           'add_new_item' => 'Add New Like',
           'edit_item' => 'Edit Like',
           'all_items' => 'All Likes',
           'singular_name' => 'Like'
        ),
        'menu_icon' => 'dashicons-heart'
    ));
}
//init -> fires after WordPress has finished loading but before any headers are sent.
add_action('init', 'university_post_types');