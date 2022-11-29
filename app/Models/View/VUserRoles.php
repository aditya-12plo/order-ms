<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Model;

class VUserRoles extends Model
{

    protected $table        = 'vw_user_roles';
    protected $primaryKey   = null;
    public $incrementing    = false;
    public $timestamps      = false;
	
    protected $fillable     = array('id_permission','code','name','sequence','id_role','method_create','method_read','method_update',
    'method_delete','method_upload','method_custom1','method_custom2','method_custom3','method_custom4','method_custom5');
  


}
