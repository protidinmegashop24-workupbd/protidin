<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    // Fillable properties to allow mass assignment
    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'transaction_id',
        'approval',
    ];

    // You can also define other properties or methods here if needed
}
