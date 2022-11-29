<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $table        = 'company';
    protected $primaryKey   = 'id_company';
	// public $incrementing    = false;
    // protected $keyType      = 'string';
    protected $fillable     = array(
        "code",
        "name",
        "pic_name",
        "email",
        "phone",
        "mobile",
        "fax",
        "country",
        "province",
        "city",
        "area",
        "sub_area",
        "village",
        "postal_code",
        "address",
        "address2",
        "address3",
        "notes",
        "latitude",
        "longitude",
        "user_limit",
        "company_type",
        "status",
        "create_by",
        "update_by",
        "user_total"
    );
    public $timestamps      = true;

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
    
    /**
     * Get the user that owns the company.
     */

    public function user_create()
    {
		  return $this->belongsTo('App\Models\User','create_by','id_user');  
    }
   
    public function user_update()
    {
		  return $this->belongsTo('App\Models\User','create_by','id_user'); 
    }
	
	
    public function fulfillments()
    {
        return $this->hasMany('App\Models\CompanyFulfillment','company_id');
    }
	
	
    public function company_group()
    {
        return $this->belongsTo('App\Models\CompanyGroup','company_group_id');
    }
	
}
