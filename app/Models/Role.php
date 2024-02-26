<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
 
    protected $table ='roles';
    protected $primarykey = 'id';
    
    protected $fillable = [
'role',
'description'
    ];
    
    
    public function attachee(){
        return $this->HasMany(Attache_details::class);
    }

    public function user(){
        return $this->HasMany(User::class);
    }
}
