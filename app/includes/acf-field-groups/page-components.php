<?php
$fields = [
    [
        'key' => 'field_page_components',
        'name' => 'page_components',
        'label' => esc_html__('Page Components', 'sage'),
        'button_label' => esc_html__('Add Component', 'sage'),
        'type' => 'flexible_content',
        'layouts' => [],
    ],
];

// Use opendir() instead of glob() because of the huge performance gain.
$handle = opendir(__DIR__ . '/page-components/');

if ($handle) {
    while (false !== ($file_name = readdir($handle))) {
        if ('.' === $file_name || '..' === $file_name || '.gitkeep' === $file_name) {
            continue;
        }

        $component_config = include_once __DIR__ . '/page-components/' . $file_name;

        if (empty($component_config)) {
            continue;
        }

        $fields[0]['layouts'][] = $component_config;
    }

    if (isset($fields[0]['layouts'])) {
        asort($fields[0]['layouts']);
    }

    closedir($handle);
}

acf_add_local_field_group([
    'key' => 'group_page_components',
    'title' => esc_html__('Page Components', 'sage'),
    'fields' => $fields,
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'page',
            ],
        ],
    ],
    'style' => 'seamless',
    'hide_on_screen' => [ 'the_content' ],
]);
