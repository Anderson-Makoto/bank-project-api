<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IPurchaseRepository;
use App\Interfaces\Services\IPurchaseService;
use Illuminate\Http\Request;
use Exception;

class PurchaseService implements IPurchaseService
{
    private $purchaseRepository;
    private $userRepository;
    public function __construct(IPurchaseRepository $iPurchaseRepository, IUserRepository $iUserRepository)
    {
        $this->purchaseRepository = $iPurchaseRepository;
        $this->userRepository = $iUserRepository;
    }

    public function savePurchase(Request $request)
    {
        try {
            $this->verifyIfPurchaseIsLowerThanBalance($request);

            $purchase = $this->purchaseRepository->save($request);

            return $purchase;
        } catch (Exception $e) {
            throw new MainException("Could not save purchase", $e->getCode(), $e->getMessage());
        }
    }

    private function verifyIfPurchaseIsLowerThanBalance(Request $request)
    {
        $currentBalance = $this->userRepository->getUserBalance();
        if ($currentBalance < (float)($request->input("value")))
            throw new Exception("Not enough balance in account", 400);
    }

    public function getAllPurchasesByMonth($month, $year)
    {
        try {
            $purchases = $this->purchaseRepository->getAllUserPurchasesByMonth($month, $year);

            return $purchases;
        } catch (Exception $e) {
            throw new MainException("Couldn't get all user purchases", $e->getCode(), $e->getMessage());
        }
    }
}
