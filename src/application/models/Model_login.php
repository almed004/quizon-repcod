<?php

class Model_login extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_save_login_data($studentid, $browser, $browserversion, $platform, $inbattery, $batteryLevel, $mobile)
    {  
        
        $array = array(
            'user_id' => $studentid,
            'browser' => $browser,
            'browser_version' => $browserversion,
            'platform' => $platform,
            'isCharging' => $inbattery,
            'batteryLevel'=>$batteryLevel,
            'mobile' => $mobile,
            'status_login' => 1,
            'login_time' => (new \DateTime())->format('Y-m-d H:i:s')         
           
        );
        $this->db->insert('tbl_login_time', $array);

        
    }

    public function mf_check_userid($userid) {
        $query=$this->db->get_where('tbl_user', array('user_id'=>$userid));
        return ($query->num_rows <> 0) ? false : true;
        // return $query->result();

    }
    public function mf_save_registration_data($userid, $firstname, $lastname, $email, $userrole, $password)
    {

        $array = array(
            'user_id' => $userid,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,            
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $userrole
        );
        $this->db->insert('tbl_user', $array);
    }

    public function mf_get_username($studentid) {
        $query=$this->db->get_where('tbl_user', array('user_id'=>$studentid));
        $row=$query->row();
        return $row->first_name;
    }    
    public function mf_get_username_teacher($email) {
        $query=$this->db->get_where('tbl_user', array('email'=>$email));
        $row=$query->row();
        return $row->first_name;
    }    

    public function mf_set_id_login_session($studentid) {
        $this->db->select('*');
        $this->db->from('tbl_login_time');
        $this->db->where('user_id', $studentid);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query=$this->db->get();
        $row=$query->row();
        $id=$row->id;
        $this->session->set_userdata('login_id',$id);
    }

    public function mf_save_logout_data() {
        // find in tbl_login_time user_id=$userid, status_login=1, update status login with 0
        // $userid=$this->session->userdata('user_id');
        // $query= $this->db->get_where('tbl_login_time', array('user_id'=> $userid,'status_login'=>1));
        // $row=$query->row();

        // $loginid=$row->id;
        $loginid=$this->session->userdata('login_id');
        
        $array = array(
            'status_login'=> 0,
            'logout_time' => (new \DateTime())->format('Y-m-d H:i:s')
        );
        $this->db->set($array);
        $this->db->where('id', $loginid);
        $this->db->update('tbl_login_time');
    }

    public function mf_save_logout_data_teacher() {
        // find in tbl_login_time email=$email, status_login=1, update status login with 0
        $email=$this->session->userdata('email');
        $query= $this->db->get_where('tbl_login_time', array('user_id'=> $email,'status_login'=>1));
        $row=$query->row();

        $loginid=$row->id;

        
        $array = array(
            'status_login'=> 0,
            'logout_time' => (new \DateTime())->format('Y-m-d H:i:s')
        );
        $this->db->set($array);
        $this->db->where('id', $loginid);
        $this->db->update('tbl_login_time');
    }

    public function mf_change_password_student($newpassword_conf, $userid) {
       
        $array = array(
            'password' => password_hash($newpassword_conf, PASSWORD_DEFAULT),
        );
        $this->db->set($array);
        $this->db->where('user_id', $userid);
        $this->db->update('tbl_user');
        if ($this->db->affected_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }

    }
    public function mf_change_password_teacher($newpassword_conf, $email) {
       
        $array = array(
            'password' => password_hash($newpassword_conf, PASSWORD_DEFAULT),
        );
        $this->db->set($array);
        $this->db->where('email', $email);
        $this->db->update('tbl_user');
        if ($this->db->affected_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function mf_list_all_students($limit, $start) {
        $this->db->select('user_id');
        $this->db->where('role','2');
        $query=$this->db->get('tbl_user', $limit, $start);
        return $query->result();
    }
}
