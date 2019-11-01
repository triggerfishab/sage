<?php
namespace App;

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter('mce_buttons_2', function ($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
});

add_filter('tiny_mce_before_init', function ($settings) {
    $heading = esc_html__('Heading', 'sage');
    $heading_selector = 'h1, h2, h3, h4, h5, p';
    $style_formats = [
        [
            'title' => esc_html__('Headings', 'sage'),
            'items' => [
                [
                    'title' => sprintf('%s 1', $heading),
                    'selector' => $heading_selector,
                    'classes' => 'h1',
                ],
                [
                    'title' => sprintf('%s 2', $heading),
                    'selector' => $heading_selector,
                    'classes' => 'h2',
                ],
                [
                    'title' => sprintf('%s 3', $heading),
                    'selector' => $heading_selector,
                    'classes' => 'h3',
                ],
                [
                    'title' => sprintf('%s 4', $heading),
                    'selector' => $heading_selector,
                    'classes' => 'h4',
                ],
                [
                    'title' => sprintf('%s 5', $heading),
                    'selector' => $heading_selector,
                    'classes' => 'h5',
                ],
            ],
        ],
        [
            'title' => esc_html__('Text Styles', 'sage'),
            'items' => [
                [
                    'title' => 'Preamble',
                    'selector' => 'p',
                    'classes' => 'preamble',
                ],
            ],
        ],
        [
            'title' => esc_html__('Color', 'sage'),
            'items' => [
                [
                    'title' => esc_html__('Primary', 'sage'),
                    'selector' => 'h1, h2, h3, h4, h5',
                    'classes' => 'text-primary',
                ],
                [
                    'title' => esc_html__('Secondary', 'sage'),
                    'selector' => 'h1, h2, h3, h4, h5',
                    'classes' => 'text-secondary',
                ],
            ],
        ],
        [
            'title' => esc_html__('Buttons', 'sage'),
            'items' => [
                [
                    'title' => esc_html__('Primary', 'sage'),
                    'selector' => 'a',
                    'attributes' => [ 'class' => 'btn' ],
                ],
            ],
        ],
    ];

    $block_formats = [
        'p' => esc_html__('Paragraph', 'sage'),
        'h1' => sprintf('%s 1', $heading),
        'h2' => sprintf('%s 2', $heading),
        'h3' => sprintf('%s 3', $heading),
        'h4' => sprintf('%s 4', $heading),
        'h5' => sprintf('%s 5', $heading),
    ];

    $block_formats = array_map(function ($label, $element) {
        return sprintf('%s=%s', $label, $element);
    }, array_values($block_formats), array_keys($block_formats));

    $settings['block_formats'] = implode(';', $block_formats);
    $settings['style_formats'] = json_encode($style_formats);
    $settings['body_class'] .= ' entry-content';

    return $settings;
});

/**
 * Remove tinymce buttons row 1
 */
add_filter('mce_buttons', function ($buttons) {
    $allowed_buttons = [
        'formatselect',
        'bold',
        'italic',
        'bullist',
        'numlist',
        'blockquote',
        'alignleft',
        'aligncenter',
        'alignright',
        'link',
        'unlink',
        'fullscreen',
        'wp_adv',
    ];

    $buttons = array_filter($buttons, function ($value) use ($allowed_buttons) {
        return in_array($value, $allowed_buttons);
    });

    return $buttons;
});

/**
 * Remove tinymce buttons row 2
 */
add_filter('mce_buttons_2', function ($buttons) {
    $allowed_buttons = [
        'styleselect',
        'hr',
        'underline',
        'pastetext',
        'removeformat',
        'table',
    ];

    $buttons = array_filter($buttons, function ($value) use ($allowed_buttons) {
        return in_array($value, $allowed_buttons);
    });

    return $buttons;
});
