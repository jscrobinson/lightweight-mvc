<?php
abstract class Frontend_Controller_Action_Abstract extends Spatula_Controller_Action_Abstract
{
    protected function _getMethodName($task) {
        $taskMethod = 'execute' . Spatula_Util::camelize($task, true);
        return $taskMethod;
    }
}