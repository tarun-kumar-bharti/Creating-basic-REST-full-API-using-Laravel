<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class SubQuestion Extends BaseModel {

    protected $table = 'questionscorelist';
    protected $fillable = array(
								'id',
								'qsnid', 
								'subquestion',
								'type',
								'score',
								'status'
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status  
	
	
	public function mainqsn() {
        return $this->hasOne('App\models\Question','id','qsnid')
					->where('status','=','A')
					->OrderBy('created_at','asc');
    }  
	
}
 