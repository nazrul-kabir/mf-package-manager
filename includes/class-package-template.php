<?php

class Package_Template {
    public function init() {
        add_filter('single_template', array($this, 'load_package_template'));
    }

    public function load_package_template($single_template) {
        global $post;

        if ($post->post_type == 'package') {
            $single_template = PACKAGE_MANAGER_PLUGIN_DIR . 'templates/single-package.php';
        }

        return $single_template;
    }
}
