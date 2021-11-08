<?php

namespace App\Interfaces\Repositories;

use Illuminate\Http\Request;

interface IPurchaseRepository
{
    public function save(Request $request);

    public function getAllUserPurchasesByMonth($month, $year);
}
