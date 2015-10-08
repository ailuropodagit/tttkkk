<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blogger extends CI_Controller
{
    public function index()
    {
        //POST
        if($this->input->post('search'))
        {
            //POST VALUE
            $keyword = $this->input->post('keyword');
        }
        else
        {
            //POST VALUE
            $keyword = '';
        }
        //FORM
        $data['keyword'] = array(
            'name'=>'keyword',
            'placeholder'=>'Search: Blogger Name, Blog URL',
            'value'=>$keyword
        );
        //QUERY BLOGGER
        $data['query_blogger'] = $this->albert_model->read_blogger($keyword);
        //TEMPLATE
        $data['page_path_name'] = 'blogger';
        $this->load->view('template/layout', $data);
    }
}