<?php

class Model_course extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_get_course_data()
    {
        $query = $this->db->get_where('tbl_course', array('id' => 1));

        return $query->result();
    }
    public function mf_delete_topic($topicid)
    {
        $this->db->where('id', $topicid);
        $this->db->delete('tbl_topic');
    }
    public function mf_delete_quiz($quizid)
    {
        $this->db->where('quiz_id', $quizid);
        $this->db->delete('tbl_quiz');
    }
    public function mf_delete_question($questionid) {
        $this->db->where('question_id', $questionid);
        $this->db->delete('tbl_question');

        $this->db->where('qid', $questionid);
        $this->db->delete('tbl_answer_option');
    }

    

    public function mf_reorder_topic() {

    }

    public function mf_get_topic_by_id($topicid) {
        // if $topicid = 1 retrieve first row from tbl_topic
        if($topicid === 1) {
            $this->db->select('*');
            $this->db->from('tbl_topic');
            $this->db->limit('1');
            $query=$this->db->get();
        }else {
            $query=$this->db->get_where('tbl_topic', array('id'=> $topicid));
        }


        
        $row=$query->row();
        return $row->topic_title;
    }

    public function mf_check_quizintopic($topicid) {
        $query = $this->db->get_where('tbl_quiz',array('topic_id'=>$topicid ));
        if ($query->num_rows <> '0') {
            return true;
        } else {
            return false;
        }

    }

    public function mf_get_topic_data($topicid)
    {
        $query = $this->db->get_where('tbl_topic', array('id' => $topicid));

        return $query->result();
    }
    public function mf_get_quiz_data($quizid)
    {
        $query = $this->db->get_where('tbl_quiz', array('quiz_id' => $quizid));

        return $query->result();
    }

    public function mf_add_quiz_data($quiztitle,  $quiztopicid, $quizdesc, $quiztype) {
        $array = array(
            'quiz_title' => $quiztitle,
            'quiz_type' => $quiztype,
            'quiz_desc' => $quizdesc,
            'topic_id' => $quiztopicid
            
        );
        $this->db->insert('tbl_quiz', $array);
    }

    public function mf_edit_quiz_data($quiztitle,  $quizid, $quiztopicid, $quizdesc, $quiztype) {
        $array = array(
            'quiz_title' => $quiztitle,
            'quiz_type' => $quiztype,
            'quiz_desc' => $quizdesc,
            'topic_id' => $quiztopicid
            
        );

        
        $this->db->set($array);
        $this->db->where('quiz_id', $quizid);
        $this->db->update('tbl_quiz');
    }

    public function mf_add_topic_data($topictitle, $topicdesc)
    {
        //get last sequence number
        $this->db->select('max(topic_sequence) as last_seq');
        $query = $this->db->get('tbl_topic');
        $row = $query->row();

        $lasttopicsequence = $row->last_seq;
        $array = array(
            'topic_sequence' => $lasttopicsequence + 1,
            'topic_title' => $topictitle,
            'topic_desc' => $topicdesc,
            'course_id' => '1'  // default to WebTech Course
        );
        $this->db->insert('tbl_topic', $array);
    }

    public function mf_edit_topic_data($topicid, $topictitle, $topicdesc)
    {
        $array = array(

            'topic_title' => $topictitle,
            'topic_desc' => $topicdesc


        );
        $this->db->set($array);
        $this->db->where('id', $topicid);
        $this->db->update('tbl_topic');
    }

    public function mf_edit_course_data($idcourse, $coursename, $coursecode, $courseteacherid, $acadyear, $ects)
    {
        $array = array(
            'course_name' => $coursename,
            'course_code' => $coursecode,
            'course_teacher_id' => $courseteacherid,
            'acad_year' => $acadyear,
            'ects' => $ects


        );
        $this->db->set($array);
        $this->db->where('id', $idcourse);
        $this->db->update('tbl_course');
    }

    public function mf_get_coursename()
    {
        $query = $this->db->get_where('tbl_course', array('id' => 1));
        $row = $query->row();
        return $row->course_name;
    }
}
