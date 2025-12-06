<?php

namespace App\Helpers;

class InvoiceGenerator
{
    public static function make()
    {
        return 'INV-' . date('Ymd') . '-' . rand(1000,9999);
    }
}
