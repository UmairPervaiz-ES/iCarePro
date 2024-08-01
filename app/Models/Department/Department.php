<?php

namespace App\Models\Department;

use App\Models\Practice\Practice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }

    public function departmentEmployeeTypes(): HasMany
    {
        return $this->hasMany(DepartmentEmployeeType::class);
    }
}
