<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{
    use HasFactory;
    protected $table ='reasons';
    protected $fillable =[
'reason'
    ];
    public function attachee(){
        return $this->belongsTo(Attache_details::class);
    }
}

