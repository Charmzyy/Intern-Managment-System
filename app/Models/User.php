<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasUuids;
     protected $primarykey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'password_confirmation',
        'position', //postion should be dynamic check with hussien about this
        'bio',//bio
        'avatar'//avatar
    ];
    public function uniqueIds(): array
    {
        return ['id'];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


public function interns(){
    return $this->belongsToMany(User::class,'supervisor_attachee','supervisor_id','intern_id');
}
public function supervisors(){
  return $this->belongsToMany(User::class,'supervisor_attachee','intern_id','supervisor_id');
}
  public function attachee(){
    return $this->hasOne(Attache_details::class);
  } 
  public function interviews(){
    return $this->hasMany(Interview::class);
  }
  public function tasks(){
    return $this->hasMany(Task::class);
  }
  public function group(){
    return $this->HasMany(GroupChat::class);
  }
  public function myrole(){
    return $this->belongsTo(Role::class);
  }
  
     
}
