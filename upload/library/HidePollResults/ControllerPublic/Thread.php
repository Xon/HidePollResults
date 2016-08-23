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
			$session = false;
			if (XenForo_Application::isRegistered('session'))
			{
				/** @var $session XenForo_Session */
				$session = XenForo_Application::get('session');
			}

			$hidePollResultsFormShown = $this->_input->filterSingle('hide_poll_results_form', XenForo_Input::UINT);
			if (!$hidePollResultsFormShown || !$session)
			{
				if ($session)
				{
					$session->remove('hidePollResults');
				}

				return parent::actionPollEdit();
			}

			$inputData = $this->_input->filter(array(
				'hide_results' => XenForo_Input::BOOLEAN,
				'until_close' => XenForo_Input::BOOLEAN
			));

			$session->set('hidePollResults', $inputData);
			
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

		if (($parent instanceof XenForo_ControllerResponse_View) && is_array($parent->params['poll']))
		{
			$parent->params['canViewPollResults'] = $this->_getPollModel()->canViewPollResults($parent->params['poll']);

			$responseId = $this->_input->filterSingle('poll_response_id', XenForo_Input::UINT);

			if ($responseId && !$parent->params['canViewPollResults'])
			{
				return $this->responseNoPermission();
			}
		}
		
		return $parent;
	}
}
