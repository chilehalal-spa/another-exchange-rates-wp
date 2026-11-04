<?php
/**
 * Plugin Name: Another Exchange Rates
 * Description: Widget de indicadores económicos (UF, Dólar, Euro) usando la API oficial de Chile.
 * Version: 1.1.0
 * Author: Zumito
 * Text Domain: another-exchange-rates
 * Requires at least: 5.8
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://github.com/chilehalal-spa/another-exchange-rates-wp
 */

if (!defined('ABSPATH')) exit;

// Actualizaciones desde las releases de GitHub
require_once __DIR__ . '/includes/class-chilehalal-github-updater.php';
Chilehalal_GitHub_Updater_V1::register( __FILE__, 'chilehalal-spa/another-exchange-rates-wp' );

// Constantes
define('AER_PATH', plugin_dir_path(__FILE__));
define('AER_URL', plugin_dir_url(__FILE__));

// Cargar módulos
require_once AER_PATH . 'includes/class-aer-logic.php';
require_once AER_PATH . 'includes/class-aer-widget.php';

class AnotherExchangeRatesPlugin {

    public function __construct() {
        // Registrar Widget
        add_action('widgets_init', function() {
            register_widget('AER_Widget');
        });

        // Cargar estilos
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'aer-styles', 
            AER_URL . 'assets/css/style.css', 
            array(), 
            '1.0'
        );
    }
}

new AnotherExchangeRatesPlugin();