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
		if (HidePollResults_Globals::$inputData !== null)
		{
			$this->bulkSet(HidePollResults_Globals::$inputData);

			HidePollResults_Globals::$inputData = null;
		}

		parent::_preSave();
	}
}

// ******************** FOR IDE AUTO COMPLETE ********************
if (false)
{
	class XFCP_HidePollResults_DataWriter_Poll extends XenForo_DataWriter_Poll {}
}