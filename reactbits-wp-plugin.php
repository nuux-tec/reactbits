<?php
/**
 * Plugin Name: ReactBits WP Plugin
 * Description: Incorpora componentes ReactBits.dev via shortcode [reactbits].
 * Version: 1.0.0
 * Author: Nubem Júnior
 * Text Domain: reactbits-wp-plugin
 */

// Enfileira os assets (JS e CSS) compilados
function reactbits_enqueue_assets() {
    $script_url = plugin_dir_url(__FILE__) . 'build/index.js';
    $style_url  = plugin_dir_url(__FILE__) . 'build/index.css';
    wp_enqueue_script('reactbits-wp-plugin-js', $script_url, array('wp-element'), '1.0.0', true);
    wp_enqueue_style('reactbits-wp-plugin-css', $style_url, array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'reactbits_enqueue_assets');

// Registra o shortcode [reactbits component="NomeDoComponente"]
function reactbits_render_shortcode($atts) {
    $atts = shortcode_atts(array(
        'component' => ''
    ), $atts, 'reactbits');
    $component = sanitize_text_field($atts['component']);
    if (empty($component)) {
        return '';
    }
    $container_id = 'reactbits-' . uniqid();
    return '<div id="' . esc_attr($container_id) . 
           '" class="reactbits-container" data-component="' . esc_attr($component) . '"></div>';
}
add_shortcode('reactbits', 'reactbits_render_shortcode');