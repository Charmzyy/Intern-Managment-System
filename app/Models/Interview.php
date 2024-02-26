<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primarykey = 'id';
    public $incrementing = false;
    
    protected $fillable =[
        'name',
        'venue',
        'interview_date',
        'interview_time',
        'attachee_id',
        'rating',
        'comments',
        'user_id',
        'admin_reviewed'
    ];
    public function uniqueIds(): array
{
    return ['id'];
}
  
    public function attachee()
    {
        return $this->belongsTo(Attache_details::class);
    }
    


    public function user(){
        return $this->belongsTo(User::class);
    }
}
