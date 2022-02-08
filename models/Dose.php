<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Dose Extends BaseModel {

    protected $table = 'dosefrequency';
    protected $fillable = array(
								'id',
								'morningdose', 								 
								'eveningdose',
								'frequency',
								'bowelmovements',
								'patientid',
								'userid',
								'doseencounterid',
								'firstrecord',
								'dosewatch',
								'completestatus',
								'healthyclose',
								'status'
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	static $FREQUENCY_ARRAY = array(70,65,60,55,50,45,40,35,30,25,20,15,10,5,0); 
	
	public function doselogs() {
        return $this->hasMany('App\models\DoseLogs','dosefrequencyid','id')
					->where('status','=','A')
					->OrderBy('created_at','asc');
    }  
	
}
 