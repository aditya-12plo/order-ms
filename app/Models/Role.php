<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table        = 'role';
    protected $primaryKey   = 'id_role';
	
    protected $fillable     = array('code','description','create_by','update_by');
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
