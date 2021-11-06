<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositPendingRequest;
use App\Http\Requests\GetUserDepositsByStatusRequest;
use App\Interfaces\Services\IDepositService;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    private $depositService;

    public function __construct(IDepositService $iDepositService)
    {
        $this->depositService = $iDepositService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepositPendingRequest $request)
    {
        $request->validated();

        try {
            $deposit = $this->depositService->registerDepositPending($request);

            return response()->json([
                "data" => $deposit
            ], 201);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function getUserDepositsByStatusAndByMonth(GetUserDepositsByStatusRequest $request)
    {
        $request->validated();

        try {
            $deposits = $this->depositService->getUserDepositsByStatusAndByMonth($request);

            return response()->json([
                "data" => $deposits
            ], 200);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function getCustomersPendingDeposits()
    {
        try {
            $deposits = $this->depositService->getCustomersPendingDeposits();

            return response()->json([
                "data" => $deposits
            ], 200);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function getPendingDepositDetails($depositId, $customerId)
    {
        try {
            $depositAndCustomerData = $this->depositService->getPendingDepositDetails($depositId, $customerId);

            return response()->json([
                "data" => $depositAndCustomerData
            ]);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }
}
