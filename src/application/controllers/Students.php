<?php

class Students extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_student_answer');
        $this->load->model('Model_question');
        $this->load->model('Model_quiz');
        $this->load->model('Model_running_question');
        $this->load->model('Model_login');
    }

    public function index()
    {

        if ($this->session->userdata('user_id') != '') {
            $userid = $this->session->userdata('user_id');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($userid);
            $this->load->view('view_header_students');
            $this->load->view('view_modes', $data);
        } else {
            $this->loginstudent_ver_3();
        }
    }

    public function modes()
    {
        if ($this->session->userdata('user_id') != '') {
            $userid = $this->session->userdata('user_id');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($userid);

            $this->load->view('view_header_students');
            $this->load->view('view_modes', $data);
        } else {
            $this->loginstudent_ver_3();
        }
    }

    public function home($topicid)
    {
        //display all quizzes from tbl_quiz
        if ($this->session->userdata('user_id') != '') {

            $userid = $this->session->userdata('user_id');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($userid);

            $cor = 0;
            $newlist = array();

            $list = $this->Model_quiz->mf_list_quiz_home($topicid);

            foreach ($list as $row) {
                $newdata = array(
                    'quiz_id'  => $row->quiz_id,
                    'topic_id' => $row->topic_id,
                    'quiz_title' => $row->quiz_title,
                    'num_question' => $row->num_question,
                    'avg_attempts' => $row->avg_attempts,
                    'total'     => $this->Model_quiz->mf_calc_total($row->quiz_id, $this->session->userdata('user_id')),
                    'cor'     => $this->Model_quiz->mf_calc_cor($row->quiz_id, $this->session->userdata('user_id')),
                    'incor' => $this->Model_quiz->mf_calc_incor($row->quiz_id, $this->session->userdata('user_id'))

                );

                array_push($newlist, $newdata);
            }

            $data['view_quiz_home'] = json_encode($newlist);
            $data['view_list_topic'] = $this->Model_quiz->mf_list_topic();

            $data['total_student_answer'] = $this->Model_quiz->mf_overall_answer();
            $data['total_questions'] = $this->Model_quiz->mf_overall_question_number();

            $this->load->view('view_header_students');
            $this->load->view('view_students_at_home', $data);
        } else {
            $this->loginstudent_ver_3();
        }
    }

    public function startquestionarray($order)
    {
        if ($this->input->post('quizid') !== null) {
            $quiz_id = $this->input->post('quizid'); // first question open
        } else {
            $quiz_id = $_SESSION['quiz_id']; //next to first question open
        }

        // get all questions belong to $quiz_id start from $order
        $data = $this->Model_quiz->mf_list_question_quizid($quiz_id);
        $dataLength = count($data);

        if ($order <= $dataLength) {

            $currQuestion = $data[$order - 1];

            $questiontype = $currQuestion->question_type;
            $questionid = $currQuestion->question_id;
            $quizid = $currQuestion->quiz_id;

            $_SESSION['quiz_id'] = $quizid;

            // set new pin
            $pin = $this->Model_running_question->mf_check_pin_home();

            $newdata = array(
                'pin'     => $pin,
                'questionrun' => $order,
                'opentabs' => 1,
                'questionid' => $questionid,
                'countquestion' => $dataLength
            );
            $this->session->set_userdata($newdata);

            switch ($questiontype) {
                case 1:
                    redirect(base_url() . 'index.php/studentshome/startquestionmcsa');
                    break;
                case 3:
                    redirect(base_url() . 'index.php/studentshome/startquestionsorting');
                    break;
                case 5:
                    redirect(base_url() . 'index.php/studentshome/startquestionshortanswer');
                    break;
            }
        } else {
            $this->home(2);
        }
    }
    public function startquestionarray_next($order)
    {
        if ($this->input->post('quizid') !== null) {
            $quiz_id = $this->input->post('quizid'); // first question open
        } else {
            $quiz_id = $_SESSION['quiz_id']; //next to first question open
        }

        // get all questions belong to $quiz_id start from $order
        $data = $this->Model_quiz->mf_list_question_quizid($quiz_id);
        $dataLength = count($data);

        if ($order <= $dataLength) {

            $currQuestion = $data[$order - 1];

            $questiontype = $currQuestion->question_type;
            $questionid = $currQuestion->question_id;
            $quizid = $currQuestion->quiz_id;

            $_SESSION['quiz_id'] = $quizid;

            // set new pin
            // $pin = $this->Model_running_question->mf_check_pin_home();
            $pin = $_SESSION['pin'];

            $newdata = array(
                'pin'     => $pin,
                'questionrun' => $order,
                'opentabs' => 1,
                'questionid' => $questionid,
                'countquestion' => $dataLength
            );
            $this->session->set_userdata($newdata);

            switch ($questiontype) {
                case 1:
                    redirect(base_url() . 'index.php/studentshome/startquestionmcsa_next');
                    break;
                case 3:
                    redirect(base_url() . 'index.php/studentshome/startquestionsorting_next');
                    break;
                case 5:
                    redirect(base_url() . 'index.php/studentshome/startquestionshortanswer_next');
                    break;
            }
        } else {
            $this->home(2);
        }
    }

    public function home_question($viewstatus)
    {
        $quiz_id = $_SESSION['quiz_id'];
        $user_id = $_SESSION['user_id'];


        if (is_null($this->Model_quiz->mf_list_question_home_last($quiz_id, $user_id))) {
            $this->startquizhome();
        } else {
            if ($viewstatus === '0') {
                $data['view_question_home'] = $this->Model_quiz->mf_list_question_home_last($quiz_id, $user_id);
            } else {
                $data['view_question_home'] = $this->Model_quiz->mf_list_question_home($quiz_id, $user_id);
            }
            $this->load->view('view_header_students');
            $this->load->view('view_students_at_home_question', $data);
        }
    }




    public function viewlastquizhome()
    {
        $quiz_id = $this->input->post('quizid');
        $newdata = array(
            'quiz_id'  => $quiz_id,
            'opentabs' => 0
        );
        $this->session->set_userdata($newdata);
        $this->home_question('0');
    }

    public function startquizhome()
    {
        // echo "enter here";
        // $quiz_id = $this->input->post('quizid');
        // check and generate PIN 
        // save the PIN to database (because it will be used by student)
        // add to session: quiz_id, quiz_title, and PIN
        // open question page

        // $quiz_id = $quiz_id;
        // $newdata = array(
        //     'quiz_id'  => $quiz_id
        // );
        // $this->session->set_userdata($newdata);

        $pin = $this->Model_running_question->mf_check_pin_home();

        $newdata = array(
            'pin'     => $pin,
            'questionrun' => 1,
            'opentabs' => 1
        );
        $this->session->set_userdata($newdata);

        $this->home_question('1');

        // echo "hello start";
    }

    public function loginstudent()
    {
        $data['title'] = 'Login to QuizAlpha';
        $this->load->view('view_login_student', $data);
    }

    public function loginstudent_ver_3()
    {
        $this->load->view('view_header_students-y');
        $this->load->view('view_login_student_ver_3');
    }

    public function processlogin()
    {
        //validation process (tbd)

        $this->form_validation->set_rules('studentid', 'Student ID', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run()) {
            //true
            //authentication process
            $studentid =  $this->input->post('studentid');
            $password = $this->input->post('password');

            if ($this->Model_student_answer->mf_check_login($studentid, $password)) {

                $username = $this->Model_login->mf_get_username($studentid);
                $session_data = array(
                    'user_id' => $studentid,
                    'username' => $username
                );
                $this->session->set_userdata($session_data);

                //save login data

                $browser = $this->agent->browser();
                $browserversion = $this->agent->version();
                $platform = $this->agent->platform();
                $inbattery = $this->input->post('inBattery');
                $batteryLevel = $this->input->post('batteryLevel');
                $mobile = $this->agent->mobile();



                $this->Model_login->mf_save_login_data($studentid, $browser, $browserversion, $platform, $inbattery, $batteryLevel, $mobile);
                $this->Model_login->mf_set_id_login_session($studentid);

                $this->processpin_alt();
            } else {
                $this->session->set_flashdata('error', '<span class="text-danger">Invalid Student ID and/or Password</span>');
                $this->loginstudent_ver_3();
            }
        } else {
            //false     

            $this->loginstudent_ver_3();
        }
    }

    public function ismobile()
    {
        echo "hello";
        echo $this->agent->mobile();
    }

    public function processpin_alt()
    {
        // //retrieve last pin (active question from tbl_question_running)
        // $pin = $this->model_student_answer->mf_retrieve_last_pin();

        // //set session userdata ('pin') 
        // $session_data = array(
        //     'pin' => $pin
        // );
        // $this->session->set_userdata($session_data);

        //redirect to question page (students)
        // if (isset($_SESSION['pin'])) {
        //     echo $_SESSION['pin'];
        // }
        // redirect(base_url() . 'students/modes');

        $this->modes();
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
                // redirect(base_url() . 'students');
                $this->index();
            } else {
                $this->session->set_flashdata('error', 'Invalid PIN');
                // redirect(base_url() . 'students/enterpin');
                $this->enterpin();
            }
        } else {
            $this->enterpin();
        }
    }

    public function enterpin() //abandoned function (X)
    {

        if ($this->session->userdata('email') != '') {
            $email = $this->session->userdata('email');
            $data['username'] = $this->Model_student_answer->mf_retrieve_username($email);
            $this->load->view('view_enter_pin', $data);
        } else {
            redirect(base_url() . 'students/loginstudent');
        }
    }

    public function questionmcsa($id)
    {
        $student_answer = $this->Model_student_answer->mf_check_answer();

        if ($student_answer === 1) {
            $this->index();
        } else {
            //load question and options from tbl_question (identify the question_id first)

            $question_data = $this->Model_student_answer->mf_load_question($id);

            $questionid = json_encode($question_data[0]->question_id);
            $questiontext = $question_data[0]->question_text;

            $option_data = $this->Model_student_answer->mf_load_option($id);

            $questionoption1 = $option_data[0]->option_text;
            $questionoption2 = $option_data[1]->option_text;
            $questionoption3 = $option_data[2]->option_text;
            $questionoption4 = $option_data[3]->option_text;

            $questionidstart = $this->input->get('idstart');

            $thequestion = array(
                'questionid' => $questionid,
                'questiontext' => $questiontext,
                'questionadds' => "<pre><code class='language-javascript'>" . $question_data[0]->question_adds . "</code></pre>",
                'questionimage' => $question_data[0]->question_image,
                'option1' => $questionoption1,
                'option2' => $questionoption2,
                'option3' => $questionoption3,
                'option4' => $questionoption4,
                'idstart' => $questionidstart
            );

            $this->load->view('view_header_students');
            $this->load->view('view_question_mcsa', $thequestion);
        }
    }

    public function questionmcma($id)
    {
        $student_answer = $this->Model_student_answer->mf_check_answer();

        if ($student_answer === 1) {
            // redirect(base_url('students'));
            $this->index();
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

            $questionidstart = $this->input->get('idstart');

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
            $this->load->view('view_question_mcma', $thequestion);
        }
    }

    public function questionsorting($id)
    {
        $student_answer = $this->Model_student_answer->mf_check_answer();

        if ($student_answer === 1) {
            // redirect(base_url('students'));
            $this->index();
        } else {
            //load question and options from tbl_question (identify the question_id first)

            $question_data = $this->Model_student_answer->mf_load_question($id);

            $questionid = json_encode($question_data[0]->question_id);
            $questiontext = json_encode($question_data[0]->question_text);

            $option_data = $this->Model_student_answer->mf_load_option($id);

            $questionoption1 = $option_data[0]->option_text;
            $questionoption2 = $option_data[1]->option_text;
            $questionoption3 = $option_data[2]->option_text;
            $questionoption4 = $option_data[3]->option_text;
            $questionoption5 = $option_data[4]->option_text;
            $questionoption6 = $option_data[5]->option_text;
            $questionoption7 = $option_data[6]->option_text;
            $questionoption8 = $option_data[7]->option_text;

            $questionidstart = $this->input->get('idstart');

            $thequestion = array(
                'questionid' => $questionid,
                'questiontext' => $questiontext,
                'questionadds' => "<pre><code class='language-javascript'>" . $question_data[0]->question_adds . "</code></pre>",
                'questionimage' => $question_data[0]->question_image,
                'option1' => $questionoption1,
                'option2' => $questionoption2,
                'option3' => $questionoption3,
                'option4' => $questionoption4,
                'option5' => $questionoption5,
                'option6' => $questionoption6,
                'option7' => $questionoption7,
                'option8' => $questionoption8,
                'idstart' => $questionidstart
            );
            $this->load->view('view_header_students');
            $this->load->view('view_question_sorting', $thequestion);
        }
    }

    public function questionmatching($id)
    {
        $student_answer = $this->Model_student_answer->mf_check_answer();

        if ($student_answer === 1) {
            // redirect(base_url('students'));

            $this->index();
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



            $questionidstart = $this->input->get('idstart');

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
            $this->load->view('view_question_matching', $thequestion);
        }
    }
    public function questionshortanswer($id)
    {
        $student_answer = $this->Model_student_answer->mf_check_answer();

        if ($student_answer === 1) {

            $this->index();
        } else {
            $question_data = $this->Model_student_answer->mf_load_question($id);

            $questionid = json_encode($question_data[0]->question_id);
            $questiontext = json_encode($question_data[0]->question_text);



            $questionidstart = $this->input->get('idstart');

            $thequestion = array(
                'questionid' => $questionid,
                'questiontext' => $questiontext,
                'questionadds' => "<pre><code class='language-javascript'>" . $question_data[0]->question_adds . "</code></pre>",
                'questionimage' => $question_data[0]->question_image,
                'idstart' => $questionidstart
            );
            $this->load->view('view_header_students');
            $this->load->view('view_question_shortanswer', $thequestion);
        }
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
            // $quest = json_encode($current_question[0]);
            // $quest = "Start";
        } else {
            $quest = $current_question;
        }


        echo "data: {$quest}\n\n";

        flush();
    }

    public function test_mf_check_answer()
    {
        echo $this->Model_student_answer->mf_check_answer();
    }

    public function test_mf_load_question_start()
    {
        echo json_encode($this->Model_student_answer->mf_load_question_start());
        echo json_encode($this->Model_student_answer->mf_check_answer());
    }

    public function vardump()
    {

        var_dump($_SESSION);
    }
    public function load_question_home()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        //check tbl_question_start
        $question_start = $this->Model_student_answer->mf_load_question_start();
        // $questionidstart = json_encode($question_start[0]->id);
        // echo $questionidstart;
        // if (isset($question_start[0]->id)) {
        // $student_show = $this->Model_student_answer->mf_read_student_show_status($question_start[0]->id);
        // }
        // echo $student_show;

        //alternativ 2
        if ($question_start === 0) {
            $quest = "0";
        } else {
            $checkshow = $this->Model_student_answer->mf_check_show();
            if ($checkshow <> 0) {
                //show only displayed when student_show=1
                //    echo  json_encode($question_start[0]->id);
                //    echo $this->session->userdata('user_id');
                $student_show = $this->Model_student_answer->mf_read_student_show_status($this->session->userdata('ids'), $this->session->userdata('user_id'));
                // echo $student_show;
                if ($student_show === "0") {
                    $quest = "0";
                } else {
                    $theoption = array(
                        'questionid' => $checkshow,
                        'status_show' => 1
                    );
                    $quest = json_encode($theoption); //there is a result to be shown

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
        }

        echo "data: {$quest}\n\n";

        flush();
    }

    public function update_student_show($sid)
    {
        //$sid = id_question_start
        $user_id = $this->session->userdata('user_id');
        //update tbl_student_answer where user_id=$user_id and id_question_start=$sid
        $this->Model_student_answer->mf_update_student_show($sid, $user_id);
        // echo $sid;
        $this->index();
    }

    public function submitAnswer()
    {
        //save answer to database (which table/column?)
        //insert into tbl_student_answer(id_question_start, id_student, student_answer)
        // $iqs = ; //id_question_start
        // $ids = ; //id_student
        // $san = ; //student_answer


        $iqs = $this->input->post('questionStart');
        $san = $this->input->post('studentAnswer');
        $ids = $this->session->userdata('user_id');


        $query = $this->Model_student_answer->mf_submit_answer($iqs, $san, $ids);
        if ($query) {
            $_SESSION['ids'] = $iqs;
            $this->index();
        } else {
            show_error('Data is not saved. Contact the administrator (E1)', 404, 'Database Error');
        }
    }

    public function submitAnswer_shortanswer()
    {
        //save answer to database (which table/column?)
        //insert into tbl_student_answer(id_question_start, id_student, student_answer)
        // $iqs = ; //id_question_start
        // $ids = ; //id_student
        // $san = ; //student_answer

        $iqs = $this->input->post('questionStart');
        $san = htmlspecialchars($this->input->post('studentAnswer'));
        $ids = $this->session->userdata('user_id');


        $this->form_validation->set_rules('studentAnswer', 'Student Answer', 'trim');
        // echo $san;
        // if ($this->form_validation->run()) {
        //     echo "run data saving";
        // } else {
        //     echo "data not saved";
        // }

        $query = $this->Model_student_answer->mf_submit_answer($iqs, $san, $ids);
        if ($query && $this->form_validation->run()) {
            $_SESSION['ids'] = $iqs;
            $this->index();
        } else {
            show_error('Data is not saved. Contact the administrator (E1)', 404, 'Database Error');
        }
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
        echo $this->Model_student_answer->mf_check_answer_home();
        echo $_SESSION['email'];
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
        $this->Model_login->mf_save_logout_data();
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('pin');
        $this->session->unset_userdata('login_id');
        $this->session->unset_userdata('username');

        $this->session->sess_destroy();

        $this->loginstudent_ver_3();
    }

    public function changepassword()
    {
        if ($this->session->userdata('user_id') != '') {
            $userid = $this->session->userdata('user_id');
            $data['user_id'] = $this->Model_student_answer->mf_retrieve_username($userid);

            $this->load->view('view_header_students');
            $this->load->view('view_change_password_student', $data);
        } else {
            // redirect(base_url() . 'students/loginstudent_ver_3');
            $this->loginstudent_ver_3();
        }
        // $this->load->view('view_change_password_student');
    }

    public function processchangepassword()
    {

        $this->form_validation->set_rules('newpassword', 'New Password', 'trim|required');
        $this->form_validation->set_rules('confpassword', 'Confirmed Password', 'trim|required');

        if ($this->form_validation->run()) {
            $newpassword_new = $this->input->post('newpassword');
            $newpassword_conf = $this->input->post('confpassword');
            $userid = $_SESSION['user_id'];

            if ($newpassword_new === $newpassword_conf) {
                //save new password to database
                if ($this->Model_login->mf_change_password_student($newpassword_conf, $userid)) {
                    //success saving data
                    $this->changepassword_success();
                } else {
                    //failed to save
                    $this->session->set_flashdata('error', 'Passwords do not match');
                    // redirect(base_url() . 'students/changepassword');

                    $this->changepassword();
                }
            } else {
                //back to changepassword page
                $this->changepassword();
            }
        } else {
            $this->loginstudent_ver_3();
        }
    }

    public function changepassword_success()
    {
        $this->load->view('view_header_students');
        $this->load->view('view_change_password_success');
    }
    public function view_session()
    {
        echo $_SESSION['pin'];
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
        // $question_start = $this->model_student_answer->mf_load_question_start();
        $checkshow = $this->Model_student_answer->mf_check_show();
        echo $checkshow;
    }

    public function testquestionstart()
    {
        echo $this->Model_student_answer->mf_load_question_start();
    }

    public function testload()
    {
        echo $this->Model_student_answer->mf_test_load();
    }

    public function testbrowserdetect()
    {
        echo $this->agent->browser();
        echo $this->agent->version();
        echo $this->agent->platform();
        $format = "%Y-%m-%d %h:%i %A";
        echo mdate($format);
    }

    public function load_sse()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $quest = "this is test";

        echo "data: {$quest}\n\n";

        flush();
    }

    public function testsse()
    {
        $this->load->view('view_test_sse');
    }

    public function test_mf_list_question_home()
    {
        echo $this->Model_quiz->mf_list_question_home(1);
        echo "<br/> pin" . $_SESSION['pin'];
        echo "<br/> quiz ID" . $_SESSION['quiz_id'];
    }

    public function testcsp()
    {
        $this->load->view('view_testcsp');
    }

    public function test_incor()
    {
        echo "test";
        echo json_encode($this->Model_quiz->mf_calc_incor(44, 300));
    }
}
