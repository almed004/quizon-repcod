<?php

class model_question extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_list_question($id)
    {
        $pin = $_SESSION['pin'];


        $this->db->select('a.question_id, a.question_text, a.quiz_id, a.question_no, a.question_type, a.time_elapsed, a.status_start, a.status_disable, b.total');
        $this->db->from('(select a.*, tbl_question_running.time_elapsed from (select * from tbl_question where quiz_id=1) a left outer join tbl_question_running on a.question_id = tbl_question_running.question_id and tbl_question_running.quiz_PIN=' . $pin . ' order by question_no) a');
        $this->db->join('(SELECT question_id, count(*) as total FROM `tbl_student_answer` inner join tbl_question_running on tbl_student_answer.id_question_start=tbl_question_running.id and tbl_question_running.quiz_PIN=' . $pin . ' group by question_id) b', 'a.question_id=b.question_id', 'left');
        $this->db->order_by('a.question_no', 'ASC');
        $query = $this->db->get();

        $_SESSION['qid'] = $id; //quiz_id

        return $query->result();
    }

    public function mf_find_question($orderquestionid, $quizid) {
        // echo $orderquestionid;
        // echo $quizid;
        $query = $this->db->get_where('tbl_question', array('quiz_id'=> $quizid));
        $row = $query->row(intval($orderquestionid)-1);
        return $row;
        // return $query->result();
    }

    public function mf_list_question_alt($id)
    {
        $pin = $_SESSION['pin'];
        $question_no = $_SESSION['questionrun'];

        $this->db->select('a.question_id, a.question_text, a.quiz_id, a.question_no, a.question_type, a.time_elapsed, a.status_start, a.status_disable, b.total');
        $this->db->from('(select a.*, tbl_question_running.time_elapsed from (select * from tbl_question where quiz_id=1) a left outer join tbl_question_running on a.question_id = tbl_question_running.question_id and tbl_question_running.quiz_PIN=' . $pin . ' order by question_no) a');
        $this->db->join('(SELECT question_id, count(*) as total FROM `tbl_student_answer` inner join tbl_question_running on tbl_student_answer.id_question_start=tbl_question_running.id and tbl_question_running.quiz_PIN=' . $pin . ' group by question_id) b', 'a.question_id=b.question_id', 'left');
        $this->db->where('a.question_no', $question_no);
        $this->db->order_by('a.question_no', 'ASC');
        $query = $this->db->get();

        $_SESSION['qid'] = $id; //quiz_id

        return $query->result();
    }

    public function mf_list_question_ver_3($id)
    {
        $pin = $_SESSION['pin'];
        $question_no = $_SESSION['questionrun'];

        $this->db->select('a.question_id, a.question_text, a.question_adds, a.question_image, a.quiz_id, a.question_no, a.question_type, a.time_elapsed, a.status_start, a.status_disable, b.total');
        $this->db->from('(select a.*, tbl_question_running.time_elapsed from (select * from tbl_question where quiz_id=' . $id . ') a left outer join tbl_question_running on a.question_id = tbl_question_running.question_id and tbl_question_running.quiz_PIN=' . $pin . ' order by question_no) a');
        $this->db->join('(SELECT question_id, count(*) as total FROM `tbl_student_answer` inner join tbl_question_running on tbl_student_answer.id_question_start=tbl_question_running.id and tbl_question_running.quiz_PIN=' . $pin . ' group by question_id) b', 'a.question_id=b.question_id', 'left');
        $this->db->where('a.question_no', $question_no);
        $this->db->order_by('a.question_no', 'ASC');
        $query = $this->db->get();

        $_SESSION['qid'] = $id; //quiz_id

        return $query->row();
    }

    public function mf_update_question_content($questionid,$questiontext,$questionadds){
        $array = array(
            'question_text' => $questiontext,
            'question_adds' => $questionadds
        );
        $this->db->set($array);
        $this->db->where('question_id', $questionid);
        $this->db->update('tbl_question');
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function mf_update_option_content($questionid,$optionno, $optiontext) {
        $array = array(
            'option_text' => $optiontext
            
        );
        $this->db->set($array);
        $this->db->where('qid', $questionid);
        $this->db->where('option_no', $optionno);
        $this->db->update('tbl_answer_option');
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function mf_load_question()
    {

        $question_id = 1;
        $query = $this->db->get_where('tbl_question');
        return $query->result();
    }

    public function mf_update_question($id, $status)
    {
        if ($status === '0') {
            $cstatus = '1';
            $cstop = '0';
        } else {
            $cstatus = '0';
            $cstop = '1';
        }

        $array = array(
            'status_start' => $cstatus,
            'status_stop' => $cstop
        );
        $this->db->set($array);
        $this->db->where('question_id', $id);
        $this->db->update('tbl_question');
    }

    public function mf_start_first_question($quiz_id)
    {
        //update status_start of tbl_question where quiz_id=$quiz_id
        $array = array(
            'status_start' => '1',
            'status_stop' => '0'
        );
        $this->db->set($array);
        $this->db->where('quiz_id', $quiz_id);
        $this->db->where('question_no', '1');
        $this->db->update('tbl_question');

        //update first question (question_id=0) in tbl_question_running where quiz_PIN = $_SESSION['pin'];
        //1. mencari question_id dgn question_no pertama u/ quiz_id=$quiz_id
        //  select question_id from tbl_question where quiz_id=$quiz_id and question_no=1
        $query = $this->db->get_where('tbl_question', array('quiz_id' => $quiz_id, 'question_no' => 1));
        $row = $query->row();
        $question_id = $row->question_id;

        $array = array(
            'question_id' => $question_id,

        );
        $this->db->set($array);
        $this->db->where('quiz_PIN', $_SESSION['pin']);
        $this->db->update('tbl_question_running');
    }

    public function mf_find_question_no($id)
    {
        $query = $this->db->get_where('tbl_question', array('question_id' => $id));
        $row = $query->row();
        return $row->question_no;
    }

    public function mf_first_question($id, $pin)
    {

        //cek adakah di tbl_question_running dgn PIN saat ini yang question_id=0
        //jika ada, update tbl_question_running set (question_id) = $id where PIN=$pin

        $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $pin, 'question_id' => 0));
        $rows = $query->num_rows();
        // return $rows;
        if ($rows === 1) {
            $array = array(
                'question_id' => $id
            );
            $this->db->set($array);
            $this->db->where('quiz_PIN', $pin);

            $this->db->update('tbl_question_running');
        } else {
            //cari adakah row yg pin dan question id nya sesuai dengan $id dan $pin, jika tidak ada, maka insert
            //jika ada maka update
            $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $pin, 'question_id' => $id));
            $rows = $query->num_rows();
            if ($rows === 0) {
                $array = array(
                    'question_id' => $id,
                    'quiz_PIN' => $pin

                );
                $this->db->insert('tbl_question_running', $array);
            }
        }
    }

    public function mf_update_time($id, $time, $pin)
    {
        $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $pin, 'question_id' => $id));
        $rows = $query->num_rows();
        // return $rows;
        if ($rows === 1) {
            //update
            $array = array(

                'time_elapsed' => $time
            );
            $this->db->set($array);
            $this->db->where('quiz_PIN', $pin);
            $this->db->where('question_id', $id);
            $this->db->update('tbl_question_running');
        }

        //disable the start button
        $array = array(
            'status_disable' => 1

        );
        $this->db->set($array);
        $this->db->where('question_id', $id);
        $this->db->update('tbl_question');
    }

    public function mf_clear_quiz()
    {
        $array = array(
            'status_start' => '0',
            'status_stop' => '1',
            'status_disable' => '0',
            'status_show' => '0'
        );
        $this->db->set($array);
        $this->db->update('tbl_question');
    }

    public function mf_save_question($quizid, $questiontype, $questiontext, $questionadds, $questionanswer, $questionimage)
    {
        //get last number from tbl_question where quiz_id=$quizid, add with 1
        $this->db->select('*');
        $this->db->from('tbl_question');
        $this->db->where('quiz_id', $quizid);
        $this->db->order_by('question_no', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() === 0) {
            $questionno = 1;
        } else {
            $row = $query->row();
            $questionno = $row->question_no + 1;
        }

        $array = array(

            'quiz_id' => $quizid,
            'question_no' => $questionno,
            'question_text' => $questiontext,
            'question_adds' => $questionadds,
            'question_answer' => $questionanswer,
            'question_image' => $questionimage,

            'question_type' => $questiontype,
            'status_start' => 0,
            'status_stop' => 1,
            'status_disable' => 0,
            'status_show' => 0

        );
        $query = $this->db->insert('tbl_question', $array);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mf_save_question_option($optionno, $optiontext, $quizid)
    {
        $this->db->select('*');
        $this->db->from('tbl_question');
        $this->db->where('quiz_id', $quizid);
        $this->db->order_by('question_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        $question_id = $row->question_id;

        $array = array(
            'qid' => $question_id,
            'option_no' => $optionno,
            'option_text' => $optiontext
        );

        $query = $this->db->insert('tbl_answer_option', $array);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mf_display_source()
    {
        $query = $this->db->get_where('tbl_question', array('question_id' => '1'));
        $row = $query->row();
        return $row->question_adds;
    }

    public function mf_load_topic()
    {
        $this->db->select('*');
        $this->db->from('tbl_topic');
        $query = $this->db->get();
        return $query->result();
    }
    public function mf_load_quiz()
    {
        $this->db->select('*');
        $this->db->from('tbl_quiz');
        $query = $this->db->get();

        return $query->result();
    }
}
