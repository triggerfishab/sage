<?php

add_filter('pre_option_rg_gforms_disable_css', function () {
    return '1';
});

add_filter( 'pre_option_rg_gforms_enable_html5', function () {
    return '1';
});

add_filter('gform_init_scripts_footer', '__return_true');
