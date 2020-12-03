<?php

class Studentshome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_student_answer');
        $this->load->model('Model_question');
        $this->load->model('Model_quiz');
        $this->load->model('Model_running_question');
    }

    public function index()
    {
        if ($this->session->userdata('email') != '' && $this->session->userdata('pin') != '') {
            $email = $this->session->userdata('email');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($email);
            $this->load->view('view_student_homepage', $data);
        } else {

            $this->loginstudent();
        }
    }

    public function modes()
    {
        $this->load->view('view_modes');
    }


    public function home()
    {
        //quiz at home
        //display all quizzes from tbl_quiz
        $data['view_quiz_home'] = $this->Model_quiz->mf_list_quiz_home();
        $this->load->view('view_students_at_home', $data);
    }

    public function home_question($quiz_id)
    {
        //clear the session
        $this->session->unset_userdata('studentanswer');
        $this->session->unset_userdata('answertext');

        $data['view_question_home'] = $this->Model_quiz->mf_list_question_home($quiz_id);

        $this->load->view('view_header_students');
        $this->load->view('view_students_at_home_question', $data);
    }

    public function startquizhome($quiz_id)
    {
        // check and generate PIN 
        // save the PIN to database (because it will be used by student)
        // add to session: quiz_id, quiz_title, and PIN
        // open question page

        $quiz_id = $quiz_id;
        $pin = $this->Model_running_question->mf_check_pin();

        $newdata = array(
            'quiz_id'  => $quiz_id,
            'pin'     => $pin,
            'questionrun' => 1
        );

        $this->session->set_userdata($newdata);

        redirect(base_url('students/home_question/' . $quiz_id));
    }

    public function loginstudent()
    {
        $data['title'] = 'Login to QuizAlpha';
        $this->load->view('view_login_student_ver_3', $data);
    }

    public function processlogin()
    {
        //validation process (tbd)

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run()) {
            //true
            //authentication process
            $email =  $this->input->post('email');
            $password = $this->input->post('password');
            if ($this->Model_student_answer->mf_check_login($email, $password)) {
                $session_data = array(
                    'email' => $email
                );
                $this->session->set_userdata($session_data);

                redirect(base_url() . 'students/processpin_alt');
            } else {
                $this->session->set_flashdata('error', 'Invalid Email and Password');
                redirect(base_url() . 'students/loginstudent');
            }
        } else {
            //false
            $this->loginstudent();
        }
    }

    public function processpin_alt()
    {
        //retrieve last pin (active question from tbl_question_running)
        $pin = $this->Model_student_answer->mf_retrieve_last_pin();

        //set session userdata ('pin') 
        $session_data = array(
            'pin' => $pin
        );
        $this->session->set_userdata($session_data);

        //redirect to question page (students)
        redirect(base_url() . 'students/modes');
    }
    public function processpin()
    {

        $this->form_validation->set_rules('pin', 'Pin', 'required');
        if ($this->form_validation->run()) {
            $pin = $this->input->post('pin');
            if ($this->Model_student_answer->mf_check_pin($pin)) {
                $session_data = array(
                    'pin' => $pin
                );
                $this->session->set_userdata($session_data);
                redirect(base_url() . 'students');
            } else {
                $this->session->set_flashdata('error', 'Invalid PIN');
                redirect(base_url() . 'students/enterpin');
            }
        } else {
            $this->enterpin();
        }
    }


    public function processregister()
    {
        //authentication process (tbd)
        //save the data to tbl_user
        //username, email, password, confirm password
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('passwordconfirm', 'Password Confirm', 'trim|required');
        if ($this->form_validation->run()) {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $passwordconfirm = $this->input->post('passwordconfirm');
            if ($passwordconfirm = $password) {
                //save register data to database
                $this->Model_student_answer->mf_save_registration_data($username, $email, $password);
                redirect(base_url('students/loginstudent'));
            } else {
                $this->session->set_flashdata('error', 'Please confirm password');
                redirect(base_url() . 'students/registerstudent');
            }
        } else {
            $this->registerstudent();
        }

        //konfirmasi via email????
    }

    public function registerstudent()
    {
        $this->load->view('view_register_student');
    }

    public function enterpin()
    {

        if ($this->session->userdata('email') != '') {
            $email = $this->session->userdata('email');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($email);
            $this->load->view('view_enter_pin', $data);
        } else {

            redirect(base_url() . 'students/loginstudent');
        }
    }

    public function startquestionmcsa()
    {
        if ($this->input->post('questionid') !== null) {
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
        } else {
            $id = $_SESSION['questionid'];
        }

        $_SESSION['idanswer'] = '';
        $_SESSION['studentanswer'] = '';

        // echo $_SESSION['questionrun'];
        $this->Model_student_answer->mf_new_pin($id);
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);
        $this->questionmcsa();
    }
    public function startquestionmcsa_next()
    {
        $orderquestionid = $this->input->post('questionid');
        $quizid = $this->input->post('quizid');

        // echo $orderquestionid;
        // echo $quizid;


        // $questionid = $this->Model_question->mf_find_question($orderquestionid, $quizid);
        // $_SESSION['questionid'] = $questionid;
        $questionid = $_SESSION['questionid'];



        // $this->questionshortanswer();
        $_SESSION['idanswer'] = '';
        $_SESSION['studentanswer'] = '';

        //insert new question into tbl_question_running
        $this->Model_student_answer->mf_new_pin($questionid);

        //insert new answer into tbl_student_answer
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($questionid);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);

        $question_data = $this->Model_student_answer->mf_load_question($questionid);
        // echo json_encode($question_data);
        // $questionid = json_encode($question_data[0]->question_id);
        $questionid = $question_data[0]->question_id;
        $quizid = json_encode($question_data[0]->quiz_id);
        $questionanswer = $question_data[0]->question_answer;
        $questiontext = $question_data[0]->question_text;
        $questiontype = $question_data[0]->question_type;
        // echo $questionid;
        $option_data = $this->Model_student_answer->mf_load_option($questionid);
        // echo "option_data:".json_encode($option_data);
        // $qu="8";
        // echo (int)$qu;
        // echo $questionid[1];
        // echo $questionid;
        // echo (int)$questionid[1];
        // echo settype($questionid, 'integer');
        // echo gettype((int)$questionid);
        // echo (int)$questionid;
        // echo intval($questionid)+1;


        $questionoption1 = $option_data[0]->option_text;
        $questionoption2 = $option_data[1]->option_text;
        $questionoption3 = $option_data[2]->option_text;
        $questionoption4 = $option_data[3]->option_text;
        // $questionoption1 = json_encode($option_data[0]->option_text);
        // $questionoption2 = json_encode($option_data[1]->option_text);
        // $questionoption3 = json_encode($option_data[2]->option_text);
        // $questionoption4 = json_encode($option_data[3]->option_text);

        // $questionidstart = $this->input->get('idstart');
        // $questionidstart = $this->Model_student_answer->mf_get_idstart_home($questionid[1]);


        $visitedNo = $this->Model_student_answer->mf_visitedNo($this->session->userdata('pin'));      
        $answeredCorrect = $this->Model_student_answer->mf_answeredCorrect($this->session->userdata('pin'));
        $answeredInCorrect = $this->Model_student_answer->mf_answeredIncorrect($this->session->userdata('pin'));

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questiontype' => $questiontype,
            'questionadds' => "<pre><code class='language-html'>" . $question_data[0]->question_adds . "</code></pre>",
            'questionimage' => $question_data[0]->question_image,
            'option1' => $questionoption1,
            'option2' => $questionoption2,
            'option3' => $questionoption3,
            'option4' => $questionoption4,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer,
            'visitedNo' => $visitedNo,
            'answeredCorrect' => $answeredCorrect,
            'answeredIncorrect' => $answeredInCorrect
        );

        //insert into tbl_student_answer new question data
        // variabel: idstart and ids(from session)
        // $this->Model_student_answer->mf_new_question_begin($questionidstart);
        // echo json_encode($thequestion);
        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_mcsa_home', $thequestion);
        // }
    }


    public function questionmcsa()
    {
        if ($this->input->post('questionid') !== null) {
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
        } else {
            $id = $_SESSION['questionid'];
        }

        $student_answer = $this->Model_student_answer->mf_check_answer_home($id);

        $question_data = $this->Model_student_answer->mf_load_question($id);

        $questionid = json_encode($question_data[0]->question_id);
        $quizid = json_encode($question_data[0]->quiz_id);
        $questionanswer = $question_data[0]->question_answer;
        $questiontext = $question_data[0]->question_text;

        $option_data = $this->Model_student_answer->mf_load_option($id);

        $questionoption1 = $option_data[0]->option_text;
        $questionoption2 = $option_data[1]->option_text;
        $questionoption3 = $option_data[2]->option_text;
        $questionoption4 = $option_data[3]->option_text;
        // $questionoption1 = json_encode($option_data[0]->option_text);
        // $questionoption2 = json_encode($option_data[1]->option_text);
        // $questionoption3 = json_encode($option_data[2]->option_text);
        // $questionoption4 = json_encode($option_data[3]->option_text);

        // $questionidstart = $this->input->get('idstart');
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questionadds' => "<pre><code class='language-html'>" . $question_data[0]->question_adds . "</code></pre>",
            'questionimage' => $question_data[0]->question_image,
            'option1' => $questionoption1,
            'option2' => $questionoption2,
            'option3' => $questionoption3,
            'option4' => $questionoption4,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer
        );

        //insert into tbl_student_answer new question data
        // variabel: idstart and ids(from session)
        // $this->Model_student_answer->mf_new_question_begin($questionidstart);
        // echo json_encode($thequestion);
        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_mcsa_home', $thequestion);
        // }
    }

    public function questionmcma($id)
    {

        $this->Model_student_answer->mf_new_pin($id);

        $student_answer = $this->Model_student_answer->mf_check_answer_home($id);


        if ($student_answer === 1) {
            redirect(base_url('students/home'));
        } else {
            //load question and options from tbl_question (identify the question_id first)

            $question_data = $this->Model_student_answer->mf_load_question($id);

            $questionid = json_encode($question_data[0]->question_id);
            $questiontext = json_encode($question_data[0]->question_text);

            $option_data = $this->Model_student_answer->mf_load_option($id);

            $questionoption1 = json_encode($option_data[0]->option_text);
            $questionoption2 = json_encode($option_data[1]->option_text);
            $questionoption3 = json_encode($option_data[2]->option_text);
            $questionoption4 = json_encode($option_data[3]->option_text);
            $questionoption5 = json_encode($option_data[4]->option_text);
            $questionoption6 = json_encode($option_data[5]->option_text);

            $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);

            $thequestion = array(
                'questionid' => $questionid,
                'questiontext' => $questiontext,
                'option1' => $questionoption1,
                'option2' => $questionoption2,
                'option3' => $questionoption3,
                'option4' => $questionoption4,
                'option5' => $questionoption5,
                'option6' => $questionoption6,
                'idstart' => $questionidstart
            );
            $this->load->view('view_question_mcma_home', $thequestion);
        }
    }

    public function startquestionsorting_next()
    {
        $orderquestionid = $this->input->post('questionid');
        $quizid = $this->input->post('quizid');

        // echo $orderquestionid;
        // echo $quizid;


        // $questionid = $this->Model_question->mf_find_question($orderquestionid, $quizid);
        // $_SESSION['questionid'] = $questionid;
        $questionid = $_SESSION['questionid'];



        // $this->questionshortanswer();
        $_SESSION['idanswer'] = '';
        $_SESSION['studentanswer'] = '';

        //insert new question into tbl_question_running
        $this->Model_student_answer->mf_new_pin($questionid);

        //insert new answer into tbl_student_answer
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($questionid);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);

        $question_data = $this->Model_student_answer->mf_load_question($questionid);

        // $questionid = json_encode($question_data[0]->question_id);
        $quizid = json_encode($question_data[0]->quiz_id);
        $questionanswer = $question_data[0]->question_answer;
        $questiontext = htmlspecialchars_decode($question_data[0]->question_text);

        $option_data = $this->Model_student_answer->mf_load_option($questionid);


        $questionoption1 = json_encode($option_data[0]->option_text);
        $questionoption2 = json_encode($option_data[1]->option_text);
        $questionoption3 = json_encode($option_data[2]->option_text);
        $questionoption4 = json_encode($option_data[3]->option_text);
        $questionoption5 = json_encode($option_data[4]->option_text);
        $questionoption6 = json_encode($option_data[5]->option_text);
        $questionoption7 = json_encode($option_data[6]->option_text);
        $questionoption8 = json_encode($option_data[7]->option_text);

        $visitedNo = $this->Model_student_answer->mf_visitedNo($this->session->userdata('pin'));      
        $answeredCorrect = $this->Model_student_answer->mf_answeredCorrect($this->session->userdata('pin'));
        $answeredInCorrect = $this->Model_student_answer->mf_answeredIncorrect($this->session->userdata('pin'));

        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($questionid);

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questionadds' => "<pre><code class='language-javascript'>" . $question_data[0]->question_adds . "</code></pre>",
            'option1' => $questionoption1,
            'option2' => $questionoption2,
            'option3' => $questionoption3,
            'option4' => $questionoption4,
            'option5' => $questionoption5,
            'option6' => $questionoption6,
            'option7' => $questionoption7,
            'option8' => $questionoption8,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer,
            'visitedNo' => $visitedNo,
            'answeredCorrect' => $answeredCorrect,
            'answeredIncorrect' => $answeredInCorrect
        );
