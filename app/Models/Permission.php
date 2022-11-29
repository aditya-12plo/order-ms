<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table        = 'permission';
    protected $primaryKey   = 'id_permission';
	
    protected $fillable     = array('code','name','create_by','update_by','sequence');
    public $timestamps      = true;
  
    protected $hidden = [
        'create_by','update_by',
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
      
   
    public function detail_user_create()
    {
		  return $this->belongsTo('App\Models\User','id_user','create_by');        
    }
   
    public function update_user_create()
    {
		  return $this->belongsTo('App\Models\User','id_user','update_by');        
    }


}
