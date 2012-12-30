<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index()
    {
        $this->load->view('index');
    }
    
    public function submit(){
    	$this->load->database();
    	$words = $this->input->get('words');
    	$author = $this->input->get('author');
    	$words = str_replace(' ', '', $words);
    	if ($words) {
    		$this->load->model('submit_model');
    		$this->load->helper('submit');
    		$submit_back = submit_go($words, $author);
    		$result = $this->submit_model->$submit_back['operate']($submit_back['content'], $submit_back['author']);
    	}
    	exit(json_encode($result));
    }
    
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */
