<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Notification Extends BaseModel {
		
 protected $table = 'notifications';
    protected $fillable = array(
								'id',
								'user_id', 								
								'title',
								'message',
								'read',
								'active',
								'deleted' 	
						); 	
	
	 static $STATUS_ARRAY = array(
									'A' => 'Active', 
									'D' => 'Inactive' 
							);
    const STATUS_ACTIVE         = 'A'; //added status
    const STATUS_INACTIVE       = 'D'; //deleted status 	
	
	
//class Notification extends  \Eloquent{

   /*
    public static $notificationPush=1;
    public static $dataPush=2;
    const ANDROID=1;
    const IOS=2;

    private $_type;
    private $_data;
    private $_token;
    private $_deviceType;
    private $_toUser;

    protected $guarded=array('id');
    protected $fillable=array('id','user_id','text','title','message','read','active','deleted');
    protected $table = 'notifications';
    public $timestamps=false;
    private $_saveSeparate=false;
    private $_saveToDB=true;
    private $_uri=null;
    private $_metaData=[];

    function __construct(){
        $this->_type=self::$dataPush;
    }

    
    public function getType()
    {
        return $this->_type;
    }

    
    public function setType($type)
    {
        $this->_type = $type;
    }

     
    public function getData()
    {
        return $this->_data;
    }

     
    public function setData($data)
    {
        $this->_data = $data;
    }

    
    public function getToken()
    {
        return $this->_token;
    }

     
    public function setToken($token)
    {
        $this->_token = $token;
    }

     
    public function getDeviceType()
    {
        return $this->_deviceType;
    }

    public function setToUser($userId){
        $this->_toUser=$userId;
    }

    public function getToUser(){
        return $this->_toUser;
    }


     
    public function setDeviceType($devicePlatform)
    {
        if(preg_match("/andi/i",$devicePlatform)) {
            $this->_deviceType =Self::ANDROID;
        }else if(preg_match("/ios/i",$devicePlatform)){
            $this->_deviceType=Self::IOS;
        }
    }

    
    public function isSaveSeparate()
    {
        return $this->_saveSeparate;
    }

    
    public function setSaveSeparate($saveSeparate)
    {
        $this->_saveSeparate = $saveSeparate;
    }

    
    public function isSaveToDB()
    {
        return $this->_saveToDB;
    }

   
    public function setSaveToDB($saveToDB)
    {
        $this->_saveToDB = $saveToDB;
    }


    public function setUri($uri){
        $this->_uri=$uri;
    }

    public function getUri(){
        return $this->_uri;
    }


    public function setMetaData(array $metaData=[]){
        $this->_metaData=$metaData;
    }

    public function getMetaData(){
        return $this->_metaData;
    }


	*/





}