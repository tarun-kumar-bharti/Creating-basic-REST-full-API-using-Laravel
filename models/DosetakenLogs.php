<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class DosetakenLogs Extends BaseModel {

    protected $table = 'dosetakenlogs';
    protected $fillable = array(
								'id',
								'userid', 
								'doseencounterid', 
								'dosetakendate',
								'morningdose',
								'eveningdose', 
								'completestatus',
								'status' 
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
 