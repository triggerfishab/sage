<?php

namespace App;

/**
 * Add depth class to menu items.
 */
add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {
    $classes[] = 'menu-item-level-' . $depth;

    return $classes;
}, 10, 4);
