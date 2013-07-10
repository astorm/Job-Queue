<?php
/*
* The MIT License (MIT)
* 
* Copyright (c) 2009-2013 Alan Storm
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/
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

