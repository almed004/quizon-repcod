<?php


class model_running_question extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_check_pin()
    {
        $rannum = 0;
        $rows = 1;

        while ($rows <> 0) {
            $rannum = mt_rand(1000, 9999);
            $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $rannum));
            $rows = $query->num_rows();
        }

        //to do it later:
        //insert rannum to tbl_question_running
        //ambil nilai rannum yg tersimpan di database (untuk menghindari refresh)
        $data = array(
            'quiz_PIN' => $rannum
        );

        $this->db->insert('tbl_question_running', $data);
        return $rannum;
    }

    public function mf_check_pin_home()
    {
        $rannum = 0;
        $rows = 1;

        while ($rows <> 0) {
            $rannum = mt_rand(1000, 9999);
            $query = $this->db->get_where('tbl_quiz_running_home', array('quiz_PIN' => $rannum));
            $rows = $query->num_rows();
        }

        //to do it later:
        //insert rannum to tbl_question_running
        //ambil nilai rannum yg tersimpan di database (untuk menghindari refresh)
        $data = array(
            'quiz_PIN' => $rannum,
            'students_id' => $this->session->userdata('user_id'),
            'quiz_id' => $this->session->userdata('quiz_id')
        );

        $this->db->insert('tbl_quiz_running_home', $data);
        return $rannum;
    }

    //show student result rally
    public function mf_load_recap() {
        $query = $this->db->get_where('tbl_user', array('role'=>2));
        return $query->result();
    }
}
