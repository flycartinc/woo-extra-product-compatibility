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
        add_filter('thwepo_product_price_html', [self::$main, 'renderPrice'], 10, 2);
    }
}