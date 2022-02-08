<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class LoginHistory Extends BaseModel {

    protected $table = 'loginhistory';
    protected $fillable = array(
								'id',
								'userid',
								'loginstatus',
								'logoutstatus',
								'token'	
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
 