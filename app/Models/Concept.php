<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;

    protected $fillable = ['concept_name'];

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
