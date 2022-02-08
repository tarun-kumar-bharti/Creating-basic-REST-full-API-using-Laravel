<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EncounterLogs Extends BaseModel {

    protected $table = 'encounterlogs';
    protected $fillable = array(
								'id',
								'doseencounterid', 
								'startdate', 
								'enddate',
								'startdose',
								'enddose',
								'completestatus',
								'patientid',
								'userid',
								'status'
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
 