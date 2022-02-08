<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Mayo Extends BaseModel {

    protected $table = 'mayoquestion';
    protected $fillable = array(
								'id',
								'question', 
								'maxscore', 
								'status'
						);
 
	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	
	 
}
 