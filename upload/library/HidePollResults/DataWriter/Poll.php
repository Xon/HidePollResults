<?php

class HidePollResults_DataWriter_Poll extends XFCP_HidePollResults_DataWriter_Poll
{
	/**
	* Gets the fields that are defined for the table. See parent for explanation.
	*
	* @return array
	*/
	protected function _getFields()
	{
		$parent = parent::_getFields();
		
		$parent['xf_poll']['hide_results'] = array('type' => self::TYPE_BOOLEAN, 'default' => 0);
		$parent['xf_poll']['until_close'] =	array('type' => self::TYPE_BOOLEAN,	'default' => 0);
		
		return $parent;
	}
	
	/**
	 * Pre-save handling.
	 */
	protected function _preSave()
	{
		$session = false;
		if (XenForo_Application::isRegistered('session'))
		{
			/** @var $session XenForo_Session */
			$session = XenForo_Application::get('session');
		}

		if ($session && $session->get('hidePollResults'))
		{
			$this->bulkSet($session->get('hidePollResults'));

			$session->remove('hidePollResults');
		}

		return parent::_preSave();
	}
}
