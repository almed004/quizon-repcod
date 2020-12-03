<?php

class Testcontroller extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_quiz');
        $this->load->model('Model_question');
        $this->load->model('Model_running_question');
        $this->load->model('Model_student_answer');
        $this->load->model('Model_login');
    }

    public function index() {
        // redirect(base_url('teacher/loginteacher'));
        $this->loginteacher(1);
    }

    public function loginteacher($id) {
        echo "this is where the teacher login n".$id;
    }
}