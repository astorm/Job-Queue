<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	* Initilizes autoloader so Alanstormdotcom classes from the library load
	*/
    protected function _initAutoload()
    {
    	$autoloader = new Zend_Application_Module_Alanstormdotcom_Autoloader(array(
            'namespace' => 'Alanstormdotcom',
            'basePath'  => dirname(__FILE__) . '/../library',
        ));
    	
        return $autoloader;
    }

	/**
	* Tries to guess the path to the ZendFramework files based on include path
	*/    
    private function guessFrameworkIncludePath()
    {
	    $paths = explode(':',get_include_path());
	    foreach($paths as $path)
	    {
	    	if(strpos($path,'ZendFramework') !== false)
	    	{
	    		return $path;
	    	}
	    }
    	#return '/path/to/ZendFramework';
    	throw new Exception('Could not Find Path to ZendFramework');
    }

	/**
	* Checks for existence of message queue tables
	*
	* Should probably examine the contents as well, but this
	* will do for now
	* @param stdClass $db
	* @return boolean
	*/    
    private function hasQueueTables($db)
    {
		try{
			$db->describeTable('message');
		}
		catch (Exception $e)
		{
			return false;
		}    
		
		try{
			$db->describeTable('queue');
		}
		catch (Exception $e)
		{
			return false;
		}		
		
		return true;
    }
    
    private function getMysqlFileContents($path)
    {
		$path = $this->guessFrameworkIncludePath();
		$full_path = $path . 
		'/' . 
		'Zend/Queue/Adapter/Db/queue.sql';
		
		if(file_exists($full_path))
		{
			return file_get_contents($full_path);
		}
		
		$full_path = $path . 
		'/' . 
		'Zend/Queue/Adapter/Db/mysql.sql';	
		if(file_exists($full_path))
		{
			return file_get_contents($full_path);
		}
		
		//if we're still here we couldn't find a file
		throw new Exception('Could not find sql file to create message queue');
    }
    
	/**
	* Creates the message queue mysql tables if they don't exist already
	*
	* This can be removed/commented out if the tables are already there
	*/    
	protected function _initMessageQueueTables()
	{					   		
		$sql = $this->getMysqlFileContents($path);

		if ($this->hasPluginResource("db")) {
			$dbResource = $this->getPluginResource("db");
			$db = $dbResource->getDbAdapter();
			if(!$this->hasQueueTables($db))
			{
				$db->query($sql);
			}
		}
		else
		{
			throw new Exception('No db Plugin Resource');
		}
	}

	/**
	* Inits a db resource handler.  Needed for prerequisites
	*/    
	const DB_PREREQUISITES_NAME = 'db';
    protected function _initDb()
    {
		if ($this->hasPluginResource("db")) {
			$dbResource = $this->getPluginResource("db");
			$db = $dbResource->getDbAdapter();
			Zend_Registry::set(self::DB_PREREQUISITES_NAME, $db);			
		}
		else
		{
			throw new Exception('No db Plugin Resource');
		}    
    }
    
	/**
	* Creates the job queue with a mysql message queue adapter, using
	* using the default database driver from the application config
	*/	
	const NAME_ORDERQUEUE = 'job_queue';
	protected function _initOrdersQueue()
	{	
		$config = $this->getOptions();		
		$options = array(
			'name'          => self::NAME_ORDERQUEUE,
			'driverOptions' => array(
				'host'      => $config['resources']['db']['params']['host'],
				'port'      => '3306',
				'username'  => $config['resources']['db']['params']['username'],
				'password'  => $config['resources']['db']['params']['password'],
				'dbname'    => $config['resources']['db']['params']['dbname'],
				'type'      => 'pdo_mysql'
			)
		);
		
		$queue = new Alanstormdotcom_Job_Queue('Db', $options);		
		Zend_Registry::set(self::NAME_ORDERQUEUE,$queue);
	}	
}