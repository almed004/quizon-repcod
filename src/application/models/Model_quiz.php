<?php

class Model_quiz extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_list_topic_all_num_quizzes()
    {
        $this->db->select('a.*, b.num_quiz');
        $this->db->from('tbl_topic a');
        $this->db->join('(select topic_id, count(*) as num_quiz from tbl_quiz group by topic_id) b', 'a.id=b.topic_id', 'left');
        // $this->db->where('a.topic_id', $topicid);
        $query = $this->db->get();
        return $query->result();
    }
    public function mf_list_topic()
    {

        $query = $this->db->get('tbl_topic');

        return $query->result();
    }

    public function mf_list_question_all()
    {
        $query = $this->db->get('tbl_question');
        return $query->result();
    }

    public function mf_list_question_quizid($quizid){
        $query=$this->db->get_where('tbl_question', array('quiz_id'=>$quizid));
       
       

        return $query->result();
    }

    public function mf_list_quiz_all_2()
    {
        $query = $this->db->get('tbl_quiz');
        return $query->result();
    }
    public function mf_list_quiz_all($topicid)
    {
        $query = $this->db->get_where('tbl_quiz', array('topic_id' => $topicid));
        return $query->result();
    }

    public function mf_list_quiz_all_num_questions($topicid)
    {
        $this->db->select('a.*, b.num_question');
        $this->db->from('tbl_quiz a');
        $this->db->join('(select quiz_id, count(*) as num_question from tbl_question group by quiz_id) b', 'a.quiz_id=b.quiz_id', 'left');
        $this->db->where('a.topic_id', $topicid);
        $query = $this->db->get();
        return $query->result();
    }

    public function mf_list_quiz($quiztype)
    {
        if ($quiztype <> '0') {
            $this->db->SELECT('a.quiz_id, a.quiz_title, a.quiz_type, count(b.question_id) as questioncount');
            $this->db->FROM('tbl_quiz a');
            $this->db->join('tbl_question b', 'a.quiz_id=b.quiz_id', 'left');
            $this->db->where('a.quiz_type', $quiztype);
            $this->db->group_by('a.quiz_id');
            $query = $this->db->get();

            // $query = $this->db->get_where('tbl_quiz', array('quiz_type'=>$quiztype));
        } else {
            $this->db->SELECT('a.quiz_id, a.quiz_title, a.quiz_type, count(b.question_id) as questioncount');
            $this->db->FROM('tbl_quiz a');
            $this->db->join('tbl_question b', 'a.quiz_id=b.quiz_id', 'left');
            // $this->db->where('quiz_type',$quiztype);
            $this->db->group_by('a.quiz_id');
            $query = $this->db->get();
            // $query = $this->db->get('tbl_quiz');
        }


        return $query->result();
    }

    public function mf_overall_question_number()
    {
        $this->db->select('*');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_quiz b', 'a.quiz_id=b.quiz_id', 'inner');
        $this->db->where('b.quiz_type=2');
        $query = $this->db->get();

        return $query->num_rows();
    }
    public function mf_overall_answer()
    {
        $this->db->select('a.id_student, count(DISTINCT(b.question_id)) as question_answered ');
        $this->db->from('tbl_student_answer a');
        $this->db->join('tbl_question_running b', 'a.id_question_start=b.id', 'inner');
        $this->db->join('tbl_question c', 'b.question_id = c.question_id', 'inner');
        $this->db->join('tbl_quiz d', 'c.quiz_id=d.quiz_id', 'inner');
        $this->db->where('d.quiz_type=2');
        $this->db->where('a.id_student="' . $this->session->userdata('user_id') . '"');
        $query = $this->db->get();
        $row = $query->row();
        return $row->question_answered;
    }

    public function mf_calc_cor($quizid, $userid)
    {
        $query = $this->db->query('select * from tbl_question_running a inner join tbl_student_answer b on a.id = b.id_question_start inner join tbl_question c on a.question_id=c.question_id where a.quiz_PIN = (
            select d.quiz_PIN from tbl_quiz_running_home d where d.id = (
            select max(d.id) from tbl_quiz_running_home d where d.students_id=' . $userid . ' and d.quiz_id=' . $quizid . '))');



        $cor = 0;
        foreach ($query->result() as $row) {
            $student_answer = $row->students_answer;
            $question_answer = $row->question_answer;
            $question_type = $row->question_type;
            switch ($question_type) {
                case '1':
                    if ($student_answer === $question_answer) {
                        $cor += 1;
                    }
                    break;
                case '3':
                    if ($student_answer === $question_answer) {
                        $cor += 1;
                    }
                    break;
                case '5':
                    if (preg_match("/" . strtolower($question_answer) . "/", strtolower($student_answer))) {
                        $cor += 1;
                    }

                    break;
            }
        }

        return $cor;
    }
    public function mf_calc_total($quizid, $userid)
    {
        $query = $this->db->query('select * from tbl_question_running a inner join tbl_student_answer b on a.id = b.id_question_start inner join tbl_question c on a.question_id=c.question_id where a.quiz_PIN = (
            select d.quiz_PIN from tbl_quiz_running_home d where d.id = (
            select max(d.id) from tbl_quiz_running_home d where d.students_id=' . $userid . ' and d.quiz_id=' . $quizid . '))');

        if (empty($query->result())) {
            return 0;
        } else {
            return $query->num_rows();
        }
    }


    public function mf_calc_incor($quizid, $userid)
    {
        $query = $this->db->query('select * from tbl_question_running a inner join tbl_student_answer b on a.id = b.id_question_start inner join tbl_question c on a.question_id=c.question_id where a.quiz_PIN = (
            select d.quiz_PIN from tbl_quiz_running_home d where d.id = (
            select max(d.id) from tbl_quiz_running_home d where d.students_id=' . $userid . ' and d.quiz_id=' . $quizid . '))');



        $incor = 0;
        foreach ($query->result() as $row) {
            $student_answer = $row->students_answer;
            $question_answer = $row->question_answer;
            $question_type = $row->question_type;
            $id = $row->id;
            switch ($question_type) {
                case '1':
                    if (($student_answer <> $question_answer) or ($student_answer === '' and $id <> NULL)) {
                        $incor += 1;
                    }
                    break;
                case '3':
                    if (($student_answer <> $question_answer) or ($student_answer === '' and $id <> NULL)) {
                        $incor += 1;
                    }
                    break;
                case '5':
                    // echo "goes here";
                    // echo $question_answer;
                    // echo $student_answer;
                    // if (!(preg_match("/" . strtolower($question_answer) . "/", strtolower($student_answer)))) {
                    //     echo "false";
                    // } else {
                    //     echo "true";
                    // }

                    if (!(preg_match("/" . strtolower($question_answer) . "/", strtolower($student_answer))) or ($student_answer === '' and !is_null($id))) {
                        $incor += 1;
                    }

                    break;
            }
        }

        return $incor;
        // return $query->result();
    }

    public function mf_list_quiz_home($topicid)
    {
        //provide access to draft_question(question_type=3) to tester ()
        $query = $this->db->get_where('tbl_user', array('user_id' => $this->session->userdata('user_id')));
        $row = $query->row();
        $role = $row->role;

        if ($role !== '1') {
            $this->db->select('*');
            $this->db->from('(SELECT a.topic_id, a.quiz_id, a.quiz_type, a.quiz_title, avg_attempts FROM `tbl_quiz` a left outer join 
           (select a.quiz_id, avg(a.count) as avg_attempts from (select topic_id, a.quiz_id, count(*) as count from tbl_quiz a inner join tbl_question b on a.quiz_id=b.quiz_id left outer join tbl_question_running c on b.question_id=c.question_id left outer join tbl_student_answer d on c.id=d.id_question_start where d.id_student="' . $this->session->userdata('user_id') . '" and a.quiz_type=2 group by b.question_id) a group by a.quiz_id) b on a.quiz_id=b.quiz_id) a');
            $this->db->join('(select a.quiz_id, b.num_question from tbl_quiz a left outer join (select quiz_id, count(*) as num_question from tbl_question group by quiz_id) b on a.quiz_id=b.quiz_id) b', 'a.quiz_id=b.quiz_id', 'inner');
            $this->db->where('a.quiz_type=2 and a.topic_id='.$topicid);
            $query = $this->db->get();
        } else {
            $this->db->select('*');
            $this->db->join('(select topic_id,quiz_id, count(*) as num_question from tbl_question group by quiz_id) b', 'a.quiz_id=b.quiz_id', 'left outer');
            $this->db->where('a.quiz_type=2');
            $this->db->or_where('a.quiz_type=3');
            $query = $this->db->get('tbl_quiz a');
        }


        return $query->result();
    }

    public function mf_load_quiz()
    {
        $this->db->select('*');
        $this->db->from('tbl_quiz');
        $query = $this->db->get();

        return $query->result();
    }

    public function mf_list_question_edit($quiz_id)
    {
        $query = $this->db->get_where('tbl_question', array('quiz_id' => $quiz_id));

        return $query->result();
        // return $query->num_rows();
    }

    public function mf_get_quiz_id($question_id)
    {
        $query = $this->db->get_where('tbl_question', array('question_id' => $question_id));
        $row = $query->row();
        return $row->quiz_id;
    }

    public function mf_list_question_option($question_id)
    {
        $query = $this->db->get_where('tbl_answer_option', array('qid' => $question_id));
        return $query->result();
    }
    public function mf_list_question_content($question_id)
    {
        $query = $this->db->get_where('tbl_question', array('question_id' => $question_id));
        return $query->result();
    }

    public function mf_list_question_home_last($quiz_id, $user_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_quiz_running_home');
        $this->db->where('quiz_id', $quiz_id);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();

        if (empty($query->result())){
            // $this->db->select('c.question_id, c.question_text, answer, quiz_PIN, c.question_type, c.question_no, c.question_answer');
            // $this->db->from('tbl_question c left join (select a.students_answer as answer, b.quiz_PIN, b.question_id from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id where a.id in (select max(a.id) from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id group by b.question_id)) d on c.question_id=d.question_id');
            // $this->db->where('c.quiz_id', $quiz_id);
            // $query = $this->db->get();
            // return $query->result();
            return null;
        }else{
            $row = $query->row();
            $last_pin_answered = $row->quiz_PIN;
    
            $pin = $last_pin_answered;
    
            $this->db->select('c.question_id, c.question_text, answer, quiz_PIN, c.question_type, c.question_no, c.question_answer');
            $this->db->from('tbl_question c left join (select a.students_answer as answer, b.quiz_PIN, b.question_id from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id where a.id in (select max(a.id) from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id where a.id_student='. $user_id .' and b.quiz_PIN=' . $pin . ' group by b.question_id)) d on c.question_id=d.question_id');
            $this->db->where('c.quiz_id', $quiz_id);
            $query = $this->db->get();
            return $query->result();
        }

        
    }

    public function mf_list_question_home($quiz_id, $user_id)
    {

        //create a view consisting of "question text"and "status answered"
        //"status answered"get the value from tbl_student_answer

        //1. list all question with quiz_id=$quiz_id
        //2. list all tbl_question_running.question_id, tbl_students_answer join tbl_question_running where tbl_question_running.quiz_PIN=$pin
        //3. (1) left join (2)


        $pin = $_SESSION['pin'];
        // to show the last session result when student open the quiz for the first time from home dashboard
        // check if there is $pin in tbl_question_running
        // if not, then retrieve for last quiz running before this pin

        // $this->db->select('*');
        // $this->db->from('tbl_question_running');
        // $this->db->where('quiz_PIN', $pin);
        // $query=$this->db->get();

        // if (empty($query->result())) {

        //     //retrieve last pin before $pin
        //     // 1. get quiz_id
        //     $this->db->select('*');
        //     $this->db->from('tbl_quiz_running_home');
        //     $this->db->where('quiz_PIN', $pin);
        //     $query=$this->db->get();

        //     $row=$query->row();
        //     $current_quiz_id=$row->quiz_id;

        //     // 2. get last pin before $pin
        //     $this->db->select('*');
        //     $this->db->from('tbl_quiz_running_home');
        //     $this->db->where('quiz_id', $current_quiz_id);
        //     $this->db->order_by('id','desc');
        //     $this->db->limit(1,1);
        //     $query=$this->db->get();

        //     $row=$query->row();
        //     $last_pin_answered=$row->quiz_PIN;

        //     $pin=$last_pin_answered;

        // }


        // echo $pin;


        // $this->db->select('*');
        // $this->db->from('tbl_question c left join (select b.question_id as status, a.students_answer as answer from tbl_student_answer a inner join tbl_question_running b where a.id_question_start = b.id and b.quiz_PIN='.$pin.' order by a.id desc limit 1) d on c.question_id=d.status');
        // $this->db->where('c.quiz_id',$quiz_id);
        // $query = $this->db->get();

        $this->db->select('c.question_id, c.question_text, answer, quiz_PIN, c.question_type, c.question_no, c.question_answer');
        $this->db->from('tbl_question c left join (select a.students_answer as answer, b.quiz_PIN, b.question_id from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id where a.id in (select max(a.id) from tbl_student_answer a inner join tbl_question_running b on a.id_question_start=b.id where a.id_student='. $quiz_id .' and b.quiz_PIN=' . $pin . ' group by b.question_id)) d on c.question_id=d.question_id');
        $this->db->where('c.quiz_id', $quiz_id);
        $query = $this->db->get();
        return $query->result();

        // return $this->db->get_compiled_select('tbl_question c left join (select b.question_id as status, a.students_answer as answer from tbl_student_answer a inner join tbl_question_running b where a.id_question_start = b.id and b.quiz_PIN='.$pin.') d on c.question_id=d.status where c.quiz_id=2');

        // $query=$this->db->get_where('tbl_question', array('quiz_id' => $quiz_id));

        // return json_encode($query->result());
    }

    public function mf_test_disable_question()
    {
        $this->db->select('*');
        $this->db->from('tbl_question c left join (select b.question_id from tbl_student_answer a inner join tbl_question_running b where a.id_question_start = b.id and b.quiz_PIN=7596) d on c.question_id=d.question_id');
        $this->db->get();
    }
}


// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web History 1','1','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web History 2','1','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','HTML 1','2','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','HTML 2','2','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','CSS 1','3','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','CSS 2','3','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Javascript 1','4','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Javascript 2','4','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','OOP 1','5','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','OOP 2','5','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','DOM 2','6','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','DOM 1','6','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Functional Programming 1','7','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Functional Programming 2','7','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','XML 1','8','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','XML 2','8','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','NodeJS 1','9','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','NodeJS 2','9','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','AJAX 1','10','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','AJAX 2','10','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','ExpressJS 1','11','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','ExpressJS 2','11','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web Security 1','12','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web Security 1','12','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web Security 2','13','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Web Security 2','13','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Social 1','14','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Social 2','14','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Semantic Web 1','15','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Semantic Web 2','15','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Adaptive Web 1','16','')
// INSERT INTO `tbl_quiz`(`quiz_id`, `quiz_title`, `topic_id`, `timestamp`) VALUES ('','Adaptive Web 2','16','')

// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '1', 'A program running on server machine, is called', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '2', 'Communication between browser and Web server takes place via', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '3', 'Parsed HTML code of in-memory tree structure is defined by a standard, called', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '4', 'Several technologies that are used by JavaScript to create dynamic web pages, are known as', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '5', 'Which of these are the front-end framework in web development', '135', '2', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '5', 'Sort CSS selectors from general to specific one', '135', '3', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '5', 'Match the protocol to the port', '1224314556687785', '4', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `quiz_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '1', '5', 'What does HTML stand for', 'Hypertext Markup Language', '5', '0', '0', current_timestamp());

// format answer for matching question: 1224314556687785 (one question followed by one answer: qaqaqaqaqaqa)
    
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '1', 'Parsed HTML code of in-memory tree structure is defined by a standard, called', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '2', 'Several technologies that are used by JavaScript to create dynamic web pages, are known as', '1', '1', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '3', 'Which of these are the front-end framework in web development', '135', '2', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '4', 'Sort CSS selectors from general to specific one', '135', '3', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '5', 'Match the protocol to the port', '1224314556687785', '4', '0', '0', current_timestamp());
// INSERT INTO `tbl_question` (`question_id`, `quiz_id`, `question_no`, `question_text`, `question_answer`, `question_type`, `status_start`, `status_stop`, `timestamp`) VALUES ('', '2', '6', 'What does HTML stand for', 'Hypertext Markup Language', '5', '0', '0', current_timestamp());
