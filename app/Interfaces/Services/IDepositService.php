<?php

namespace App\Interfaces\Services;

use Illuminate\Http\Request;

interface IDepositService
{
    public function registerDepositPending(Request $request);

    public function getUserDepositsByStatusAndByMonth(Request $request);

    public function getCustomersPendingDeposits();

    public function getPendingDepositDetails($depositId, $customerId);

    public function changeDepositStatus($depositId, $status, $customerId);
}
