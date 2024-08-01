<?php

namespace App\Models\Practice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticePaymentMethod extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function practice(){
     return $this->belongsTo(Practice::class);
    }

}
