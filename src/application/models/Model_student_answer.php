<?php

class model_student_answer extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function mf_list_scores($studentid)
    {
        $this->db->select('b.id_student, b.students_answer, c.question_answer, b.time_answer, a.question_id, max(b.id)');
        $this->db->from('tbl_question_running a');
        $this->db->join('tbl_student_answer b', 'a.id = b.id_question_start', 'inner');
        $this->db->join('tbl_question c', 'a.question_id=c.question_id', 'inner');
        $this->db->where('a.question_id in (select question_id from tbl_question where quiz_id=33)');
        $this->db->where('b.id_student=' . $studentid);
        $this->db->group_by('a.question_id, b.id_student');
        $query = $this->db->get();

        return $query->result();
    }

    public function mf_count_answer($question_no, $pin)
    {
        //id_start= get question_running(id) where question_id=$question_id and pin=$pin
        //count student_answer where id_question_start=id_start

        $query = $this->db->get_where('tbl_question', array('quiz_id' => $_SESSION['quiz_id'], 'question_no' => $question_no));
        $row = $query->row();
        $question_id = $row->question_id;


        $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $pin, 'question_id' => $question_id));
        $row = $query->row();
        $id_start = $row->id;
        // $id_start = 2365;



        $query = $this->db->get_where('tbl_student_answer', array('id_question_start' => $id_start));
        $rows = $query->num_rows();
        return $rows;
    }

    public function mf_update_student_show($sid, $user_id)
    {
        $array = array(

            'student_show' => '0'

        );
        $this->db->set($array);
        $this->db->where('id_question_start', $sid);
        $this->db->where('id_student', $user_id);
        $this->db->update('tbl_student_answer');
    }
    public function mf_read_student_show_status($sid, $userid)
    {
        $query = $this->db->get_where('tbl_student_answer', array('id_question_start' => $sid, 'id_student' => $userid));
        // if ($query->num_rows() != 0) {
        $row = $query->row();
        $statusshow = $row->student_show;

        return $statusshow;
        // }

    }

    public function mf_answer_result($id, $pin)
    {
        //identifikasi ada berapa option di tbl_answer_option
        //isi tiap option dgn jawaban yang ada di tbl_student_answer
        // $id_start = '990';
        // $i = 1;
        // $query_answer_1 = $this->db->get_where('tbl_student_answer', array('id_question_start' => $id_start, 'students_answer' => $i));
        $qid = $id; //question_id
        $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $pin, 'question_id' => $qid));

        $row = $query->row();
        $sid = $row->id; //id_question_start


        $studentid = $this->session->userdata('user_id');

        $query = $this->db->get_where('tbl_student_answer', array('id_question_start' => $sid, 'id_student' => $studentid));
        $row = $query->row();
        // if ($row->students_answer === '') {
        //     echo "null";
        // } else {
        //     echo 'not null';
        // }
        if ($query->num_rows() > 0 && $row->students_answer !== '') {
            $num_option = $row->students_answer;

            //translate student answer to tbl_answer_option.option_text
            $query = $this->db->get_where('tbl_answer_option', array('qid' => $qid, 'option_no' => $num_option));
            $row = $query->row();
            if ($query->num_rows() !== 0) {
                $studentanswer = $row->option_text;
            }
            //counter with correct answer in tbl_question.question_answer;
            $query = $this->db->get_where('tbl_question', array('question_id' => $qid));
            $row = $query->row();
            $correctanswer = $row->question_answer;
            if ($num_option === $correctanswer) {
                $answeriscorrect = true;
            } else {
                $answeriscorrect = false;
            }
        } else {
            $studentanswer = "";
            $answeriscorrect = "";
        }


        //retrieve answer options
        $query = $this->db->get_where('tbl_question', array('question_id' => $qid));
        $row = $query->row();
        $questiontext = $row->question_text;
        $questionno = $row->question_no;
        $questionanswer = $row->question_answer;
        $questiontype = $row->question_type;

        $query = $this->db->get_where('tbl_answer_option', array('qid' => $qid));

        $numrows = $query->num_rows();

        $thelabel = array();
        $i = 0;
        while ($i < $numrows) {
            $thelabel[$i] = $query->row($i)->option_text;
            $i++;
        }

        //retrieve total answer per option
        $numrows = $query->num_rows();

        $theanswer = array();
        $i = 0;

        switch ($questiontype) {
            case '1': {

                    while ($i < $numrows) {
                        $query = $this->db->get_where('tbl_student_answer', array('id_question_start' => $sid, 'students_answer' => $i + 1));
                        $theanswer[$i] = $query->num_rows();
                        $i++;
                    }

                    $theoption = array(
                        'questiontext' => htmlspecialchars_decode($questiontext),
                        'questionno' => $questionno,
                        'questiontype' => $questiontype,
                        'questionanswer' => $questionanswer,
                        'label1' => $thelabel[0],
                        'label2' => $thelabel[1],
                        'label3' => $thelabel[2],
                        'label4' => $thelabel[3],

                        'total1' => json_encode($theanswer[0]),
                        'total2' => json_encode($theanswer[1]),
                        'total3' => json_encode($theanswer[2]),
                        'total4' => json_encode($theanswer[3]),
                        'studentanswer' => $studentanswer,
                        'answerstatus' => $answeriscorrect,
                        'idquestionstart' => $sid
                    );
                    break;
                }
            case '2': {
                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    $total4 = 0;
                    $total5 = 0;
                    $total6 = 0;

                    $query = $this->db->get_where('tbl_student_answer', array('id_question_start' => $sid));
                    $i = 0;
                    $a = '';
                    $j = 0;
                    // $array = array("blue", "red", "green", "blue", "blue");
                    // print_r(array_keys($array, "green"));
                    // 

                    while ($i < $query->num_rows()) {
                        $row = $query->row($i);
                        $stuans = $row->students_answer;
                        //convert from 110100 to 124
                        $array = str_split($stuans);

                        $array1 = array_keys($array, "1");


                        for ($j = 0, $len = count($array1); $j < $len; $j++) {
                            $b = $array1[$j];
                            $b++;
                            $a = $a . $b;
                        }



                        $arr1 = str_split($a);
                        $j = 0;
                        while ($j < count($arr1)) {
                            switch ($arr1[$j]) {
                                case '1':
                                    $total1++;
                                    break;
                                case '2':
                                    $total2++;
                                    break;
                                case '3':
                                    $total3++;
                                    break;
                                case '4':
                                    $total4++;
                                    break;
                                case '5':
                                    $total5++;
                                    break;
                                case '6':
                                    $total6++;
                                    break;
                            }

                            $j++;
                        }
                        $i++;
                    }

                    $theoption = array(
                        'questiontext' => $questiontext,
                        'questionno' => $questionno,
                        'questiontype' => $questiontype,
                        'label1' => $thelabel[0],
                        'label2' => $thelabel[1],
                        'label3' => $thelabel[2],
                        'label4' => $thelabel[3],
                        'label5' => $thelabel[4],
                        'label6' => $thelabel[5],

                        'total1' => $total1,
                        'total2' => $total2,
                        'total3' => $total3,
                        'total4' => $total4,
                        'total5' => $total5,
                        'total6' => $total6

                    );
                    break;
                }

            case '3': {
                    // $total1 = 0;
                    // $total2 = 0;
                    // $total3 = 0;
                    // $total4 = 0;
                    // $total5 = 0;
                    // $total6 = 0;


                    // $this->db->select('students_answer, count(*) as total');
                    // $this->db->from('tbl_student_answer');
                    // $this->db->where('id_question_start', $sid);
                    // $this->db->group_by("students_answer");
                    // $this->db->limit(4);
                    // $query = $this->db->get();

                    // //get group 1 to 4
                    // //find the name of sequence in a tbl_answer_option
                    // //put it in one string
                    // $group = array();
                    // $i = 0;

                    // while ($i < 4) {
                    //     $row = $query->row($i);

                    //     list($op1, $op2, $op3, $op4) = explode(",", $row->students_answer);

                    //     $query1 = $this->db->get_where('tbl_answer_option', array('qid' => $qid, 'option_no' => $op1));
                    //     $query2 = $this->db->get_where('tbl_answer_option', array('qid' => $qid, 'option_no' => $op2));
                    //     $query3 = $this->db->get_where('tbl_answer_option', array('qid' => $qid, 'option_no' => $op3));
                    //     $query4 = $this->db->get_where('tbl_answer_option', array('qid' => $qid, 'option_no' => $op4));
                    //     $rowop1 = $query1->row()->option_text;
                    //     $rowop2 = $query2->row()->option_text;
                    //     $rowop3 = $query3->row()->option_text;
                    //     $rowop4 = $query4->row()->option_text;

                    //     if ($i < $query->num_rows()) {
                    //         // $thelabel[$i] = $row->students_answer;"
                    //         $groupno = $i + 1;
                    //         // $thelabel[$i] = "Group " . $groupno;
                    //         $thelabel[$i] = $rowop1 . " " . $rowop2 . " " . $rowop3 . " " . $rowop4;
                    //         // $thelabel[$i] = "Class, ID, Type, Universal ";
                    //         $theanswer[$i] = $row->total;
                    //     } else {
                    //         // echo "kosong";
                    //         $thelabel[$i] = '';
                    //         $theanswer[$i] = 0;
                    //     }
                    //     $i++;
                    // }

                    $theoption = array(
                        'questiontext' => $questiontext,
                        'questiontype' => $questiontype,
                        'questionno' => $questionno,
                        'idquestionstart' => $sid
                        // 'label1' => $thelabel[0],
                        // 'label2' => $thelabel[1],
                        // 'label3' => $thelabel[2],
                        // 'label4' => $thelabel[3],
                        // // 'label5' => $thelabel[4],
                        // // 'label6' => $thelabel[5],
                        // 'total1' => $theanswer[0],
                        // 'total2' => $theanswer[1],
                        // 'total3' => $theanswer[2],
                        // 'total4' => $theanswer[3]
                        // 'total5' => $theanswer[4],
                        // 'total6' => $theanswer[5]
                    );


                    break;
                }

            case '4': { //matching
                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    $total4 = 0;
                    $total5 = 0;
                    $total6 = 0;


                    $this->db->select('students_answer, count(*) as total');
                    $this->db->from('tbl_student_answer');
                    $this->db->where('id_question_start', $sid);
                    $this->db->group_by("students_answer");
                    $query = $this->db->get();

                    while ($i < 6) {

                        $row = $query->row($i);
                        if ($i < $query->num_rows()) {
                            // $thelabel[$i] = $row->students_answer;
                            $groupno = $i + 1;
                            $thelabel[$i] = "Group " . $groupno;
                            $theanswer[$i] = $row->total;
                        } else {
                            // echo "kosong";
                            $thelabel[$i] = '-';
                            $theanswer[$i] = '';
                        }

                        $i++;
                    }

                    $theoption = array(
                        'questiontext' => $questiontext,
                        'questionno' => $questionno,
                        'questiontype' => $questiontype,
                        'label1' => $thelabel[0],
                        'label2' => $thelabel[1],
                        'label3' => $thelabel[2],
                        'label4' => $thelabel[3],
                        'label5' => $thelabel[4],
                        'label6' => $thelabel[5],
                        'total1' => $theanswer[0],
                        'total2' => $theanswer[1],
                        'total3' => $theanswer[2],
                        'total4' => $theanswer[3],
                        'total5' => $theanswer[4],
                        'total6' => $theanswer[5]
                    );
                    break;
                }

            case '5': { //shortanswer
                    //retrieve all student answer
                    //retrieve the question answer
                    //cluster the student answers based its similarity
                    //put same answers into one group


                    // $query=$this->db->get_where('tbl_student_answer', array('id_question_start', $sid));
                    // $j=0;
                    // while ($j < $query->num_rows()) {
                    //     //clustering

                    // }

                    $this->db->select('students_answer');
                    $this->db->from('tbl_student_answer');
                    $this->db->where('id_question_start', $sid);
                    // echo $sid;
                    $query = $this->db->get();
                    // echo json_encode($query->result());
                    // $label=json_encode($query->result());
                    // $label=$query->result();
                    $label = array();
                    // $row = $query->row();
                    // echo json_encode($query->result());

                    foreach ($query->result() as $key) {
                        array_push($label, $key->students_answer);
                    }

                    // $i = 0;
                    // while ($i <= $query->num_rows()) {
                    //     array_push($label,$row->students_answer);
                    //     $i++;
                    // }

                    $theoption = array(
                        'questiontext' => htmlspecialchars_decode($questiontext),
                        'questionno' => $questionno,
                        'questiontype' => $questiontype,
                        'questionanswer' => $questionanswer,
                        'label' => $label,
                        'idquestionstart' => $sid,
                        'studentanswer' => array('html', 'html', 'head', 'head', 'head', 'body', 'html', 'html', 'html', 'html', 'body', 'body', 'body', 'body', 'body', 'body', 'body', 'body', 'script')
                    );

                    break;
                }
        }

        return $theoption;
    }

    public function mf_test_result_sorting()
    {
        $sid = "549";
        $this->db->select('students_answer, count(*) as total');
        $this->db->from('tbl_student_answer');
        $this->db->where('id_question_start', $sid);
        $this->db->group_by("students_answer");
        $this->db->limit(4);
        $query = $this->db->get();

        $group = array();

        $i = 0;

        while ($i < $query->num_rows()) {
            $row = $query->row($i);
            $i++;

            list($op1, $op2, $op3, $op4) = explode(",", $row->students_answer);

            $query1 = $this->db->get_where('tbl_answer_option', array('qid' => 12, 'option_no' => $op1));
            $query2 = $this->db->get_where('tbl_answer_option', array('qid' => 12, 'option_no' => $op2));
            $query3 = $this->db->get_where('tbl_answer_option', array('qid' => 12, 'option_no' => $op3));
            $query4 = $this->db->get_where('tbl_answer_option', array('qid' => 12, 'option_no' => $op4));
            $rowop1 = $query1->row()->option_text;
            $rowop2 = $query2->row()->option_text;
            $rowop3 = $query3->row()->option_text;
            $rowop4 = $query4->row()->option_text;
            array_push($group, $rowop1 . " " . $rowop2 . " " . $rowop3 . " " . $rowop4);
        }


        return $group[0];
        // return $row1->students_answer;


    }

    public function mf_load_question($id)
    {
        $query = $this->db->get_where('tbl_question', array('question_id' => $id));
        if ($query->num_rows() === 0) {
            return 0;
        } else {
            return $query->result();
        }
    }


    public function mf_load_option($id)
    {
        // echo $id;
        $query = $this->db->get_where('tbl_answer_option', array('qid' => $id));

        return $query->result();
    }

    public function mf_load_matching_option($id)
    {
        $query = $this->db->get_where('tbl_matching_option', array('qid' => $id));
        return $query->result();
    }

    public function mf_load_question_text()
    {

        $query = $this->db->get_where('tbl_question', array('status_start' => '1'));
        if ($query->num_rows() === 1) {
            return $query->result();
        }
    }

    public function mf_load_question_start()
    {
        //alternative 3
        // $query_start = $this->db->get_where('tbl_question a inner join tbl_question_running b on a.question_id=b.question_id ', array('a.status_start' => 1));
        $this->db->select('*');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_question_running b', 'a.question_id=b.question_id', 'inner');
        $this->db->where('a.status_start', 1);
        $this->db->order_by('b.id', 'DESC');
        $this->db->limit(1);
        $query_start = $this->db->get();

        $this->db->select('*');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_question_running b', 'a.question_id=b.question_id', 'inner');
        $this->db->where('status_show', 1);
        $this->db->limit(1);
        $query_show = $this->db->get();

        // $query_show = $this->db->get_where('tbl_question a inner join tbl_question_running b on a.question_id=b.question_id', array('a.status_show' => 1));
        if ($query_start->num_rows() === 0) {
            if ($query_show->num_rows() === 0) {
                return 0;
            } else {
                return $query_show->result();
            }
        } else {
            return $query_start->result();
            // return "hello";
            // return $query_start->num_rows();
        }


        //alternative 1
        // $query = $this->db->get_where('tbl_question,tbl_question_running', array('tbl_question.status_start' => 1));
        // if ($query->num_rows() === 0) {
        //     //status show
        //     $query = $this->db->get_where('tbl_question,tbl_question_running', array('tbl_question.status_show' => 1));
        //     if ($query->num_rows() === 0) {
        //         return 0;
        //     } else {
        //         return $query->result();
        //     }
        // } else {
        //     return $query->result();
        // }

        //alternative 2
        // $this->db->select('*');
        // $this->db->from('tbl_question_running, tbl_question');
        // $this->db->where('tbl_question_running.question_id=tbl_question.question_id');
        // $this->db->where('tbl_question_running.time_elapsed=0');        
        // $this->db->order_by('tbl_question_running.id', 'desc');
        // $this->db->limit(1);
        // $query = $this->db->get();
        // // echo json_encode($query->result());
        // // $query = $this->db->get('tbl_question_running, tbl_question');

        // // $query=$this->db->get_where('tbl_question', array('status_start' => 1));
        // $row = $query->row();
        // if ($row->status_start === '1') {
        //     return $query->result();
        // } else {
        //     return 0;
        // }
    }

    public function mf_test_load()
    {
        $this->db->select('*');
        $this->db->from('tbl_question_running, tbl_question');
        $this->db->where('tbl_question_running.question_id=tbl_question.question_id');
        $this->db->where('tbl_question_running.time_elapsed=0');
        $this->db->order_by('tbl_question_running.id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        // echo json_encode($query->result());
        // $query = $this->db->get('tbl_question_running, tbl_question');

        // $query=$this->db->get_where('tbl_question', array('status_start' => 1));
        $row = $query->row();
        if ($row->status_start === '1') {
            return json_encode($query->result());
        } else {
            return 0;
        }
    }

    public function mf_load_pin_student()
    {
        $this->db->select('*');
        $this->db->from('tbl_question_running');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        return $row->quiz_PIN;
    }

    public function mf_submit_answer($iqs, $san, $ids)
    {

        $array = array(
            'id_question_start' => $iqs,
            'id_student' => $ids,
            'students_answer' => $san,
            'student_show' => 1

        );
        $this->db->insert('tbl_student_answer', $array);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function mf_update_qr()
    {
        $pin = "8661";

        $idquestion = "444";

        $array = array(
            'question_id' => $idquestion,

        );
        $this->db->set($array);
        $this->db->where('quiz_PIN', $pin);
        $this->db->update('tbl_question_running');
    }

    public function mf_new_question_begin($questionsidstart)
    {


        $array = array(
            'id_question_start' => $questionsidstart,
            'id_student' => $this->session->userdata('user_id'),
            'time_open' => date("Y-m-d H:i:s")


        );

        $this->db->insert('tbl_student_answer', $array);

        //get the answer id, save it to session
        $this->db->select('*');
        $this->db->where('id_question_start', $questionsidstart);
        $this->db->where('id_student', $this->session->userdata('user_id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get('tbl_student_answer');
        $row = $query->row();
        $idsan = $row->id;

        $newsession = array(
            'idanswer' => $idsan
        );
        $this->session->set_userdata($newsession);

        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function mf_finish_question($idanswer)
    {
        $this->db->delete('tbl_student_answer', array('id' => $idanswer));
    }
    public function mf_submit_answer_home($iqs, $san, $ids, $idquestion, $antext)
    {
        //update tbl_question_running
        $pin = $_SESSION['pin'];

        $idquestion = $idquestion;

        // $array = array(
        //     'question_id' => $idquestion,

        // );
        // $this->db->set($array);
        // $this->db->where('quiz_PIN', $pin);
        // $this->db->update('tbl_question_running');
        // echo $this->session->userdata('idanswer');
        //version 2
        $array = array(
            'students_answer' => $san,
            'time_answer' => date("Y-m-d H:i:s")

        );
        $this->db->set($array);
        $this->db->where('id', $this->session->userdata('idanswer'));
        $this->db->update('tbl_student_answer');


        $newsession = array(
            'studentanswer' => $san,
            'answertext' => $antext
        );
        $this->session->set_userdata($newsession);

        // $this->db->insert('tbl_student_answer', $array);    

        //version 1
        // $array = array(
        //     'id_question_start' => $iqs,
        //     'id_student' => $ids,
        //     'students_answer' => $san

        // );


        // $this->db->insert('tbl_student_answer', $array);
        return ($this->db->affected_rows() !== 1) ? false : true;
    }

    public function mf_check_shortanswer($correctanswer, $studentanswer)
    {
        $my_url = $studentanswer;
        // if (preg_match("/extensible markup language/", strtolower($my_url))) {

        if (preg_match("/" . strtolower($correctanswer) . "/", strtolower($my_url))) {
            return true;
        } else {
            return false;
        }


        // $arr_studentanswer = explode(' ', $studentanswer);

        // $stu = '';
        // $i = 0;
        // for ($i === 0; $i <= count($arr_studentanswer) - 1; $i++) {
        //     $stu .= '(?=.*\b' . $arr_studentanswer[$i] . '\b)';
        // }
        // // echo $stu;
        // if (preg_match("/" . $stu . "/", strtolower($correctanswer), $matches[2]) && $studentanswer <> '') {
        // // if (strpos(strtolower($studentanswer), strtolower($correctanswer)) && $studentanswer <> '') {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    public function mf_check_mcsa($correctanswer, $studentanswer)
    {
        return ($correctanswer === $studentanswer) ? true : false;
    }
    public function mf_check_answer()
    {
        $id_student = $_SESSION['user_id'];

        // $quiz_id= 2;
        $this->db->select('*');
        $this->db->from('tbl_question_running');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        $question_id_active = $row->question_id;

        $query = $this->db->get_where('tbl_question', array('question_id' => $question_id_active));
        $row = $query->row();
        $quiz_id = $row->quiz_id;

        //retrieve tbl_question_running last row (active question -> in class quiz)
        $this->db->from("tbl_question_running");
        $this->db->where("question_id in (select question_id from tbl_question a inner join tbl_quiz b where a.quiz_id=b.quiz_id and b.quiz_id=$quiz_id)");
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();

        $row = $query->row();
        $question_id_start = $row->id;

        //retrieve row from tbl_student_answer where id_question_start=$question_id_start and id_student=xxx (ambil dari session login)
        $this->db->select('*');
        $this->db->where('id_question_start', $question_id_start);
        $this->db->where('id_student', $id_student); //pertanyaan tidak akan tampil jika tidak ada nama / id student di tbl_student_answer untuk kode pertanyaan yg sedang running, switch/kunci tampil/tidak pertanyaan ada di sini
        $query = $this->db->get('tbl_student_answer');


        // $query=$this->db->get_where('tbl_student_answer', array('id_question_start'=>$question_id_start, 'id_student' => 'eummy001'));
        if ($query->num_rows() === 0) { //belum menjawab
            return 0;
        } else { //sudah menjawab
            return 1;
        }
    }

    public function mf_get_idstart_home($id)
    {
        $query = $this->db->get_where('tbl_question_running', array('quiz_PIN' => $_SESSION['pin'], 'question_id' => $id));
        $row = $query->row();
        return $row->id;
    }

    public function mf_new_pin($id)
    {
        $pin = $_SESSION['pin'];

        $array = array(
            'quiz_PIN' => $pin,
            'question_id' => $id

        );
        $this->db->insert('tbl_question_running', $array);
    }

    public function mf_check_answer_home($question_id_start)
    {

        $id_student = $this->session->userdata('user_id');
        $pin = $this->session->userdata('pin');
        // echo $pin;
        //retrieve tbl_question_running last row (active question -> in class quiz)
        $this->db->from("tbl_question_running");
        $this->db->where("quiz_PIN='$pin' and question_id='$question_id_start'");
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();

        $row = $query->row();
        $question_id_start = $row->id;

        //retrieve row from tbl_student_answer where id_question_start=$question_id_start and id_student=xxx (ambil dari session login)
        $this->db->select('*');
        $this->db->where('id_question_start', $question_id_start);
        $this->db->where('id_student', $id_student); //pertanyaan tidak akan tampil jika tidak ada nama / id student di tbl_student_answer untuk kode pertanyaan yg sedang running, switch/kunci tampil/tidak pertanyaan ada di sini
        $query = $this->db->get('tbl_student_answer');


        // $query=$this->db->get_where('tbl_student_answer', array('id_question_start'=>$question_id_start, 'id_student' => 'eummy001'));
        if ($query->num_rows() === 0) { //no answer yet
            return 0;
        } else { //already answered
            return 1;
        }
    }

    public function mf_check_started_question()
    {
        $query = $this->db->get_where('tbl_question', array('status_start' => '1'));
        return $query->num_rows();
    }

    public function mf_test()
    {

        // $this->db->select('SELECT * FROM tbl_question_running', FALSE);
        $this->db->select('*');
        $this->db->where('tbl_question_running.question_id=tbl_question.question_id');
        $this->db->order_by('tbl_question_running.id', 'desc');
        $this->db->limit(1);

        $query_test = $this->db->get('tbl_question_running, tbl_question');
        return $query_test->result();
    }

    public function mf_check_login($studentid, $password)
    {

        $query = $this->db->get_where('tbl_user', array('user_id' => $studentid));
        $hash = $query->row()->password;
        if (password_verify($password, $hash)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mf_check_login_teacher($email, $password)
    {

        $query = $this->db->get_where('tbl_user', array('email' => $email));
        $hash = $query->row()->password;
        $role = $query->row()->role;
        if (password_verify($password, $hash) && $role === '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mf_retrieve_username($userid)
    {
        $query = $this->db->get_where('tbl_user', array('user_id' => $userid));
        $row = $query->row();
        return $row->first_name;
    }


    public function mf_retrieve_username_teacher($email)
    {
        $query = $this->db->get_where('tbl_user', array('email' => $email));
        $row = $query->row();
        return $row->first_name;
    }


    public function mf_check_pin($pin)
    {
        $this->db->select('*');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query_pin = $this->db->get('tbl_question_running');
        $row = $query_pin->row();
        $pin_from_db = $row->quiz_PIN;
        if ($pin === $pin_from_db) {
            return TRUE;
        } else {
            return FALSE;
        }
    }



    public function mf_save_registration_data_bulk($studentid, $firstname, $lastname, $password)
    {
        // this function for insert login data for student
        $array = array(
            'user_id' => $studentid,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 2
        );

        $this->db->insert('tbl_user', $array);
    }

    public function mf_save_registration_data_teacher($teacherid, $firstname, $lastname, $email, $password)
    {
        // this function for insert login data for student
        $array = array(
            'user_id' => $teacherid,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 1
        );

        $this->db->insert('tbl_user', $array);
    }

    public function mf_save_registration_data_tester($teacherid, $firstname, $lastname, $email, $password)
    {
        // this function for insert login data for student
        $array = array(
            'user_id' => $teacherid,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 3
        );

        $this->db->insert('tbl_user', $array);
    }

    public function mf_retrieve_last_pin()
    {
        $this->db->select('*');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query_pin = $this->db->get('tbl_question_running');
        $row = $query_pin->row();
        $pin_from_db = $row->quiz_PIN;
        return $pin_from_db;
    }

    public function mf_update_show($id)
    {
        //update tbl_question(status_show) = 1 where question_id=$id
        $cstatus = 1; //showing result
        $array = array(
            'status_show' => $cstatus,

        );
        $this->db->set($array);
        $this->db->where('question_id', $id);
        $this->db->update('tbl_question');
    }

    public function mf_stop_show()
    {
        $cstatus = 0; //showing result
        $array = array(
            'status_show' => $cstatus,
        );
        $this->db->set($array);
        $this->db->update('tbl_question');
    }

    public function mf_check_show()
    {
        //find tbl_question.status_show = 1, get the question_id
        $query = $this->db->get_where('tbl_question', array('status_show' => 1));
        $row = $query->row();
        if ($query->num_rows() <> 0) {
            return $row->question_id;
        }
    }


    //question number status (view_question_....._home)
    public function mf_visitedNo($pin)
    {
        //find current quiz running which is the questions is answered
        $query = $this->db->get_where('tbl_quiz_running_home', array('quiz_PIN' => $pin));
        $row = $query->row();
        $quizid = $row->quiz_id;
        $listquestionOrigin = array();
        $visitedNo = array();

        $query = $this->db->query('select question_id from tbl_question where quiz_id=' . $quizid);

        foreach ($query->result() as $listquestion) {
            array_push($listquestionOrigin, $listquestion->question_id);
        }

        $query2 = $this->db->query('SELECT question_id FROM tbl_question_running where quiz_PIN=' . $pin . ' group by question_id');

        foreach ($query->result() as $listquestion) {
            foreach ($query2->result() as $answeredquestion) {
                
                if ($listquestion->question_id === $answeredquestion->question_id) {
                    // echo $listquestion->question_id.'=>'. $answeredquestion->question_id.'| <br/> ';
                    // echo 'listquestionOrigin: '.json_encode($listquestionOrigin).'<br/>';
                    // echo $listquestion->question_id;
                    // echo array_search(5,$listquestionOrigin);



                    // echo array_search(15, $listquestionOrigin)+1;
                    // echo array_search(intval($listquestion->question_id) + 1, $listquestionOrigin);
                    array_push($visitedNo, array_search(intval($listquestion->question_id), $listquestionOrigin)+1);
                }
            }
        }

        // echo json_encode($visitedNo);
        return $visitedNo;
    }

    public function mf_answeredCorrect($pin)
    {
        //find current quiz running which is the questions is answered
        //    $query = $this->db->get_where('tbl_quiz_running_home', array('quiz_PIN' => $pin));

        $query = $this->db->get_where('tbl_quiz_running_home', array('quiz_PIN' => $pin));
        $row = $query->row();
        $quizid = $row->quiz_id;
        $listquestionOrigin = array();
        $answeredCorrect = array();

        $query = $this->db->query('select question_id from tbl_question where quiz_id=' . $quizid);

        foreach ($query->result() as $listquestion) {
            array_push($listquestionOrigin, $listquestion->question_id);
        }


        $query2 = $this->db->query('select * from 
       (select * from 
        (select e.id_question_start, e.students_answer from tbl_student_answer e where e.id in 
         (SELECT max(f.id) FROM `tbl_student_answer` f group by f.id_question_start)) a inner join tbl_question_running b on a.id_question_start=b.id
       where b.quiz_PIN=' . $pin . ') d inner join tbl_question c on d.question_id=c.question_id');
    //    echo json_encode($query2->result());

    //    echo json_encode($query2->result());
        foreach ($query2->result() as $listquestion) {
            foreach ($query2->result() as $answeredquestion) {
                // echo $listquestion->question_answer;
                // echo $answeredquestion->students_answer;
                if ($listquestion->question_answer === $answeredquestion->students_answer) {
                    array_push($answeredCorrect, array_search($listquestion->question_answer, $listquestionOrigin));

                }
            }
        }

        // echo json_encode($answeredCorrect);
        return $answeredCorrect;
    }
    public function mf_answeredInCorrect()
    {
        // return array(3, 5);
    }
}
