<?php

namespace App\Repositories;

use App\Exceptions\SQLException;
use App\Interfaces\Repositories\IPurchaseRepository;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Exception;

class PurchaseRepository implements IPurchaseRepository
{
    public function save(Request $request)
    {
        try {
            $purchase = Purchase::create([
                "description" => $request->input("description"),
                "value" => $request->input("value"),
                "fk_user" => $request->input("user_fk"),
                "purchase_date" => $request->input("purchase_date")
            ]);

            return $purchase;
        } catch (Exception $e) {
            throw new SQLException($e->getMessage() . "Couldn't save purchase", 500);
        }
    }
}
