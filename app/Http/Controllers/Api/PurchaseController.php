<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePurchaseRequest;
use App\Interfaces\Services\IPurchaseService;


class PurchaseController extends Controller
{
    private $purchaseService;
    public function __construct(IPurchaseService $iPurchaseService)
    {
        $this->purchaseService = $iPurchaseService;
    }

    public function store(SavePurchaseRequest $request)
    {
        $request->validated();

        try {
            $purchase = $this->purchaseService->savePurchase($request);

            return response()->json([
                "data" => $purchase
            ], 201);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function getAllPurchasesByMonth($month, $year)
    {
        try {
            $purchases = $this->purchaseService->getAllPurchasesByMonth($month, $year);

            return response()->json([
                "data" => $purchases
            ], 200);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }
}
