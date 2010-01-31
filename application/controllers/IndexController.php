<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

	public function testAction()
	{
		$this->view->message = '<p>This is a test action</p>';
	}
	
	public function addjobsAction()
	{
		$queue = Zend_Registry::get(Bootstrap::NAME_ORDERQUEUE);
		
		$params = array('example'=>'param');
		for($i=0;$i<10;$i++)
		{
			$params['requence to show differences']=$i;		
			$params['time to show differences']=time();
			$queue->addJob('Alanstormdotcom_Job_Example',
			$params,
			false);
		}
		
		
	}
	
	public function runjobsAction()
	{
		$queue = Zend_Registry::get(Bootstrap::NAME_ORDERQUEUE);	
		$queue->runJobs();
	}
}

