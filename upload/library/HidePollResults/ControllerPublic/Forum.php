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

			return parent::actionAddThread();
		}

		$inputData = $this->_input->filter(array(
			'hide_results' => XenForo_Input::BOOLEAN,
			'until_close' => XenForo_Input::BOOLEAN
		));

		$session->set('hidePollResults', $inputData);
			
		return parent::actionAddThread();
	}
}