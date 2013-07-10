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