<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Consecutive Extends BaseModel {

    protected $table = 'consecutive_history';
    protected $fillable = array(
								'id',
								'patientid', 
								'userid',
								'doseencounterid', 							 
								'scoredon',
								'status' 	
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
