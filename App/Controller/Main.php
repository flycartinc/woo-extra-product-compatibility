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
    static function renderPrice($price_html, $product_id)
    {
        if (empty($product_id)) {
            return $price_html;
        }
        $original_price = Product::getPrice($product_id);
        if (!isset(self::$extra_price[$product_id])) {
            return $price_html;
        }
        $data = self::$extra_price[$product_id];
        $price = isset($data['price_final']) ? $data['price_final'] : '';
        if (empty($price) || !isset($data['product'])) {
            return $price_html;
        }
        if ($original_price != $price) {
            $result = apply_filters('wdr_get_product_discounted_price', false, $data['product'], 1, $price);
            if ($result !== false) {
                $price_html = "<del>{$price_html}</del><ins>" . wc_price($result) . "</ins>";
            }
        }

        return $price_html;
    }

    public static $extra_price = [];

    static function checkPriceFinal($price_final, $price_original, $price_extra, $product)
    {

        $product_id = $product->get_id();
        if (!isset(self::$extra_price[$product_id])) {
            self::$extra_price[$product_id] = array(
                'price_extra' => $price_extra,
                'original_price' => $price_original,
                'price_final' => $price_final,
                'product' => $product
            );
        }
        return $price_final;
    }
}