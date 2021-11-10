<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = "user";

    protected $fillable = [
        "name",
        "password",
        "email"
    ];

    protected $attributes = [
        "is_admin" => false
    ];

    protected $hidden = [
        "password"
    ];

    public $timestamps = false;

    public function deposit()
    {
        $this->hasMany(Deposit::class);
    }

    public function purchase()
    {
        $this->hasMany(Purchase::class);
    }
}
