<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Photographer extends CI_Controller
{
    public function index()
    {                
        //POST
        if($this->input->post('search'))
        {
            //POST VALUE
            $keyword = $this->input->post('keyword');
            $search_type = $this->input->post('the_type');
        }
        else
        {
            //POST VALUE
            $keyword = '';
            $search_type = NULL;
        }
        //FORM
        $data['keyword'] = array(
            'name'=>'keyword',
            'placeholder'=>'Search: Photographer Name, Photographer URL',
            'value'=>$keyword
        );

        $photography_list = $this->m_custom->get_dynamic_option_array('photography', 'all', 'All Photography Type');
        $data['photography_list'] = $photography_list;
        $data['the_type'] = array(
            'name' => 'the_type',
            'id' => 'the_type',
        );
        $data['the_type_selected'] = empty($search_type) ? "" : $search_type;

        if($search_type == 'all') { $search_type = NULL;}
        
        //QUERY PHOTOGRAPHER             
        $data['query_blogger'] = $this->albert_model->read_photographer($keyword, $search_type);
        //TEMPLATE
        $data['page_path_name'] = 'photographer';
        $this->load->view('template/layout', $data);
    }
}