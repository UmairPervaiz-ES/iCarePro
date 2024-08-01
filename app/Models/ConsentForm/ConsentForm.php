<?php

namespace App\Models\ConsentForm;

use App\Traits\ConsentFormAuthentication;
use App\Traits\IdentifyAuthUser;
use App\Traits\UserRestriction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsentForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ConsentFormAuthentication;

    protected $guarded = [];

    public function formType(): BelongsTo
    {
        return $this->belongsTo(ConsentFormType::class);
    }
}
