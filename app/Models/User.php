<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract ,  CanResetPasswordContract
{
    use Authenticatable, Authorizable, HasFactory,CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table        = 'user';
    protected $primaryKey   = 'id_user';
    protected $fillable     = [
        'id_company', 'id_role', 'password','email','company_id','mobile','fax','email_verified_at',
        'create_by','update_by','status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'remember_token','password','email_verified_at',
    ];
    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
          ->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at'])
           ->format('Y-m-d H:i:s');
    }

    public function company_detail()
    {
    	return $this->belongsTo('App\Models\Company','id_company','id_company');
    }

    public function role()
    {
    	return $this->hasOne('App\Models\Role','id_role','id_role');
    }
}
