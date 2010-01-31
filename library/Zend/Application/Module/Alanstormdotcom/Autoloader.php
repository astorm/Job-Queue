<?php
	class Zend_Application_Module_Alanstormdotcom_Autoloader extends Zend_Application_Module_Autoloader
	{
		/**
		 * Initialize default resource types for module resource classes
		 * 
		 * @return void
		 */
		public function initDefaultResourceTypes()
		{
			parent::initDefaultResourceTypes();
			$basePath = $this->getBasePath();
			$this->addResourceTypes(array(		
				'job'			=> array(
					'namespace'	=> 'Job',
					'path'		=> 'Alanstormdotcom/Job'
				)
			));
		}	
	}