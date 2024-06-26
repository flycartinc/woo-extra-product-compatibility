<?php
/**
 * Plugin Name:         Discount rules : Extra product options compatibility
 * Plugin URI:          https://www.flycart.org
 * Description:         This add-on used to give compatibility of woocommerce extra product option by ThemeHigh for woo discount rules.
 * Version:             1.0.0
 * Requires at least:   5.3
 * Requires PHP:        5.6
 * Author:              Flycart
 * Author URI:          https://www.flycart.org
 * Slug:                wdr-product-options-compatibility
 * Text Domain:         wdr-product-options-compatibility
 * Domain path:         /i18n/languages/
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * WC requires at least: 4.3
 * WC tested up to:     8.0
 */

defined( "ABSPATH" ) or die();

if ( ! function_exists( 'isWoocommerceActive' ) ) {
    function isWoocommerceActive() {
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( 'woocommerce/woocommerce.php', $active_plugins, false ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
    }
}
if ( ! function_exists( 'isDiscountRulesActive' ) ) {
    function isDiscountRulesActive() {
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( 'woo-discount-rules-pro/woo-discount-rules-pro.php', $active_plugins, false ) || in_array( 'woo-discount-rules/woo-discount-rules.php', $active_plugins, false );
    }
}

if ( ! isWoocommerceActive() || ! isDiscountRulesActive() ) {
    return;
}


if ( ! class_exists( '\WDR\Core\Helpers\Plugin' ) && file_exists( WP_PLUGIN_DIR . '/woo-discount-rules/vendor/autoload.php' ) ) {
    require_once WP_PLUGIN_DIR . '/woo-discount-rules/vendor/autoload.php';
} elseif ( file_exists( WP_PLUGIN_DIR . '/woo-discount-rules-pro/vendor/autoload.php' ) ) {
    require_once WP_PLUGIN_DIR . '/woo-discount-rules-pro/vendor/autoload.php';
}
if ( ! class_exists( '\WDR\Core\Helpers\Plugin' ) ) {
    return;
}

/**
 * Check discount rules plugin is latest.
 */
if ( ! function_exists( 'isWooDiscountLatestVersion' ) ) {
    function isWooDiscountLatestVersion() {
        $db_version = get_option( 'wdr_version', '' );
        if ( ! empty( $db_version ) ) {
            return ( version_compare( $db_version, "3.0.0", '>=' ) );
        }

        return false;
    }
}
if ( ! isWooDiscountLatestVersion() ) {
    return;
}

$plugin = new \WDR\Core\Helpers\Plugin();
if ( ! $plugin::isActive( 'woocommerce-extra-product-options-pro/woocommerce-extra-product-options-pro.php' ) ) {
    return;
}

defined( 'WDRPO_PLUGIN_NAME' ) or define( 'WDRPO_PLUGIN_NAME', 'Woo extra product option compatibility' );
defined( 'WDRPO_PLUGIN_VERSION' ) or define( 'WDRPO_PLUGIN_VERSION', '1.0.0' );
defined( 'WDRPO_PLUGIN_SLUG' ) or define( 'WDRPO_PLUGIN_SLUG', 'wdr-product-options-compatibility' );

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    return;
}
require __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( \WDRPO\App\Router::class ) ) {
    return;
}

$myUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/flycartinc/woo-extra-product-compatibility',
    __FILE__,
    'wdr-product-options-compatibility'
);
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

if ( ! method_exists( \WDRPO\App\Router::class, 'init' ) ) {
    return;
}

\WDRPO\App\Router::init();




