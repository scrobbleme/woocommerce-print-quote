<?php

/**
 * Plugin Name: WooCommerce Print Quote
 * Plugin URI: http://git.githost.de/wordpress/woocommerce-print-quote
 * Description: This plugin allows administrators to print a quote for a orders. It requires the plugin "WooCommerce Print Invoices & Delivery Notes" to work.
 * Author: Adrian Moerchen
 * Author URI: http://demo.moewe-studio.com/wp/
 * Version: 1.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

final class WooCommerce_Print_Quote
{

    public function __construct()
    {
        add_action('init', array($this, 'plugin_init'));

        add_filter('wcdn_template_registration', array($this, 'add_template_type'), 100, 1);
        add_filter('wcdn_document_title', array($this, 'get_document_title'), 100);
        add_filter('wcdn_order_info_fields', array($this, 'update_order_info'), 10, 2);

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    function admin_enqueue_scripts()
    {
        wp_enqueue_style('woocommerce-print-quote-css', plugins_url('styles.css', __FILE__), false, '1.1');
    }

    function plugin_init()
    {
        load_plugin_textdomain('woocommerce-print-quote', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function add_template_type($templates)
    {
        $templates[] = array(
            'type' => 'quote',
            'labels' => array(
                'name' => __('Quote', 'woocommerce-print-quote'),
                'name_plural' => __('Quotes', 'woocommerce-print-quote'),
                'print' => __('Print Quote', 'woocommerce-print-quote'),
                'print_plural' => __('Print Quotes', 'woocommerce-print-quote'),
                'message' => __('Quote created.', 'woocommerce-print-quote'),
                'message_plural' => __('Quote created.', 'woocommerce-print-quote'),
                'setting' => __('Enable Quotes', 'woocommerce-print-quote')
            )
        );
        return $templates;
    }

    function get_document_title($title)
    {
        if (wcdn_get_template_type() == 'quote') {
            return __('Quote', 'woocommerce-print-quote');
        }
        return $title;
    }

    function update_order_info($fields, $order)
    {
        if (wcdn_get_template_type() != 'quote') {
            return $fields;
        }
        unset($fields['order_number']);
        unset($fields['payment_method']);
        $fields['order_date']['label'] = __('Quotation Date', 'woocommerce-print-quote');
        return $fields;
    }
}

new WooCommerce_Print_Quote();