echo "thello";
        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_sorting_home', $thequestion);
    }


    public function startquestionsorting()
    {
        if ($this->input->post('questionid') !== null) {
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
        } else {
            $id = $_SESSION['questionid'];
        }
        $this->Model_student_answer->mf_new_pin($id);
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);
        $this->questionsorting();
    }

    public function questionsorting()
    {
        if ($this->input->post('questionid') !== null) {
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
        } else {
            $id = $_SESSION['questionid'];
        }

        $student_answer = $this->Model_student_answer->mf_check_answer_home($id);

        $question_data = $this->Model_student_answer->mf_load_question($id);

        $questionid = json_encode($question_data[0]->question_id);
        $quizid = json_encode($question_data[0]->quiz_id);
        $questionanswer = $question_data[0]->question_answer;
        $questiontext = htmlspecialchars_decode($question_data[0]->question_text);

        $option_data = $this->Model_student_answer->mf_load_option($id);


        $questionoption1 = json_encode($option_data[0]->option_text);
        $questionoption2 = json_encode($option_data[1]->option_text);
        $questionoption3 = json_encode($option_data[2]->option_text);
        $questionoption4 = json_encode($option_data[3]->option_text);
        $questionoption5 = json_encode($option_data[4]->option_text);
        $questionoption6 = json_encode($option_data[5]->option_text);
        $questionoption7 = json_encode($option_data[6]->option_text);
        $questionoption8 = json_encode($option_data[7]->option_text);


        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questionadds' => "<pre><code class='language-javascript'>" . $question_data[0]->question_adds . "</code></pre>",
            'option1' => $questionoption1,
            'option2' => $questionoption2,
            'option3' => $questionoption3,
            'option4' => $questionoption4,
            'option5' => $questionoption5,
            'option6' => $questionoption6,
            'option7' => $questionoption7,
            'option8' => $questionoption8,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer
        );

        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_sorting_home', $thequestion);
    }

    public function questionmatching($id)
    {
        $this->Model_student_answer->mf_new_pin($id);

        $student_answer = $this->Model_student_answer->mf_check_answer_home($id);


        if ($student_answer === 1) {
            redirect(base_url('students/home'));
        } else {
            //load question and options from tbl_question (identify the question_id first)

            $question_data = $this->Model_student_answer->mf_load_question($id);

            $questionid = json_encode($question_data[0]->question_id);
            $questiontext = json_encode($question_data[0]->question_text);

            $option_data = $this->Model_student_answer->mf_load_option($id);

            $questionoption1 = json_encode($option_data[0]->option_text);
            $questionoption2 = json_encode($option_data[1]->option_text);
            $questionoption3 = json_encode($option_data[2]->option_text);
            $questionoption4 = json_encode($option_data[3]->option_text);
            $questionoption5 = json_encode($option_data[4]->option_text);
            $questionoption6 = json_encode($option_data[5]->option_text);

            $matching_data = $this->Model_student_answer->mf_load_matching_option($id);

            $matchingoption1 = json_encode($matching_data[0]->option_text);
            $matchingoption2 = json_encode($matching_data[1]->option_text);
            $matchingoption3 = json_encode($matching_data[2]->option_text);
            $matchingoption4 = json_encode($matching_data[3]->option_text);
            $matchingoption5 = json_encode($matching_data[4]->option_text);
            $matchingoption6 = json_encode($matching_data[5]->option_text);

            $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);

            $thequestion = array(
                'questionid' => $questionid,
                'questiontext' => $questiontext,
                'option1' => $questionoption1,
                'option2' => $questionoption2,
                'option3' => $questionoption3,
                'option4' => $questionoption4,
                'option5' => $questionoption5,
                'option6' => $questionoption6,
                'matching1' => $matchingoption1,
                'matching2' => $matchingoption2,
                'matching3' => $matchingoption3,
                'matching4' => $matchingoption4,
                'matching5' => $matchingoption5,
                'matching6' => $matchingoption6,

                'idstart' => $questionidstart
            );
            $this->load->view('view_question_matching_home', $thequestion);
        }
    }

    public function question_direction()
    {
        $orderquestionid =  $this->input->post('questionid');
        $quizid =  $this->input->post('quizid');

        $question = $this->Model_question->mf_find_question($orderquestionid, $quizid);
        $questionid = $question->question_id;
        $questiontype = $question->question_type;
        $_SESSION['questionid'] = $questionid;
        $_SESSION['questionrun'] = $orderquestionid;

        switch ($questiontype) {
            case '1':
                $this->startquestionmcsa_next();
                break;
            case '3':
                $this->startquestionsorting_next();
                break;
            case '5':
                $this->startquestionshortanswer_next();
                break;
        }
    }

    

    public function startquestionshortanswer_next()
    {
        $orderquestionid = $this->input->post('questionid');
        $quizid = $this->input->post('quizid');

        // echo $orderquestionid;
        // echo $quizid;
        // $questionid = $this->Model_question->mf_find_question($orderquestionid, $quizid);
        // echo $questionid;   
        // $_SESSION['questionid'] = $questionid;
        $questionid = $_SESSION['questionid'];
        // $this->questionshortanswer();
        $_SESSION['idanswer'] = '';
        $_SESSION['studentanswer'] = '';

        //insert new question into tbl_question_running
        $this->Model_student_answer->mf_new_pin($questionid);

        //insert new answer into tbl_student_answer
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($questionid);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);
        $question_data = $this->Model_student_answer->mf_load_question($questionid);

        $questionid = json_encode($question_data[0]->question_id);
        $quizid = json_encode($question_data[0]->quiz_id);
        $questiontext = htmlspecialchars_decode($question_data[0]->question_text);
        $questionanswer = $question_data[0]->question_answer;
        $questiontype = $question_data[0]->question_type;

        $visitedNo = $this->Model_student_answer->mf_visitedNo($this->session->userdata('pin'));      
        $answeredCorrect = $this->Model_student_answer->mf_answeredCorrect($this->session->userdata('pin'));
        $answeredInCorrect = $this->Model_student_answer->mf_answeredIncorrect($this->session->userdata('pin'));

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questiontype' => $questiontype,
            'questionadds' => "<pre><code class='language-markup'>" . $question_data[0]->question_adds . "</code></pre>",
            'questionimage' => $question_data[0]->question_image,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer,
            'visitedNo' => $visitedNo,
            'answeredCorrect' => $answeredCorrect,
            'answeredIncorrect' => $answeredInCorrect
        );
        // $this->Model_student_answer->mf_new_question_begin($questionidstart);
        // echo json_encode($thequestion);
        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_shortanswer_home', $thequestion);
    }

    public function startquestionshortanswer()
    {
        if ($this->input->post('questionid') !== null) {
            // echo "here enter";
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
        } else {
            $id = $_SESSION['questionid'];
        }

        $_SESSION['idanswer'] = '';
        $_SESSION['studentanswer'] = '';

        //insert new question into tbl_question_running
        $this->Model_student_answer->mf_new_pin($id);

        //insert new answer into tbl_student_answer
        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);
        $this->Model_student_answer->mf_new_question_begin($questionidstart);
        $this->questionshortanswer();
    }

    public function questionshortanswer()
    {
        if ($this->input->post('questionid') !== null) {
            $id = $this->input->post('questionid');
            $newdata = array(
                'questionid' => $id
            );
            $this->session->set_userdata($newdata);
            //new question to load
        } else {
            $id = $_SESSION['questionid'];
            //old question to load
        }



        $question_data = $this->Model_student_answer->mf_load_question($id);

        $questionid = json_encode($question_data[0]->question_id);
        $quizid = json_encode($question_data[0]->quiz_id);
        $questiontext = htmlspecialchars_decode($question_data[0]->question_text);
        $questionanswer = $question_data[0]->question_answer;

        $questionidstart = $this->Model_student_answer->mf_get_idstart_home($id);
        $visitedNo = $this->Model_student_answer->mf_visitedNo($this->session->userdata('pin'));
        // $answeredCorrect = $this->Model_student_answer->mf_answeredCorrect();
        // $answeredInCorrect = $this->Model_student_answer->mf_answeredIncorrect();

        $thequestion = array(
            'questionid' => $questionid,
            'quizid' => $quizid,
            'questiontext' => $questiontext,
            'questionadds' => "<pre><code class='language-markup'>" . $question_data[0]->question_adds . "</code></pre>",
            'questionimage' => $question_data[0]->question_image,
            'idstart' => $questionidstart,
            'questionanswer' => $questionanswer,
            // 'visitedNo' => array(1,2,3,4,5,8,10),
            'visitedNo' => $visitedNo,
            'answeredCorrect' => 0,
            'answeredIncorrect' => 0
        );
        // $this->Model_student_answer->mf_new_question_begin($questionidstart);
        // echo json_encode($thequestion);
        $this->load->view('view_header_students', $thequestion);
        $this->load->view('view_question_shortanswer_home', $thequestion);
        // }
    }

    public function load_question() //g dipake
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');


        $question_start = $this->Model_student_answer->mf_load_question_start();

        $current_question = $this->Model_student_answer->mf_load_question();

        $question_text = $this->Model_student_answer->mf_load_question_text();

        if ($current_question <> 0) {

            $question_start = json_encode($question_start[0]->id);
            $questtext = json_encode($question_text[0]->question_text);
            $option1 = json_encode($current_question[0]->option_text);
            $option2 = json_encode($current_question[1]->option_text);
            $option3 = json_encode($current_question[2]->option_text);
            $option4 = json_encode($current_question[3]->option_text);

            $theoption = array(
                'questionstart' => $question_start,
                'question' => $questtext,
                'option1' => $option1,
                'option2' => $option2,
                'option3' => $option3,
                'option4' => $option4
            );
            $quest = json_encode($theoption);
        } else {
            $quest = $current_question;
        }


        echo "data: {$quest}\n\n";

        flush();
    }

    public function load_question_home()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        //check tbl_question_start
        $question_start = $this->Model_student_answer->mf_load_question_start();

        if ($question_start === 0) {
            $checkshow = $this->Model_student_answer->mf_check_show();
            if ($checkshow <> 0) {
                $theoption = array(
                    'questionid' => $checkshow,
                    'status_show' => 1
                );
                $quest = json_encode($theoption); //there is a result to be show
            } else {
                $quest = "0";
            }
        } else {
            //check tbl_student_answer
            $student_answer = $this->Model_student_answer->mf_check_answer();

            $questionid = json_encode($question_start[0]->question_id);
            $questionstart = json_encode($question_start[0]->status_start);
            $questionshow = json_encode($question_start[0]->status_show);
            $questiontype = json_encode($question_start[0]->question_type);
            $questionidstart = json_encode($question_start[0]->id);
            $student_answer = $student_answer; //terupdate setelah student menjawab 

            $theoption = array(

                'questionid' => $questionid,
                'questionstart' => $questionstart,
                'questionshow' => $questionshow,
                'questiontype' => $questiontype,
                'studentanswer' => $student_answer,
                'questionidstart' => $questionidstart
            );
            $quest = json_encode($theoption);
        }

        echo "data: {$quest}\n\n";

        flush();
    }

    public function startquestion()
    {
        $idquestion = $this->input->post('idquestion');
        $iqs = $this->input->post('questionStart');
        $ids = $this->session->userdata('user_id');
    }


    public function submitAnswer_shortanswer()
    {
        $idquestion = $this->input->post('idquestion');
        $iqs = $this->input->post('questionStart');
        $san = htmlspecialchars($this->input->post('studentAnswer'));
        $antext = $this->input->post('answerText');
        $quizid = $this->input->post('quizid');

        $_SESSION['quiz_id'] = $quizid;

        $ids = $this->session->userdata('user_id');

        $this->form_validation->set_rules('studentAnswer', 'Student Answer', 'trim');

        //updating tbl_student_answer with student answer in respective id_question_start
        $query = $this->Model_student_answer->mf_submit_answer_home($iqs, $san, $ids, $idquestion, $antext);

        if ($query) {
            $this->questionshortanswer();
        } else {
            show_error('Data is not saved. Contact the administrator (E1)', 404, 'Database Error');
        }
    }

    public function clear_studentanswer($qt)
    {
        $this->session->unset_userdata('studentanswer');
        switch ($qt) {
            case '1':
                $this->questionmcsa();
                break;
            case '5':
                $this->questionshortanswer();
                break;
        }
    }

    public function submitAnswer()
    {
        //save answer to database (which table/column?)
        //insert into tbl_student_answer(id_question_start, id_student, student_answer)
        // $iqs = ; //id_question_start
        // $ids = ; //id_student
        // $san = ; //student_answer
        // echo $_SESSION['pin'];

        $idquestion = $this->input->post('idquestion');
        $iqs = $this->input->post('questionStart');
        $san = $this->input->post('studentAnswer');
        $antext = $this->input->post('answerText');
        $quizid = $this->input->post('quizid');
        $ids = $this->session->userdata('user_id');

        $_SESSION['quiz_id'] = $quizid;

        $query = $this->Model_student_answer->mf_submit_answer_home($iqs, $san, $ids, $idquestion, $antext);
        if ($query) {
            // $this->questionmcsa();
            // $this->home_question($_SESSION['quiz_id']);
            // redirect(base_url() . 'index.php/students/home_question/0');
            // $currOrder = $_SESSION['questionrun'] + 1;
            // redirect(base_url() . 'index.php/students/startquestionarray/' . $currOrder);
            $this->questionmcsa();
        } else {
            show_error('Data is not saved. Contact the administrator (E1)', 404, 'Database Error');
        }
    }

    public function submitAnswer_ajax()
    {
        $idquestion = $this->input->post('idquestion');
        $iqs = $this->input->post('questionStart');
        $san = $this->input->post('studentAnswer');
        $antext = $this->input->post('answerText');
        $quizid = $this->input->post('quizid');
        $ids = $this->session->userdata('user_id');

        $_SESSION['quiz_id'] = $quizid;

        $query = $this->Model_student_answer->mf_submit_answer_home($iqs, $san, $ids, $idquestion, $antext);
        if ($query) {
            echo $san;
        } else {
            show_error('Data is not saved. Contact the administrator (E1)', 404, 'Database Error');
        }
    }

    public function finish_question()
    {
        //clear the session
        $this->session->unset_userdata('studentanswer');
        $this->session->unset_userdata('answertext');

        //delete last record
        //delete from tbl_student_answer where id=$_SESSION['idsan]
        $this->Model_student_answer->mf_finish_question($_SESSION['idanswer']);

        //go to home_question
        $this->home_question($_SESSION['quiz_id']);
    }

    public function errorpage($errormessage)
    {
        $data['error_message'] = $errormessage;
        $this->load->view('view_error_page', $data);
    }

    public function test_update_question_running()
    {
        $this->Model_student_answer->mf_update_qr();
    }

    public function load_question_start()
    {
        echo $this->Model_student_answer->mf_load_question_start();
    }

    public function test_query()
    {
        echo json_encode($this->Model_student_answer->mf_test());
    }

    public function testmcma() //ga dipake
    {
        $studentanswer = 1; //check dari database;
        //check dari database apakah sudah menjawab belum
        if ($studentanswer === 1) {
            redirect(base_url('controller_test'));
        }

        $this->load->view('view_test_mcma');
    }

    public function checkanswer()
    {
        echo $this->Model_student_answer->mf_check_answer();
    }

    public function check_started_question()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        //check tbl_question_start
        $question_start = $this->Model_student_answer->mf_check_started_question();

        echo "data: {$question_start}\n\n";

        flush();
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('pin');
        redirect(base_url() . 'students/loginstudent');
    }

    public function testlogin()
    {
        $email = "john@gmail.com";
        $password = "johny";
        $statuslogin = $this->Model_student_answer->mf_check_login($email, $password);
        if ($statuslogin) {
            echo "login berhasil";
        } else {
            echo "login gagal";
        }
    }

    public function test_send_email()
    {
        $to_email = "almed_2001@yahoo.com";
        $subject = "Test Sending Email";
        $body = "Hi, this is your first email";
        $headers = "From: sender\'s email";

        if (mail($to_email, $subject, $body, $headers)) {
            echo "Email sent";
        } else {
            echo "Email sending failed";
        }
    }

    public function testhash()
    {
        $password = "tom";
        echo password_hash($password, PASSWORD_DEFAULT);
    }

    public function testshowresult()
    {
        $checkshow = $this->Model_student_answer->mf_check_show();
        echo $checkshow;
    }

    public function testdisablequestion()
    {
        $this->Model_quiz->mf_test_disable_question();
    }
}
