<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = "deposit";

    protected $fillable = [
        "fk_user",
        "description",
        "value",
        "check_img",
        "fk_deposit_status"
    ];

    public $timestamps = true;

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function depositStatus()
    {
        $this->belongsTo(DepositStatus::class);
    }
}
