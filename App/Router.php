<?php

namespace WDRPO\App;

use WDRPO\App\Controller\Main;

defined("ABSPATH") or die();
class Router
{
    /**
     * @var Main
     */

    public static function init()
    {
        add_action('wp_loaded',function (){

            if (\WDRPO\App\Controller\Main::check()) {
                add_filter('thwepo_product_price_html', 'WDRPO\App\Controller\Main::renderPrice', 10, 2);

            }

        });

    }
}