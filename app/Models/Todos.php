<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todos extends Model
{
    use HasFactory;
    public $fillable = [
        'user_id',
        'name',
        'slug',
        'activity',
        'status',
        'deleted_at'
    ];
    
    protected $appends = ['user'];

    public function user()
    {

      return $this->belongsToMany(Users::class);
    }

    public function getUserAttribute()
    {

        $user = $this->user()->first();
        if(!is_null($user)){

            $user = $user->only('id','name', 'email');
        }
        return $user;
    }
  
  
    
}
