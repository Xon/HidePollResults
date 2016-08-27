<?php

class HidePollResults_Listener
{
	public static function extendPollModel($class, array &$extend)
	{
		$extend[] = 'HidePollResults_Model_Poll';
	}

	public static function extendPollWriter($class, array &$extend)
	{
		$extend[] = 'HidePollResults_DataWriter_Poll';
	}

	public static function extendForumController($class, array &$extend)
	{
		$extend[] = 'HidePollResults_ControllerPublic_Forum';
	}

	public static function extendThreadController($class, array &$extend)
	{
		$extend[] = 'HidePollResults_ControllerPublic_Thread';
	}
}