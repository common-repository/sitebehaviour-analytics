<?php
// If this file is called directly, abort.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Option name that stores tracking code
$option_name = 'sitebehaviour_analytics_tracking_code';

// Delete the option from the database
delete_option($option_name);

// For multisite
delete_site_option($option_name);
