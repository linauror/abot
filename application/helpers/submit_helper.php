<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 根据输入内容判断使用哪个函数处理
 *
 * @access	public
 * @param	$words string
 * @param   $author string
 * @return string
 */
if (!function_exists('submit_go')){
    function submit_go ($words, $author = '') {
    	$learn = preg_split('/问：|答：/', $words); //判断是否为教学
    	$translate = preg_split('/翻译：/', $words); //判断是否为翻译
    	$return = array();
    	if (3 == count($learn)) {
    		$return['operate'] = 'learn';
    		$return['content'] = $learn;
    		$return['author'] = $author;
    	} elseif (2 == count($translate)) {
    		$return['operate'] = 'translate';
    		$return['content'] = $translate[1];
			$return['author'] = $author;		
    	} else {
    		$return['operate'] = 'query';
    		$return['content'] = $words;
    		$return['author'] = $author;
    	}
    	return $return;
    }
}

// --------------------------------------------------------------------

/* End of file submit_helper.php */
/* Location: ./application/helpers/submit_helper.php */

