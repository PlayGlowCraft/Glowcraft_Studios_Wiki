<?php

/**
 *
 * @package News Zone */
function newszone_customize_register($wp_customize) {

$newses_default = newszone_get_default_theme_options();



//section title
$wp_customize->add_setting('editor_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new newses_Section_Title(
        $wp_customize,
        'editor_section_title',
        array(
            'label'             => esc_html__( 'Editor Section ', 'news-zone' ),
            'section'           => 'frontpage_main_banner_section_settings',
            'priority'          => 90,
        )
    )
);

// Setting - drop down category for slider.
$wp_customize->add_setting('select_editor_news_category',
    array(
        'default' => $newses_default['select_editor_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new Newses_Dropdown_Taxonomies_Control($wp_customize, 'select_editor_news_category',
    array(
        'label' => esc_html__('Category', 'news-zone'),
        'description' => esc_html__('Select category for Editor 2 Post', 'news-zone'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 91,
    )));



}
add_action('customize_register', 'newszone_customize_register');