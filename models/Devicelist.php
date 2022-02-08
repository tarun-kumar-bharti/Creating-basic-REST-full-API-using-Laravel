<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Devicelist Extends BaseModel {

    protected $table = 'devicelist';
    protected $fillable = array(
								'id',
								'device_token', 								 
								'device_id',
								'os_version',
								'model',
								'user_id',
								'logoutstatus' 
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status 
	
}
 