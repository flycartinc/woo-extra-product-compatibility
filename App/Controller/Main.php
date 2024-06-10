<?php

namespace WEPC\App\Controller;

use WDR\Core\Models\WC\Product;

defined("ABSPATH") or die();

class Main
{

    /**
     * Checks available rules.
     *
     * @return bool
     */
    function check()
    {
        $rules = \WDR\Core\Models\Custom\StoreRule::getRules();
        if (empty($rules)) {
            return false;
        }
        $res = [];
        foreach ($rules as $rule) {
            if ($rule->getDiscountContext() == 'item' && !in_array($rule->getType(), array(
                    'buy_x_get_x',
                    'buy_x_get_y'
                ))) {
                $res[] = $rule;
            }
        }
        if (empty($res)) {
            return false;
        }

        return true;
    }

    /**
     * @param $price_html
     * @param $product_id
     * @return mixed|string
     */
    static function renderPrice($price_html, $product_id)
    {
        if (empty($product_id)) {
            return $price_html;
        }
        $price = trim(strip_tags($price_html));
        if (!function_exists('wc_get_product')) {
            return $price_html;
        }
        $product = Product::get($product_id);
        $original_price = Product::getPrice($product_id);
        $prices = explode(';', $price);
        $price = apply_filters('wdr_extra_product_compatibility_price', end($prices), $product_id,$price_html);
        if ($original_price != $price) {
            $result = apply_filters('wdr_get_product_discounted_price', false, $product, 1, $price);
            $result = (float)apply_filters('wdr_discount_get_product_price', false, $product, $product_id, 'cart');
            if ($result !== false) {
               $price_html = "<del>{$price_html}</del><ins>" . wc_price($result) . "</ins>";
            }
        }

        return $price_html;
    }

}