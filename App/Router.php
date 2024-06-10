<?php

namespace WEPC\App;

use WEPC\App\Controller\Main;

defined("ABSPATH") or die();
class Router
{
    /**
     * @var Main
     */
    private static $main;

    function init()
    {
        self::$main = empty(self::$main) ? new Main() : self::$main;
        if (self::$main->check()) {
            add_filter('thwepo_product_price_html', [self::$main, 'renderPrice'], 100, 2);
            add_action('woocommerce_before_calculate_totals',function (){

            },10,1);
        }
    }
}