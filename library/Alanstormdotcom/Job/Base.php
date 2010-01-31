<?php
	/**
	* Our base jobs.  All jobs inherit from this
	*/
	abstract class Alanstormdotcom_Job_Base
	{
		/**
		* An array of paramaters passed in at job creation time
		*
		* Special Params:
		*
		* @var array $params
		*/	
		protected $params=array();

		/**
		* Our main entry point
		*
		* When a job is pulled from the queue, this is the method that's run
		* If the method returns true, the job is removed from the queue, if it
		* returns false, the job is left in the queue to be run again
		* @return boolean
		*/		
		abstract public function runJob();

		/**
		* Sets our paramater array
		* @param array $params
		*/		
		public function setParams($params)
		{
			$this->params = $params;
			return $this;
		}

		public function getParams()
		{
			return $this->params;
		}
		
		/**
		* Called by the job queue
		*
		* Checks is required tasks are complete, and then runs the job
		* @return mixed
		*/		
		public function run($queue)
		{			
			if($queue->requireTasksComplete($this))
			{
				return $this->runJob();
			}
			else
			{
				echo "Could not run ".__CLASS__."; Precursor jobs have not run";
				return false;
			}			
		}
		
	}