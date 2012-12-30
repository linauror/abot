<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Submit_model extends CI_Model {
	
    public function __construct()
    {
        parent::__construct();
    }
	
    public function query ($words, $aurthor = '') {
		$result = $this->db->get_where('abot', array('ask' => $words))->row_array();
		if ($result) {
			return array('answer' => $result['answer'], 'author' => $result['author']);
		} else {
			$result = $this->db->get('no_query')->result_array();
			shuffle($result);
			return array('answer' => $result[0]['str'], 'author' => '');
		}
	}
	
	public function learn ($words, $aurthor = '') {
		$result = $this->db->get_where('abot', array('ask' => $words[1]))->row_array();
		if (!$result) {
			$this->db->insert('abot', array('ask' => $words[1], 'answer' => $words['2'], 'author' => $aurthor, 'timeline' => date('Y-m-d H:i:s', mktime())));
			return array('answer' => ':）我已经学会啦，不信你问问看。', 'author' => '');
		} else {
			return array('answer' => ':）已经有人教过我啦，不信你问问看。', 'author' => '');
		}
	}
	
	public function translate ($words, $aurthor = '') {
		$content = file_get_contents('http://translate.google.cn/translate_a/t?client=t&text='.$words.'&hl=zh-CN&sl=auto&tl=en&ie=UTF-8&oe=UTF-8&multires=1&prev=conf&psl=auto&ptl=zh-CN&otf=1&it=sel.394&ssel=3&tsel=6&uptl=en&alttl=zh-CN&sc=1');
		$content_array = explode('"', $content);
		return array('answer' => '翻译：'.preg_replace('/Translation:/', '', $content_array[1]), 'author' => '谷歌');
	}
	
}

/* End of file submit_model.php */
/* Location: ./application/models/submit_model.php */