<?php

class HidePollResults_ControllerPublic_Forum extends XFCP_HidePollResults_ControllerPublic_Forum
{
	/**
	 * Inserts a new thread into this forum.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionAddThread()
	{	
		$userId = XenForo_Visitor::getUserId();
		
		$pollExtraInputs[$userId] = $this->_input->filter(array(
			'hide_results' => XenForo_Input::UINT,
			'until_close' => XenForo_Input::UINT,
		));
		
		$data = XenForo_Application::getSimpleCacheData('HidePollResults');
		$data[$userId] = $pollExtraInputs[$userId];
		
		XenForo_Application::setSimpleCacheData('HidePollResults', $data);
			
		return parent::actionAddThread();
	}
}