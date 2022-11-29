<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyGroup extends Model
{

    protected $table        = 'company_group';
    // protected $primaryKey   = 'id_company';
	// public $incrementing    = false;
    // protected $keyType      = 'string';
    protected $fillable     = array(
        "id_company_group",
        "id_company"
    );
    public $timestamps      = false;
 
	
    public function company_detail()
    {
        return $this->belongsTo('App\Models\Company','id_company');
    }
	
	 
	
}
