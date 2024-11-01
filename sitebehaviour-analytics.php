<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/*
Plugin Name: SiteBehaviour Analytics
Description: Adds a custom <script> tag to the <head> section of the website for analytics tracking.
Version: 1.2
Author: SiteBehaviour
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Function to add settings page
function sitebehaviour_analytics_add_settings_page() {
    add_options_page(
        'Sitebehaviour Analytics Settings',
        'Sitebehaviour Analytics',
        'manage_options',
        'sitebehaviour-analytics-settings',
        'sitebehaviour_analytics_render_settings'
    );
}
add_action('admin_menu', 'sitebehaviour_analytics_add_settings_page');

// Function to render settings page
function sitebehaviour_analytics_render_settings() {
    ?>
    <div class="wrap">
        <h1>Sitebehaviour Analytics Settings</h1>
        <p>Welcome to Sitebehaviour Analytics! Follow the instructions below to set up analytics tracking on your site:</p>
        <ol>
            <li>Step 1: <a href="https://web.sitebehaviour.com/" target="_blank">Sign up for a Sitebehaviour Analytics account</a> and obtain your tracking code.</li>
            <li>Step 2: Paste your tracking secret into the field below.</li>
            <li>Step 3: Save your settings and start tracking visitors to your site!</li>
        </ol>

        <form method="post" action="options.php">
            <?php 
            settings_fields('sitebehaviour_analytics_settings_group'); 
            do_settings_sections('sitebehaviour-analytics-settings'); 
            submit_button('Save Settings'); 
            ?>
        </form>
    </div>
    <?php
}

// Function to initialize plugin settings
function sitebehaviour_analytics_initialize_settings() {
    add_settings_section(
        'sitebehaviour_analytics_settings_section',
        'Sitebehaviour Analytics Tracking Code',
        'sitebehaviour_analytics_settings_section_callback',
        'sitebehaviour-analytics-settings'
    );

    add_settings_field(
        'sitebehaviour_analytics_tracking_code',
        'Tracking Secret',
        'sitebehaviour_analytics_tracking_code_callback',
        'sitebehaviour-analytics-settings',
        'sitebehaviour_analytics_settings_section'
    );

    register_setting(
        'sitebehaviour_analytics_settings_group',
        'sitebehaviour_analytics_tracking_code'
    );
}
add_action('admin_init', 'sitebehaviour_analytics_initialize_settings');

// Section content callback
function sitebehaviour_analytics_settings_section_callback() {
    echo '<p>Paste your Sitebehaviour Analytics tracking secret below. You need an account to obtain a tracking secret.</p>';
}

// Tracking code input field callback
function sitebehaviour_analytics_tracking_code_callback() {
    $tracking_code = get_option('sitebehaviour_analytics_tracking_code');
    echo '<input type="text" name="sitebehaviour_analytics_tracking_code" value="' . esc_attr($tracking_code) . '" size="50">';
}

// Function to output the tracking script in the <head>
function sitebehaviour_analytics_output_script() {
    $tracking_code = get_option('sitebehaviour_analytics_tracking_code');

    if ($tracking_code) {
        ?>
        <script type="text/javascript">
            (function() {
                var sbSiteSecret = "<?php echo esc_js($tracking_code); ?>";
                window.sitebehaviourTrackingSecret = sbSiteSecret;
                var scriptElement = document.createElement('script');
                scriptElement.async = true;
                scriptElement.id = "site-behaviour-script-v2";
                scriptElement.src = "https://sitebehaviour-cdn.fra1.cdn.digitaloceanspaces.com/index.min.js?sitebehaviour-secret=" + sbSiteSecret;
                document.head.appendChild(scriptElement); 
            })()
        </script>
        <?php
    }
}
add_action('wp_head', 'sitebehaviour_analytics_output_script');
