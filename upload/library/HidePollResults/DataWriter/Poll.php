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
		$userId = XenForo_Visitor::getUserId();
		
		$data = XenForo_Application::getSimpleCacheData('HidePollResults');
		
		if (!empty($data[$userId]))
		{
			$this->bulkSet($data[$userId]);
		}
		
		return parent::_preSave();
	}
	
	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		if ($this->isChanged('hide_results') || $this->isChanged('until_close'))
		{		
			$userId = XenForo_Visitor::getUserId();
			
			$data = XenForo_Application::getSimpleCacheData('HidePollResults');
			
			if (!empty($data[$userId]))
			{
				unset($data[$userId]);
				
				XenForo_Application::setSimpleCacheData('HidePollResults', $data);
			}
		}
		
		return parent::_postSave();
	}	
}
