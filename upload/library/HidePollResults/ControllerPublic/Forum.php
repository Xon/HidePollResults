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

		return parent::actionAddThread();
	}
}


// ******************** FOR IDE AUTO COMPLETE ********************
if (false)
{
	class XFCP_HidePollResults_ControllerPublic_Forum extends XenForo_ControllerPublic_Forum {}
}