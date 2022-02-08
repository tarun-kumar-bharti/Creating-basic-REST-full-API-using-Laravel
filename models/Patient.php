<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model; 

class Patient Extends BaseModel {

    protected $table = 'patientdetails';
    protected $fillable = array(
								'id',
								'patient_code',
								'firstName', 
								'middleName',
								'lastName', 							 
								'sex',
								'age',
								'mobileNo',
								'email',
								'diagnosis',
								'parisclassification',
								'ageatdiagnosis',
								'locationdiagnosis',
								'behaviourdiagnosis',								
								'aminoacylates',
								'immunomodulators',
								'biologics',
								'complications',
								//'antitumor',
								//'integrinreceptor',
								//'interleukin',
								//'januskinase',
								'comments',
								'address',
								'doctorid',
								'status',
								'userid' 
						);
 
	
	static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive',
									'P' => 'Pending' 	
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status
	const STATUS_PENDING        = 'P'; //deleted status
	 
	static $DIAGNOSIS_ARRAY = array(
									'U' => 'Ulcerative Colitis', 
									'C' => 'Crohn\'s Disease' 
							); 
					
	static $SEVERITY_ARRAY = array(
								'9' => 'Remission/Extremely Mild - 9 mg', 
								'20' => 'Mild - 20 mg' ,
								'40' => 'Moderate - 40 mg', 
								'60' => 'Severe - 60 mg'							
							);
							
							
		static $SEVERITY_ARRAY2 = array(
								'9'  => 'Budesonide 9 mg', 
								'20' => 'Prednisone 20 mg', 
								'40' => 'Prednisone 40 mg', 
								'60' => 'Prednisone 60 mg' 
							);
							
	 	
	static $PHYSICIANRATING_ARRAY = array(
								'0' => 'Normal', 
								'1' => 'Mild disease', 
								'2' => 'Moderate disease',
								'3' => 'Severe disease' 	
							);						 
	 
	 static $CITY_ARRAY = array(
								'New York' 		=> 'New York', 
								'Washington' 	=> 'Washington', 
								'Greenville' 	=> 'Greenville' ,
								'Chicago' 		=> 'Chicago' ,
								'Boston'		=> 'Boston' 
							);
							
	static $ULCERATIVE_ARRAY = array(
						"U1"  => "E1 - Ulcerative proctitis - Involvement limited to the rectum",
						"U2"  => "E2 - Left sided UC (distal UC) - Involvement limited to a proportion of the colorectum distal to the splenic flexure",
						"U3"  => "E3 - Extensive UC - Involvement extends distal to the hepatic flexure",
						"U4"  => "E4 - Pancolitis - Involvement extends proximal to the hepatic flexure" 
					);
							
	static $CROHNS_AGE_ARRAY = array(
						"16 Years"  				=> "A1 - 16 Years or Younger",
						"Between 17 to 40 Years"  	=> "A2 - Between 17 to 40 Years",
						"Above 40 Years"  			=> "A3 - Above 40 Years"
					);							
							
	static $CROHNS_LOCATION_ARRAY = array(
						"Ileal"  					=> "L1 - Ileal",
						"Colonic"  					=> "L2 - Colonic",
						"Ileocolonic"  				=> "L3 - Ileocolonic",
						"Isolated upper disease"  	=> "L4 - Isolated upper disease"					
					);			
	static $CROHNS_BEHAVIOUR_ARRAY = array(
						"Non stricturing, Non penetrating" 	=> "B1 - Non stricturing, Non penetrating",
						"Stricturing" 						=> "B2 - Stricturing",
						"Penetrating" 						=> "B3 - Penetrating",
						"Perianal disease modifier" 		=> "P - Perianal disease modifier"						
					);
	
	static $AMINO_ARRAY = array(
									"Sulfasalazine" => "Sulfasalazine",
									"Mesalamine" 	=> "Mesalamine",
									"Olsalazine" 	=> "Olsalazine",
									"Balsalazide" 	=> "Balsalazide",
									"None3" 		=> "None" 
								);	
								
	static $IMMUNO_ARRAY = array(
									"Azathioprine" 		=> "Azathioprine",
									"6-mercaptopurine" 	=> "6-mercaptopurine",
									"Methotrexate" 		=> "Methotrexate",
									"Cyclosporine A" 	=> "Cyclosporine A",
									"Tacrolimus" 		=> "Tacrolimus",
									"None4" 			=> "None" 
								);									
	
	static $ANTITUMOR_ARRAY = array(
									"Adalimumab (Humira)" 			=> "Adalimumab (Humira)",
									"Certolizumab pegol (Cimzia)" 	=> "Certolizumab pegol (Cimzia)",
									"Golimumab (Simponi)" 			=> "Golimumab (Simponi)",
									"Infliximab (Remicade)" 		=> "Infliximab (Remicade)",
									 
								);		
	
	static $INTEGRIN_ARRAY = array(
									"Natalizumab (Tysabri)" 	=> "Natalizumab (Tysabri)",
									"Vedolizumab (Entyvio)" 	=> "Vedolizumab (Entyvio)",
									 
								);			
	
	static $INTERLEU_ARRAY = array(
									"Ustekinumab (Stelara)" 	=> "Ustekinumab (Stelara)",
									
								);	
	
	static $JANUS_ARRAY = array(
									"Tofacitinib (Xeljanz)"		=> "Tofacitinib (Xeljanz)",
									 "None5" 					=> "None" 
								);	
								
								
	static $COMPLICATION_ARRAY = array(
									"Joint pain" 			=> "Joint pain",
									"Aphthae ulcer" 		=> "Aphthae ulcer",
									"Anal fissure" 			=> "Anal fissure",
									"Fistula" 				=> "Fistula",
									"Abscess" 				=> "Abscess",
									"Uveitis" 				=> "Uveitis",
									"Erythema Nodosum" 		=> "Erythema Nodosum",
									"Pyoderma gangrenosum" 	=> "Pyoderma gangrenosum" 	
								);	

 static $ABDOMINALMASS_ARRAY = array(
										"-1"	=> "N/A",
										"0"		=> "No",
										"1" 	=> "Possible",
										"2" 	=> "Definite",
										"3" 	=> "Tender mass"	
								);
								
								
	/*public function doctor() {
        return $this->belongsTo('App\models\DoseLogs','doctorid','id');
    }*/
	
	public function linkdoctor() {
        return $this->belongsTo('App\models\Doctor','doctorid','id');
    }
	
	public function linkuser() {
        return $this->belongsTo('App\models\User','userid','id');
    }
	 	
	public function dose() {
        return $this->belongsTo('App\models\Dose','id','patientid')
					->where('status','=','A');
    }
	
	public function dosecompleted() {
        return $this->belongsTo('App\models\Dose','id','patientid')
					->where('completestatus','=','1')->take(1);
    }
	
	public function dosehistory() {
        return $this->hasMany('App\models\DoseLogs','userid','userid') 
					->OrderBy('created_at','asc');
    } 
}
