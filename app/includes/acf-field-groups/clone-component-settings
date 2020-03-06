<?php

$fields = [
    [
        'key' => 'field_clone_component_settings_component_name',
        'name' => 'component_name',
        'type' => 'text',
        'label' => esc_html__('Name', 'sage'),
        'instructions' => esc_html__('Name this component. Only for internal use.', 'sage'),
        'wrapper' => ['width' => 50],
    ],
    [
        'key' => 'field_clone_component_settings_component_anchor_name',
        'name' => 'component_anchor_name',
        'type' => 'text',
        'prepend' => '#',
        'label' => esc_html__('Anchor Name', 'sage'),
        'instructions' => esc_html__('Name this component for anchor linking.', 'sage'),
        'wrapper' => ['width' => 50],
    ],
];

acf_add_local_field_group([
    'key' => 'group_clone_component_settings',
    'title' => esc_html__('Component Component Settings', 'sage'),
    'fields' => $fields,
    'location' => [],
    'style' => 'seamless',
    'active' => 0,
]);
