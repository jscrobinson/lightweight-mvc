<?php
class Spatula_Controller_Action_404 extends Spatula_Controller_Action_Abstract
{
    public function execute() {
        header('HTTP/1.0 404 Not Found');
		$this->setTemplateName('404');
    }
}