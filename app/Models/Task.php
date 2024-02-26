<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = "tasks";
    protected $primarykey = 'id';
  
    protected $fillable =[
      'user_id',
      'name',
      'description',
      'accepted',
      'assigned',
      'due',
      'status',
      'rate'
    ];
  
    protected $casts = [
        'duration' => 'integer',
    ];
 public function user(){
  return $this->belongsTo(User::class);
 }
 public function tasks(){
  return $this->hasMany(Comment::class);
 }
    
}
