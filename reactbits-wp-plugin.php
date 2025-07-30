<?php
/**
 * Plugin Name:     ReactBits WP Plugin
 * Description:     Incorpora componentes ReactBits via shortcode [reactbits].
 * Version:         1.0.0
 * Author:          Nubem Júnior
 *
 * GitHub Plugin URI: nuux-tec/reactbits1
 * Requires at least: 5.0
 * Tested up to:       6.4
 * License:           MIT
 */


if ( ! defined('ABSPATH') ) exit;

// 1) Registrar resources já existente...
function reactbits_registrar_recursos() {
    wp_register_script(
        'reactbits-script',
        plugin_dir_url(__FILE__) . 'build/index.js',
        array('wp-element'),
        '1.0.0',
        true
    );
    wp_register_style(
        'reactbits-style',
        plugin_dir_url(__FILE__) . 'build/index.css',
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'reactbits_registrar_recursos');

// 2) Shortcode [reactbits]
function reactbits_renderizar_componente($atts = array()) {
    $atts = shortcode_atts(array(
        'component' => '',
        'items'     => '',
    ), $atts, 'reactbits');

    $componente = sanitize_text_field($atts['component']);
    $items_raw  = sanitize_text_field($atts['items']);

    if ( empty($componente) ) {
        return '<!-- ReactBits: componente não informado -->';
    }

    wp_enqueue_script('reactbits-script');
    wp_enqueue_style('reactbits-style');

    // monta o container com os atributos necessários
    $html  = '<div class="reactbits-componente"';
    $html .= ' data-component="' . esc_attr($componente) . '"';
    if ( $items_raw !== '' ) {
        $html .= ' data-items="' . esc_attr($items_raw) . '"';
    }
    $html .= '></div>';

    return $html;
}
add_shortcode('reactbits', 'reactbits_renderizar_componente');

// 3) Incluir o Plugin Update Checker **(linha nova)**
require __DIR__ . '/lib/plugin-update-checker/plugin-update-checker.php';

// buildUpdateChecker( repositório, arquivo principal, slug )
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/nuux-tec/reactbits1/',
    __FILE__,
    'reactbits-wp-plugin'
);
// opcional: usar branch “main” em vez de tags
$updateChecker->setBranch('main');

// 4) Adicionar página de verificação manual no menu de Plugins
add_action('admin_menu', 'reactbits_adicionar_pagina_atualizacao');
function reactbits_adicionar_pagina_atualizacao() {
    add_plugins_page(
        'Atualizações ReactBits',    // título da página
        'Atualizações ReactBits',    // rótulo no menu
        'manage_options',            // capability
        'reactbits-atualizacoes',    // slug do menu
        'reactbits_pagina_atualizacao' // callback
    );
}

function reactbits_pagina_atualizacao() {
    global $updateChecker;

    echo '<div class="wrap">';
    echo '<h1>Atualizações ReactBits</h1>';

    if ( isset($_POST['reactbits_check_update']) ) {
        // força a verificação de novas versões no GitHub
        $updateChecker->checkForUpdates();
        // limpa transientes para exibir nova notificação no Plugins
        delete_site_transient('update_plugins');
        echo '<div class="updated"><p>Verificação concluída! Confira a lista de plugins para atualizar.</p></div>';
    }

    // formulário com botão para disparar a verificação
    echo '<form method="post">';
    echo '<p><input type="submit" name="reactbits_check_update" ';
    echo 'class="button button-secondary" value="Procurar Atualizações" /></p>';
    echo '</form>';
    echo '</div>';
}
