<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attache extends Model
{
    use HasFactory;

    public function interviews(){
        return $this->hasMany(Interview::class);
    }
}
