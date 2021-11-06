<?php

namespace App\Repositories;

use App\Exceptions\SQLException;
use App\Interfaces\Repositories\IDepositRepository;
use App\Models\Deposit;
use Exception;
use Illuminate\Http\Request;

class DepositRepository implements IDepositRepository
{
    public function saveDepositPending(Request $request)
    {
        $image = $this->imageParser($request);

        try {
            $deposit = Deposit::create([
                "fk_user" => (int)$request->input("user_fk"),
                "description" => $request->input("description"),
                "value" => (float)$request->input("value"),
                "check_img" => $image,
                "fk_deposit_status" => 1
            ]);
            return $deposit;
        } catch (Exception $e) {
            throw new SQLException($e, 500);
        }
    }

    private function imageParser(Request $request)
    {
        if ($request->hasFile("image") && $request->file("image")->isValid()) {
            try {
                $requestImage = $request->image;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $request->image->move(public_path("img/checks"), $imageName);
                return $imageName;
            } catch (Exception $e) {
                throw new Exception("There is some error in the image file", 500);
            }
        }
        throw new Exception("The file was not recognized as a image", 500);
    }

    public function getDepositsByUserAndByStatusAndMonth(Request $request)
    {
        $month = (int)($request->input("month"));
        $year = (int)($request->input("year"));
        $fkUser = (int)($request->input("user_fk"));
        $fkDepositStatus = (int)($request->input("deposit_status_fk"));

        try {
            $deposits = Deposit::select(["id", "description", "updated_at", "value"])
                ->where("fk_user", $fkUser)
                ->where("fk_deposit_status", $fkDepositStatus)
                ->whereYear("updated_at", $year)
                ->whereMonth("updated_at", $month)
                ->orderBy("updated_at", "asc")
                ->get();

            return $deposits;
        } catch (Exception $e) {
            throw new SQLException("Could not retrieve data from database", 500);
        }
    }

    public function getAllPendingDeposits()
    {
        try {
            $deposits = Deposit::select("id", "description", "updated_at", "value")
                ->where("fk_deposit_status", 1)
                ->orderBy("updated_at", "ASC")
                ->get();

            return $deposits;
        } catch (Exception $e) {
            throw new SQLException($e->getMessage() . "Could not retrieve pending requests from database", 500);
        }
    }

    public function getDepositById($id)
    {
        try {
            $deposit = Deposit::where("id", $id)->first();

            return $deposit;
        } catch (Exception $e) {
            throw new SQLException("Could not retrieve deposit details", 500);
        }
    }

    public function getDepositsSumWhereStatusIsApproved()
    {
        try {
            $id = auth()->user()->id;
            $totalDeposits = Deposit::where("fk_user", $id)
                ->where("fk_deposit_status", 2)
                ->sum("value");

            return $totalDeposits;
        } catch (Exception $e) {
            throw new SQLException("Could not get sum of approved deposits from database", 500);
        }
    }

    public function updateDepositStatus($depositId, $status, $customerId)
    {
        try {
            $deposit = Deposit::where("fk_user", $customerId)
                ->where("id", $depositId)
                ->update(["fk_deposit_status" => $status]);

            return $deposit;
        } catch (Exception $e) {
            throw new SQLException("Couldn't update deposit status in database", 500);
        }
    }
}
