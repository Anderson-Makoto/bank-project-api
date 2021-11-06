<?php

namespace App\Interfaces\Services;

use Illuminate\Http\Request;

interface IPurchaseService
{
    public function savePurchase(Request $request);
}
