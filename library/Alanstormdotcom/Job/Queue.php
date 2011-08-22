<?php
class Alanstormdotcom_Job_Queue extends Zend_Queue
{	
    /**
    * Adds a job to the queue
    *
    * @param string $type //the classname of the job we're adding
    * @param array $job_params //params to be passed to the job
    * @param array $prerequisite_queue_ids //a list of job ids that must complete before we can run
    */	
    public function addJob($type,$job_params,$prerequisite_queue_ids=false)
    {
        $prerequisite_queue_ids = $prerequisite_queue_ids ? $prerequisite_queue_ids : array();
        $job = new $type;
        $job_params['prerequisite_queue_ids'] = 
        array_key_exists('prerequisite_queue_ids',$job->getParams()) ? $job_params['prerequisite_queue_ids'] : array();
        
        $job_params['prerequisite_queue_ids'] = array_merge($job_params['prerequisite_queue_ids'], $prerequisite_queue_ids);
        call_user_func(array($job,'setParams'),$job_params);
        return $this->send(serialize($job));
    }

    /**
    * Gets a general db handler
    *
    * Needed for the prerequisite functionality.  
    * Requires you bootstrap in a dbhandled named db
    */
    private function getDbHandler()
    {
        $db = Zend_Registry::get(Bootstrap::DB_PREREQUISITES_NAME);			
        if(!$db)
        {
            throw new Exception('No '.Bootstrap::DB_PREREQUISITES_NAME.' resource in registry');
        }
        return $db;
    }
    
    /**
    * Takes a job and determines if the required tasks have completed
    */		
    public function requiredTasksComplete(Alanstormdotcom_Job_Base $job)
    {		
        $params = $job->getParams();
        if(!array_key_exists('prerequisite_queue_ids',$params) ||
        count($params['prerequisite_queue_ids']) == 0)
        {
            return true;
        }			
        
        $placeholders 	= array();
        $params 		= array();
        
        foreach($params['prerequisite_queue_ids'] as $id)
        {
            $placeholders[] = 'message_id = ?';
            $params[]		= $id;
        }
        $placeholders = join(' OR ', $placeholders);
        
        $db = $this->getDbHandler();
        $result = $db->fetchAll('SELECT count(*) as count FROM message WHERE '.$placeholders ,$params);			
        if($result[0]['count'] > 0)
        {
            echo 'Could not run '.get_class($job).'; prerequisite_queue_ids still exists in message queue'."\n";
            return false;
        }
        return true;
    }

    /**
    * Runs a job with a specific ID
    *
    * Mainly used when you're debugging a job
    * @param int $job_id
    * @return type $var_name
    */
    public function runJobId($job_id, $with_output=false)
    {
        //$message->message_id
        $queue_size = count($this);
        $job = false;
        for($i=0;$i<count($this);$i++)
        {
            $messages = $this->receive();
            foreach($messages as $message)
            {		
                if($message->message_id == $job_id)
                {
                    $job = $message;
                }
            }				
        }				
        if($job)
        {
            if($with_output) 
            {
                echo 'Attemping to Run: ', get_class($job), "\n";
            }
            $this->runJob($job);
            return true;
        }
        else
        {
            if($with_output)
            {
                echo 'Could not find a job with the message id of '.$job_id,"\n";
            }
            return false;
        }
    }
    
    /**
    * Takes a message object and runs it if its a job
    * @param type $message
    */		
    private function runJob($message)
    {
        $object = unserialize($message->body);
        if($this->requiredTasksComplete($object))
        {
            if( call_user_func(array($object,'runJob'), $this ) )
            {
                $this->deleteMessage($message);
            }			
        }
        else{
            echo "Could not run ".get_class($object).", required jobs not complete \n";
        }							
    }
    
    /**
    * Public entry point.  Receive a message and run a job
    */		
    public function runJobs()
    {
        $messages = $this->receive();	    
        foreach($messages as $message)
        {
            $this->runJob($message);
        }
    
    }
}