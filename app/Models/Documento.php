<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Documento extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function persona(): HasOne
    {
        return $this->hasOne(Persona::class);
    }

}
