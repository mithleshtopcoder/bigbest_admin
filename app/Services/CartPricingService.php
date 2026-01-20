<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Store;

class CartPricingService
{
    public function calculate($cartItems, ?Store $store = null): array
    {
        $settings = AppSetting::getSettings();

        $subtotal = $cartItems->sum('total_price');

        /**
         * ---------------------------------------
         * TAX CALCULATION (from DB)
         * ---------------------------------------
         */
        $taxPercent = (float) $settings->delivery_tax_percent;
        $taxAmount  = ($subtotal * $taxPercent) / 100;

        /**
         * ---------------------------------------
         * DELIVERY CHARGE CALCULATION
         * ---------------------------------------
         */
        $deliveryCharge = 0;

        if ($subtotal < $settings->free_delivery_threshold) {
            $deliveryCharge = (float) $settings->delivery_charge;
        }

        /**
         * ---------------------------------------
         * MIN ORDER CHECK
         * ---------------------------------------
         */
        $minOrderAmount = (float) $settings->min_order_amount;
        $isMinOrderMet  = $subtotal >= $minOrderAmount;

        /**
         * ---------------------------------------
         * GRAND TOTAL
         * ---------------------------------------
         */
        $grandTotal = $subtotal + $taxAmount + $deliveryCharge;

        return [
            // Amounts
            'subtotal'            => round($subtotal, 2),
            'tax_percent'         => $taxPercent,
            'tax_amount'          => round($taxAmount, 2),
            'shipping_charge'     => round($deliveryCharge, 2),
            'grand_total'         => round($grandTotal, 2),

            // Rules info
            'min_order_amount'    => round($minOrderAmount, 2),
            'is_min_order_met'    => $isMinOrderMet,
            'free_delivery_above' => round($settings->free_delivery_threshold, 2),

            // UI helpers (optional)
            'currency'            => $settings->currency,
            'currency_symbol'     => $settings->currency_symbol,
        ];
    }
}