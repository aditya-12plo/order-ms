<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{

    protected $table = 'permission_role';
    protected $primaryKey = 'id_permission_role';
	
    protected $fillable = array(
        "id_role",
        "id_permission",
        "method_create",
        "method_read",
        "method_update",
        "method_delete",
        "method_upload",
        "method_custom1",
        "method_custom2",
        "method_custom3",
        "method_custom4",
        "method_custom5",
        "create_by",
        "update_by",
    );
    public $timestamps = true;
  
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
   
    public function role()
    {
		  return $this->belongsTo('App\Models\Role','id_role','id_role');        
    }
   
    public function permission()
    {
		  return $this->belongsTo('App\Models\Permission','id_permission','id_permission');        
    }


}
