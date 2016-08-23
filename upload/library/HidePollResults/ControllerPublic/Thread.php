<?php

class HidePollResults_ControllerPublic_Thread extends XFCP_HidePollResults_ControllerPublic_Thread
{
	/**
	 * Displays a thread.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionIndex()
	{
		$parent = parent::actionIndex();
		
		$parent->params['canViewPollResults'] = false;
		
		if (empty($parent->params['thread']) || $parent->params['thread']['discussion_type'] != 'poll' || empty($parent->params['poll']))
		{
			return $parent;
		}
		
		$parent->params['canViewPollResults'] = $this->_getPollModel()->canViewPollResults($parent->params['poll']);
		
		return $parent;
	}	
	
	/**
	 * Edits the poll in this thread.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionPollEdit()
	{
		if ($this->_request->isPost())
		{
			$userId = XenForo_Visitor::getUserId();
			
			$pollExtraInputs[$userId] = $this->_input->filter(array(
				'hide_results' => XenForo_Input::UINT,
				'until_close' => XenForo_Input::UINT,
			));
			
			$data = XenForo_Application::getSimpleCacheData('HidePollResults');
			$data[$userId] = $pollExtraInputs[$userId];
			
			XenForo_Application::setSimpleCacheData('HidePollResults', $data);
			
			return parent::actionPollEdit();
		}
		
		return parent::actionPollEdit();
	}
	
	/**
	 * Views the results of the poll in this thread. Also doubles as viewing voters
	 * for a particular response.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionPollResults()
	{
		$parent = parent::actionPollResults();

		$parent->params['canViewPollResults'] = $this->_getPollModel()->canViewPollResults($parent->params['poll']);
		
		$responseId = $this->_input->filterSingle('poll_response_id', XenForo_Input::UINT);
		
		if ($responseId && !$parent->params['canViewPollResults'])
		{
			return $this->responseNoPermission();
		}
		
		return $parent;
	}
}
