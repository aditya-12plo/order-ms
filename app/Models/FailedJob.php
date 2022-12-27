<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob  extends Model
{

    protected $table 		= 'failed_jobs';
    protected $primaryKey 	= 'id';
	
    protected $fillable = array(
        "connection",
        "queue",
        "payload",
        "exception",
        "failed_at"
		);
		
    public $timestamps = false;
  
}
