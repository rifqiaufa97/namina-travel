<?php

if (!function_exists('format_currency')) {
    /**
     * Format number as currency
     * 
     * @param  float  $amount
     * @param  string  $prefix
     * @return string
     */
    function format_currency($amount, $prefix = 'Rp')
    {
        return $prefix . ' ' . number_format($amount, 0, ',', '.');
    }
}
