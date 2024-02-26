<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attache_details extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'attache_details';
    protected $primarykey = 'id';
    public $incrementing = false;
    protected $fillable =[
'fullname',
'email',
'phone',
'academic',
'role_id',
'duration',
'cv',
'is_accepted',
'chosen',
'reason_id',
'user_id'
    ];
    public function uniqueIds(): array
    {
        return ['id'];
    }
    protected $casts = [
        'duration' => 'integer',
    ];
    
    
    
   
    public function supervisor(){
        return $this->belongsToMany(Supervisor_Attachee::class,'supervisor_attachees');
    }
    public function reason(){
        return $this->hasOne(Reject::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function interview()
    {
        return $this->HasOne(Interview::class,'attachee_id');
    }
    public function role(){
        return $this->belongsTo(Role::class);
      }
}
