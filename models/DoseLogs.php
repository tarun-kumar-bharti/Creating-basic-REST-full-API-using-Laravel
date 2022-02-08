<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class DoseLogs Extends BaseModel {

    protected $table = 'doselogs';
    protected $fillable = array(
								'id',
								'dosefrequencyid', 
								'dosedate', 
								'morningdose',
								'eveningdose',
								'subquestion1_id',
								'subquestion1_score',
								'subquestion2_id',
								'subquestion2_score',
								'subquestion3_id',
								'subquestion3_score', 
								'subquestion3_updated_score',
								'subquestion4_id',
								'subquestion4_score',
								'doseencounterid',
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
 