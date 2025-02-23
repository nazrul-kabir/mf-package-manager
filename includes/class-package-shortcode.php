<?php

class Package_Shortcode {
    public function init() {
        add_shortcode('package_slider', array($this, 'package_slider_shortcode'));
    }

    public function package_slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'type' => '', // 'residential' or 'business'
            'postcode' => '', // Filter by postcode
        ), $atts, 'package_slider');

        $args = array(
            'post_type' => 'package',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        $meta_query = array();

        if (!empty($atts['type'])) {
            $meta_query[] = array(
                'key' => '_package_type',
                'value' => $atts['type'],
                'compare' => '=',
            );
        }

        if (!empty($atts['postcode'])) {
            $meta_query[] = array(
                'relation' => 'OR',
                array(
                    'key' => '_package_postcodes',
                    'value' => 'all',
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => '_package_postcodes',
                    'value' => '"' . $atts['postcode'] . '"',
                    'compare' => 'LIKE',
                ),
            );
        }

        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        $packages = new WP_Query($args);

        ob_start();

        if ($packages->have_posts()) :
            echo '<div class="package-slider">'; // This class will be initialized with Slick
            while ($packages->have_posts()) : $packages->the_post();
                $id = get_the_ID();
                ?>
                <div class="package-slide">
                    <div class="package-content">
                        <h3 class="package-title"><?php echo esc_html(get_post_meta($id, '_package_name', true)); ?></h3>
                        <p class="package-description"><?php echo esc_html(get_post_meta($id, '_package_description', true)); ?></p>
                        <div class="package-details">
                            <p class="package-speed">
                                <span class="download"><?php echo esc_html(get_post_meta($id, '_package_download_speed', true)); ?></span>
                                <span class="upload"><?php echo esc_html(get_post_meta($id, '_package_upload_speed', true)); ?></span>
                            </p>
                            <p class="package-extra"><?php echo esc_html(get_post_meta($id, '_package_extra_free', true)); ?></p>
                            <p class="package-provider"><?php echo esc_html(get_post_meta($id, '_package_provider', true)); ?></p>
                            <p class="package-type"><?php echo esc_html(get_post_meta($id, '_package_type', true)); ?></p>
                            <p class="package-price"><?php echo esc_html(get_post_meta($id, '_package_price', true)); ?></p>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="package-link"><?php _e('View Details', 'package-manager'); ?></a>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
        else :
            _e('No packages found.', 'package-manager');
        endif;

        wp_reset_postdata();

        return ob_get_clean();
    }
}
