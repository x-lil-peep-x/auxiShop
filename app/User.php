<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','last_name', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function sales() {
        return $this->hasMany('App\Models\Sale', 'user_id', 'id');
    }
    public function get_full_name() {
        return $this->last_name . ' '.$this->name;
    }
    public function Role()
    {
        return $this->belongsTo('App\Models\Role','role_id','id');
    }
    public function stores() {
        return $this->belongsToMany('App\Models\Store','store_users','user_id', 'store_id');
    }
    public function hasStore($store_id) {
        foreach ($this->stores as $store) {
            if ($store->pivot->store_id == $store_id) {
                return true;
            }
        }
        return false;
    }
}
