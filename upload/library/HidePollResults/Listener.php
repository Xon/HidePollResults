<?php

class HidePollResults_Listener
{
	public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		switch ($hookName)
		{
			case 'thread_create':
			
				$replace = $template->create('thread_create_hide_poll_results', $template->getParams());
				$contents = preg_replace('/<ul id="ctrl_poll_close_Disabler">(.*?<\/ul>)/is', "\\0 $replace", $contents);

				break;
		}
	}
	
	public static function templatePostRender($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template)
	{
		switch ($templateName)
		{
			case 'thread_poll_edit':

				$params = $template->getParams();
				
				$replace = $template->create('thread_create_hide_poll_results', $params);
				if ($params['poll']['close_date'])
				{
					$content = preg_replace('/<label for="ctrl_close_date">(.*?<\/label>)/is', "\\0 $replace", $content);
				}
				else
				{
					$content = preg_replace('/<ul id="ctrl_close_Disabler">(.*?<\/ul>)/is', "\\0 $replace", $content);
				}
				
				break;
				
			case 'thread_view':
			case 'thread_poll_vote':
			case 'thread_poll_results':
			
				$params = $template->getParams();
				
				if (!$params['canViewPollResults'])
				{	
					$regEx = array(
						'hideResults' => array(
							'pattern' => '/<td[^>]*>.*?<\/td>/is',
							'replace' => '',
							'limit' => -1
						),
						'hideResultsButton' => array(
							'pattern' => '/<input type="button" (.*?\/>)/is',
							'replace' => '',
							'limit' => 1
						),
						'hideResultsButtonJs' => array(
							'pattern' => '/<noscript><a (.*?<\/noscript>)/is',
							'replace' => '',
							'limit' => -1
						)
					);
					
					if ($params['poll']['close_date'] && $params['poll']['hide_results'] && $params['poll']['until_close'])
					{
						$params['closeDate'] = XenForo_Template_Helper_Core::helperDateTimeHtml($params['poll']['close_date']);
						
						$regEx['appendNote'] = array(
							'pattern' => '/<div class="question">(.*?<\/div>)/is',
							'replace' => '\\0 <div class="pollNotes closeDate muted">' . new XenForo_Phrase('the_results_of_this_poll_are_hidden_until_x', array('time' => $params['closeDate']), false) . '</div>',
							'limit' => 1						
						);
					}
					elseif ($params['poll']['hide_results'])
					{
						$regEx['appendNote'] = array(
							'pattern' => '/<div class="question">(.*?<\/div>)/is',
							'replace' => '\\0 <div class="pollNotes closeDate muted">' . new XenForo_Phrase('the_results_of_this_poll_are_hidden_until_manual') . '</div>',
							'limit' => 1						
						);						
					}
					
					foreach ($regEx AS $item)
					{
						$content = preg_replace($item['pattern'], $item['replace'], $content, $item['limit']);
					}
				}
				
				break;
		}
	}
	
	public static function extendWriters($class, array &$extend)
	{
		switch ($class)
		{
			case 'XenForo_DataWriter_Poll':
			
				$extend[] = 'HidePollResults_DataWriter_Poll';
				break;
		}
	}

	public static function extendControllers($class, array &$extend)
	{
		switch ($class)
		{
			case 'XenForo_ControllerPublic_Forum':
			
				$extend[] = 'HidePollResults_ControllerPublic_Forum';
				break;
				
			case 'XenForo_ControllerPublic_Thread':
			
				$extend[] = 'HidePollResults_ControllerPublic_Thread';
				break;
		}
	}
	
	public static function extendModels($class, array &$extend)
	{
		switch ($class)
		{
			case 'XenForo_Model_Poll':
			
				$extend[] = 'HidePollResults_Model_Poll';
				break;
		}
	}
}