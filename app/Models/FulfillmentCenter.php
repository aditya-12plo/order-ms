<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentCenter extends Model
{

    protected $table = 'fulfillment_center';
    protected $primaryKey = 'fulfillment_center_id';
	 public $incrementing = true;
    protected $fillable = array('code','name','pic','phone','mobile','fax','email','address','address2','country',
                    'province','city','area','sub_area','postal_code','village','status','remarks','longitude',
                    'latitude','fulfillment_center_type_id',
                    'add1','add2','add3','add4','add5');
    public $timestamps = true;

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
          ->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at'])
           ->format('Y-m-d H:i:s');
        // return \Carbon\Carbon::parse($this->attributes['updated_at'])
        //    ->diffForHumans();
    }

    /**
     * Get the fulfillment that owns the company.
     */
	
    public function inventorys()
    {
		return $this->hasMany('App\Models\Inventory','fulfillment_center_id');
    }
	
	
    public function fulfillment_type_desc()
    {
		  return $this->belongsTo('App\Models\FulfillmentCenterType','fulfillment_center_type_id');        
    }
}
