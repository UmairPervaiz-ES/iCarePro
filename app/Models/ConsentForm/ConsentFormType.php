<?php

namespace App\Models\ConsentForm;

use App\Traits\ConsentFormAuthentication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsentFormType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ConsentFormAuthentication;

    protected $guarded = []; 
    
    public function practice(): HasOne
    {
        return $this->hasOne(Practice::class);
    }
    
    public function consentForms(): HasMany
    {
        return $this->hasMany(ConsentForm::class);
    }
    
    public function publishConsentForm(): HasOne
    {
        return $this->hasOne(ConsentForm::class);
    }
}
