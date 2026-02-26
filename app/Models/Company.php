<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $guarded = [];
    protected $table = 'companies';

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class,'company_id','id');
    }
}
