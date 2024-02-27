<?php

namespace WEPC\App\Controller;
use WDR\Core\Models\WC\Product;
defined("ABSPATH") or die();
class Main
{
    /**
     * @param $price_html
     * @param $product_id
     * @return mixed|string
     */
    function renderPrice($price_html, $product_id)
    {
        /* if (!Woocommerce::is_ajax()) {
             return $price_html;
         }*/
        $price = trim(strip_tags($price_html));
        $replace_strings = array('&#36;', '&nbsp;');
        if (!function_exists('wc_get_product')) {
            return $price_html;
        }
        $product = Product::get($product_id);
        $original_price = Product::getPrice($product_id);
        $prices = explode('&nbsp;', $price);
        $price = (float)str_replace($replace_strings, '', $prices[0]);
        if ($original_price != $price) {
            $result = apply_filters('wdr_get_product_discounted_price',$price,$product,1,$original_price);
            if ($result !== false) {
                $price_html = "<del>{$price_html}</del><ins>" . wc_price($result) . "</ins>";
            }
        }
        return $price_html;
    }
}