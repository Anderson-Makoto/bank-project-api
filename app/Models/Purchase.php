<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = "user";

    protected $fillable = [
        "user_fk",
        "description",
        "value",
        "purchase_date"
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
