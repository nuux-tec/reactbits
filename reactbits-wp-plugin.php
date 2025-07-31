<?php
/**
 * Plugin Name:     ReactBits WP Plugin
 * Description:     Incorpora componentes ReactBits.dev via shortcode [reactbits].
 * Version:         1.0.0
 * Author:          Nubem Júnior
 *
 * GitHub Plugin URI: nuux-tec/reactbits1
 * License:         MIT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Saída direta se acessado fora do WordPress
}

/**
 * Enfileira os assets compilados (JS + CSS) do plugin.
 */
function reactbits_registrar_recursos() {
    $url_base = plugin_dir_url( __FILE__ );

    // Bundle JavaScript gerado por `npm run build`
    wp_register_script(
        'reactbits-wp-plugin-js',
        $url_base . 'build/index.js',
        array( 'wp-element' ),  // Usa o React/ReactDOM nativo do WordPress
        '1.0.0',
        true
    );

    // CSS compilado dos componentes
    wp_register_style(
        'reactbits-wp-plugin-css',
        $url_base . 'build/index.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action( 'wp_enqueue_scripts', 'reactbits_registrar_recursos' );

/**
 * Callback do shortcode [reactbits].
 * Aceita atributos:
 * - component (string): nome do componente ReactBits a ser renderizado.
 * - items     (string opcional): lista de itens separada por vírgula para componentes que suportem props.items.
 *
 * Exemplo de uso no conteúdo:
 * [reactbits component="AnimatedList" items="Maçã,Banana,Laranja"]
 */
function reactbits_renderizar_componente( $atts = array() ) {
    $args = shortcode_atts( array(
        'component' => '',
        'items'     => '',
    ), $atts, 'reactbits' );

    $componente = sanitize_key( $args['component'] );
    $items_raw  = sanitize_text_field( $args['items'] );

    if ( empty( $componente ) ) {
        return '<!-- ReactBits: atributo component não informado -->';
    }

    // Enfileira assets somente quando o shortcode é usado
    wp_enqueue_script( 'reactbits-wp-plugin-js' );
    wp_enqueue_style( 'reactbits-wp-plugin-css' );

    // Monta o container que o JavaScript irá detectar e montar o React
    $attrs = sprintf(
        ' data-component="%s"%s',
        esc_attr( $componente ),
        ( $items_raw !== '' )
            ? ' data-items="' . esc_attr( $items_raw ) . '"'
            : ''
    );

    return '<div class="reactbits-componente"' . $attrs . '></div>';
}
add_shortcode( 'reactbits', 'reactbits_renderizar_componente' );
