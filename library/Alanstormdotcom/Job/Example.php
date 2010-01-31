<?php
	class Alanstormdotcom_Job_Example extends Alanstormdotcom_Job_Base
	{
		public function runJob()
		{
			echo '<p>I am running the job ' .
			__CLASS__ .
			' ' .
			'with the following params </p>';
			var_dump($this->params);
			
			echo '<p>'.date('l jS \of F Y h:i:s A',$this->params['time to show differences']).'</p>';
			echo '<p>I will now randomly decide whether or not I failed or succeeded</p>';
			
			$fail = true;
			if(rand(1,2) % 2 == 0)
			{
				$fail = false;
			}
			
			if($fail)
			{
				echo '<p>I failed, and will need to run again!';
				return false;
			}
			else
			{
				echo '<p>I was successful, and will be expunged from the queue!';
				return true;			
			}
		}
	}