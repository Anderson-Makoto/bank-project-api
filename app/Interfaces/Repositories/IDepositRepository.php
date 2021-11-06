<?php

namespace App\Interfaces\Repositories;

use Illuminate\Http\Request;

interface IDepositRepository
{
    public function saveDepositPending(Request $request);

    public function getDepositsByUserAndByStatusAndMonth(Request $request);

    public function getAllPendingDeposits();

    public function getDepositById($id);
}
