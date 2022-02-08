<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Doctor Extends BaseModel {

    protected $table = 'doctordetails';
    protected $fillable = array(
								'id',
								'firstName', 
								'middleName',
								'lastName', 							 
								'sex',
								'mobileNo',
								'email',
								'status',
								'userid'	
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
