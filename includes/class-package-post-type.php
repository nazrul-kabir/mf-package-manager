<?php

class Package_Post_Type {
    public function init() {
        add_action('init', array($this, 'register_post_type'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => _x('Packages', 'post type general name', 'package-manager'),
            'singular_name'      => _x('Package', 'post type singular name', 'package-manager'),
            'menu_name'          => _x('Packages', 'admin menu', 'package-manager'),
            'name_admin_bar'     => _x('Package', 'add new on admin bar', 'package-manager'),
            'add_new'            => _x('Add New', 'package', 'package-manager'),
            'add_new_item'       => __('Add New Package', 'package-manager'),
            'new_item'           => __('New Package', 'package-manager'),
            'edit_item'          => __('Edit Package', 'package-manager'),
            'view_item'          => __('View Package', 'package-manager'),
            'all_items'          => __('All Packages', 'package-manager'),
            'search_items'       => __('Search Packages', 'package-manager'),
            'parent_item_colon'  => __('Parent Packages:', 'package-manager'),
            'not_found'          => __('No packages found.', 'package-manager'),
            'not_found_in_trash' => __('No packages found in Trash.', 'package-manager')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'package'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon'          => 'dashicons-cart'
        );

        register_post_type('package', $args);
    }
}
