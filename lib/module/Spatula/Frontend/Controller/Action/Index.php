<?php
class Frontend_Controller_Action_Index extends Frontend_Controller_Action_Abstract
{
    public function execute() {
	
		$jsUrl = ASSETS_URL . '/skin/js/';
		$cssUrl = ASSETS_URL . '/skin/css/';
		
//		$this->getTemplate()->addScript('UNIQUEID',  'relative path');
//		$this->getTemplate()->addLink('UNIQUEID',  'relative path');
		Spatula_Db_Init::getInstance()->init();
		$bean = R::dispense('media');
		$bean->filename = 'test';
		$bean->mime_type = 'video/mp4';
		$model = new Model_Media();
		$bean2 = $model->dispense();
		$bean2->size = '12334';
		$bean2->store();
		R::tag( $bean, array('topsecret','mi6') );
		$id = R::store($bean);
		
		echo $id;
    }
}