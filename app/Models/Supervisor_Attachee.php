<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor_Attachee extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'supervisor_attachee';
    protected $primarykey = 'intern_id';
    public $incrementing = false;
    protected $fillable =[
'intern_id',
'supervisor_id'
    ];
    public function uniqueIds(): array
    {
        return ['intern_id'];
    }
   
}
