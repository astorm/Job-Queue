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