<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = "purchase";

    protected $fillable = [
        "fk_user",
        "description",
        "value",
        "purchase_date"
    ];

    public $timestamps = false;

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
