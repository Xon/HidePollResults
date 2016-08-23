<?php

class HidePollResults_Model_Poll extends XFCP_HidePollResults_Model_Poll
{
	public function canViewPollResults(array $poll, &$errorPhraseKey = '', array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);
		
		if (!$poll['hide_results'])
		{
			return true;
		}
		
		if ($poll['hide_results'] && $poll['close_date'] && $poll['until_close'] && ($poll['close_date'] < XenForo_Application::$time))
		{
			return true;
		}
		
		if (XenForo_Permission::hasPermission($viewingUser['permissions'], 'forum', 'bypassHiddenPollResults'))
		{
			return true;
		}
		
		$thread = $this->getModelFromCache('XenForo_Model_Thread')->getThreadById($poll['content_id']);
		
		if ($thread['user_id'] == $viewingUser['user_id'] && XenForo_Permission::hasPermission($viewingUser['permissions'], 'forum', 'bypassHiddenPollResultOwn'))
		{
			return true;
		}
		
		$errorPhraseKey = '';
		return false;
	}
}
