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

		if ($parent instanceof XenForo_ControllerResponse_View)
		{
			$parent->params['canViewPollResults'] = false;

			if (empty($parent->params['thread']) || (isset($parent->params['thread']['discussion_type']) && $parent->params['thread']['discussion_type'] != 'poll') || empty($parent->params['poll']))
			{
				return $parent;
			}

			$parent->params['canViewPollResults'] = $this->_getPollModel()->canViewPollResults($parent->params['poll']);
		}
		
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
			$inputData = $this->_input->filter(array(
				'hide_poll_results_form' => XenForo_Input::UINT,
				'hide_results' => XenForo_Input::BOOLEAN,
				'until_close' => XenForo_Input::BOOLEAN
			));
			if ($inputData['hide_poll_results_form'])
			{
				unset($inputData['hide_poll_results_form']);
				HidePollResults_Globals::$inputData = $inputData;
			}
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

		if (($parent instanceof XenForo_ControllerResponse_View) && is_array($parent->params['poll']))
		{
			/** @var HidePollResults_Model_Poll $pollModel */
			$pollModel = $this->_getPollModel();

			$parent->params['canViewPollResults'] = $pollModel->canViewPollResults($parent->params['poll']);

			$responseId = $this->_input->filterSingle('poll_response_id', XenForo_Input::UINT);

			if ($responseId && !$parent->params['canViewPollResults'])
			{
				return $this->responseNoPermission();
			}
		}
		
		return $parent;
	}
}

// ******************** FOR IDE AUTO COMPLETE ********************
if (false)
{
	class XFCP_HidePollResults_ControllerPublic_Thread extends XenForo_ControllerPublic_Thread {}
}