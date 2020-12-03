<?php

class controller_test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_student_answer');
    }

    public function index()
    {
        $this->load->view("view_student_homepage_test");
    }

    public function mcsa_test()
    {
        //check if student has already answered
        // 1. retrieve last row from tbl_question_running
        // 2. select its id
        // 3. select tbl_student_answer where id_question_start = (2).id and id_student="dummy001"
        // 4. if yes (row<>0), redirect to homepage, if no, show mcsa
        // $studentanswer = 1; //check dari database;
        //check dari database apakah sudah menjawab belum
        // if ($studentanswer === 1) {
        //     redirect(base_url('controller_test'));
        // } else {
            $this->load->view('view_test_mcsa');
        // }
    }

    public function load_question_test()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $theoption = array(
            'questionstart' => '1',
            'questionid' => '2',
            'studentanswer' => '0' //terupdate setelah student menjawab 
        );
        $quest = json_encode($theoption);


        echo "data: {$quest}\n\n";

        flush();
    }
}
