<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ClosenotificationLogs Extends BaseModel {

    protected $table = 'closenotificationlogs';
    protected $fillable = array(
								'id',
								'userid', 
								'seventh',
								'thirtieth', 
								'seventhstatus',
								'thirtiethstatus',
								'status' 	
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
