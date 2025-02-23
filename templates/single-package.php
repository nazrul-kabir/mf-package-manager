<?php
// templates/single-package.php

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            $id = get_the_ID();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php echo esc_html(get_post_meta($id, '_package_name', true)); ?></h1>
                </header>

                <div class="entry-content">
                    <div class="package-details">
                        <p class="package-description"><?php echo esc_html(get_post_meta($id, '_package_description', true)); ?></p>
                        <p class="package-speed">
                            <strong><?php _e('Download Speed:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_download_speed', true)); ?>
                        </p>
                        <p class="package-speed">
                            <strong><?php _e('Upload Speed:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_upload_speed', true)); ?>
                        </p>
                        <p class="package-extra">
                            <strong><?php _e('Extra Free:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_extra_free', true)); ?>
                        </p>
                        <p class="package-provider">
                            <strong><?php _e('Provider:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_provider', true)); ?>
                        </p>
                        <p class="package-type">
                            <strong><?php _e('Type:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_type', true)); ?>
                        </p>
                        <p class="package-price">
                            <strong><?php _e('Price:', 'package-manager'); ?></strong>
                            <?php echo esc_html(get_post_meta($id, '_package_price', true)); ?>
                        </p>
                        <div class="package-postcodes">
                            <strong><?php _e('Available Postcodes:', 'package-manager'); ?></strong>
                            <ul>
                                <?php
                                $postcode_ranges = $this->get_postcode_ranges($id);
                                foreach ($postcode_ranges as $range) {
                                    echo '<li>' . esc_html($range) . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();
