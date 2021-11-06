<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Interfaces\Services\IDepositService;
use App\Interfaces\Repositories\IDepositRepository;
use App\Interfaces\Repositories\IUserRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FacadesFile;

class DepositService implements IDepositService
{
    private $depositRepository;
    private $userRepository;

    public function __construct(IDepositRepository $iDepositRepository, IUserRepository $iUserRepository)
    {
        $this->depositRepository = $iDepositRepository;
        $this->userRepository = $iUserRepository;
    }

    public function registerDepositPending(Request $request)
    {
        try {
            $deposit = $this->depositRepository->saveDepositPending($request);

            return $deposit;
        } catch (Exception $e) {
            throw new MainException("Could not save deposit pending", $e->getCode(), $e->getMessage());
        }
    }

    public function getUserDepositsByStatusAndByMonth(Request $request)
    {
        try {
            $deposits = $this->depositRepository->getDepositsByUserAndByStatusAndMonth($request);

            return $deposits;
        } catch (Exception $e) {
            throw new MainException("Could not get deposits", $e->getCode(), $e->getMessage());
        }
    }

    public function getCustomersPendingDeposits()
    {
        try {
            $deposits = $this->depositRepository->getAllPendingDeposits();

            return $deposits;
        } catch (Exception $e) {
            throw new MainException("Could not get pending deposits", $e->getCode(), $e->getMessage());
        }
    }

    public function getPendingDepositDetails($depositId, $customerId)
    {
        try {
            $depositData = $this->depositRepository->getDepositById($depositId);

            $depositData = $this->getImageAsBase64($depositData);

            $customerData = $this->userRepository->getUserById($customerId);

            return [
                "deposit" => $depositData,
                "customer" => $customerData
            ];
        } catch (Exception $e) {
            throw new MainException($e->getMessage() . "Could not retrieve pending deposit's details", $e->getCode(), $e->getMessage());
        }
    }

    private function getImageAsBase64($depositData)
    {
        try {
            $checkImgPath = public_path("img/checks/") . $depositData->check_img;
            $checkImg = file_get_contents($checkImgPath);
            $depositData["checkImgBase64"] = base64_encode($checkImg);

            return $depositData;
        } catch (Exception $e) {
            throw new Exception("Could not convert image in base64 format", 500);
        }
    }

    public function changeDepositStatus($depositId, $status, $customerId)
    {
        try {
            $deposit = $this->depositRepository->updateDepositStatus($depositId, $status, $customerId);
            return $deposit;
        } catch (Exception $e) {
            throw new MainException("Could not change deposit status", $e->getCode(), $e->getMessage());
        }
    }
}
