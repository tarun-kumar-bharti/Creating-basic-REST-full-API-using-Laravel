<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Question Extends BaseModel {

    protected $table = 'questionlist';
    protected $fillable = array(
								'id',
								'question', 
								'type',
								'status'
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status 
	 
	public function subquestion() {
        return $this->hasMany('App\models\SubQuestion','qsnid','id')
					->where('status','=','A')
					->OrderBy('id','asc');
    } 
}
 