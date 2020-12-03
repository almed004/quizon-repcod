<div class="container mt-3">
    <div class="row mb-3">
        <div class="col">
            <h4 id="quiztitle"></h4>

        </div>
    </div>

    <div class="list-question shadow">
        <div class="row mx-1">
            <div class="col">

            </div>
            <div class="col-3 text-center">
                <i>Latest answer</i>
            </div>
        </div>
        <?php foreach ($view_question_home as $view) : ?>
            <?php if ($view->answer <> NULL) {
            ?>
                <div id="<?php echo $view->question_id; ?>" class="row questiontabs mx-1 mt-1 border-bottom cursor-pointer" qtype="<?php echo $view->question_type; ?>">

                    <div class="col py-3">
                        <!-- <h6><span class="mx-3 badge badge-light questiontypebadge" value="<?= $view->question_type; ?>"></span>Question <?php echo $view->question_no; ?> -->
                        <div class="row">
                            <div class="col-lg-1 col-xs-12">
                                <span class="badge badge-light questiontypebadge" value="<?= $view->question_type; ?>"></span>
                            </div>
                            <div class="col-lg-6 col-xs-12">                                
                                    <?php echo $view->question_text; ?>  <br/>
                                    <strong><?='Your answer: '.$view->answer;?></strong>                              
                            </div>
                        </div>
                    </div>

                    <div class="col-3 py-3 text-center">
                        <?php
                        if ($view->question_type === '5') {
                            $answerstatus = $this->Model_student_answer->mf_check_shortanswer($view->question_answer, $view->answer);
                        } else {
                            $answerstatus = $this->Model_student_answer->mf_check_mcsa($view->question_answer, $view->answer);
                        }

                        if ($answerstatus) {
                            echo "<span value='true' class='answersheet'><i class='fas fa-check mr-2 text-success sizef'></i></span>";
                        } else {
                            echo "<span value='false' class='answersheet'><i class='fas fa-times mr-2 text-danger sizef'></i></span>";
                        }
                        ?>
                    </div>
                </div>

            <?php
            } else { ?>
                <div id="<?php echo $view->question_id; ?>" class="row questiontabs mx-1 mt-1 border-bottom cursor-pointer" qtype="<?php echo $view->question_type; ?>">
                    <div class="col py-3">
                        <!-- <h6><span class="mx-3 badge badge-light questiontypebadge" value="<?= $view->question_type; ?>"></span>Question <?php echo $view->question_no; ?>
                        </h6> -->


                        <div class="row">
                            <div class="col-lg-1 col-xs-12">
                                <span class="badge badge-light questiontypebadge" value="<?= $view->question_type; ?>"></span>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                               
                                    <?php echo $view->question_text; ?>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-3 py-3">
                    </div>
                </div>
            <?php }
            ?>

            <form id="form-open-question" action="" method="POST">
                <input type="hidden" name="questionid" value="" id="input-qid">
            </form>

        <?php endforeach; ?>
    </div>


    <div class="row mt-3">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mr-auto">
            <button id="finish" class="btn btn-labeled btn-block float-right border mt-1 btn-outline-warning">
                <span class="font-weight-bold">Back</span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
            <button id="newattempt" class="btn btn-labeled float-right btn-block border mt-1 bg-warning">
                <span class="font-weight-bold text-white">New Attempt</span>
            </button>
        </div>
    </div>

    <!-- <div id="<?php echo $view->question_id; ?>" class="row mt-3">
        <div class="col-xs-12 col-sm-6 col-md-3">        
            <button class="btn btn-labeled border btn-block mt-1 font-20">
                <span class="btn-label px-2 py-2 icon"><i class="fas fa-check sizef"></i></span>
                <span id="correctanswer" class="font-weight-bold"></span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <button class="btn btn-labeled border btn-block mt-1 font-20">
                <span class="btn-label px-2 py-2 icon"><i class="fas fa-times sizef"></i></span>
                <span id="incorrectanswer" class="font-weight-bold"></span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <button class="btn btn-labeled float-right border mt-1 bg-warning icon-x font-20 px-5">
                <span id="finish" class="closequestion font-weight-bold text-white">Back</span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <button class="btn btn-labeled float-right border mt-1 bg-warning icon-x font-20 px-5">
                <span id="newattempt" class="font-weight-bold text-white">New Attempt</span>
            </button>            
        </div>
    </div> -->




</div>

<?php  ?>
<script nonce="**CSP_NONCE**">
    $('document').ready(function() {
        let open = '<?= $_SESSION['opentabs']; ?>';
        console.log(open);

        if (open === '1') {
            $('.questiontabs').addClass('startquestion');
        } else {
            $('.questiontabs').css('cursor', 'not-allowed');
        }

        $('.startquestion').click(function() {
            gotoquestion($(this).attr('id'), $(this).attr('qtype'))
        });


        $('#finish').click(function() {
            closeQuestion();
        });

        $('#newattempt').click(function() {
            window.location.replace('<?php echo base_url('index.php/students/startquizhome'); ?>');
        });


        $('#quiztitle').html(localStorage.getItem('quiztitle'));

        $('.questiontypebadge').each(function() {

            switch ($(this).attr('value')) {
                case '1':
                    $(this).append('MCQ');
                    $(this).addClass('bg-secondary');
                    $(this).addClass('text-white');
                    break;

                case '3':
                    $(this).append('ORD');
                    $(this).addClass('bg-info');
                    $(this).addClass('text-white');
                    break;

                case '5':
                    $(this).append('SAQ');
                    $(this).addClass('bg-dark');
                    $(this).addClass('text-white');
                    break;
                case '6':
                    $(this).append('PAR');
                    $(this).addClass('bg-primary');
                    break;
            }
        });
        recap();



    });

    function recap() {
        var correct = 0;
        var incorrect = 0;
        $('.answersheet').each(function() {
            if ($(this).attr('value') === 'true') {
                correct += 1;
            } else {
                incorrect += 1;
            }
        });
        $('#correctanswer').text(correct);
        $('#incorrectanswer').text(incorrect);

    }

    function gotoquestion(question_id, question_type) {
        switch (question_type) {
            case '1':
                //replace form attr.action
                //replace form input attr.value
                //send the form

                document.getElementById('form-open-question').action = "<?= base_url(); ?>index.php/studentshome/startquestionmcsa";
                document.getElementById('input-qid').value = question_id;
                document.getElementById('form-open-question').submit();

                break;
            case '2':
                document.getElementById('form-open-question').action = "<?= base_url(); ?>index.php/studentshome/questionmcsa";
                document.getElementById('input-qid').value = question_id;
                document.getElementById('form-open-question').submit();

                // window.location.replace("<?php echo base_url('index.php/studentshome/questionmcma/"+ question_id+"'); ?>");
                break;
            case '3':
                document.getElementById('form-open-question').action = "<?= base_url(); ?>index.php/studentshome/startquestionsorting";
                document.getElementById('input-qid').value = question_id;
                document.getElementById('form-open-question').submit();

                // window.location.replace("<?php echo base_url('index.php/studentshome/questionsorting/"+ question_id+"'); ?>");
                break;
            case '4':
                window.location.replace("<?php echo base_url('index.php/studentshome/questionmatching/"+ question_id+"'); ?>");
                break;
            case '5':
                document.getElementById('form-open-question').action = "<?= base_url(); ?>index.php/studentshome/startquestionshortanswer";
                document.getElementById('input-qid').value = question_id;
                document.getElementById('form-open-question').submit();
                // window.location.replace("<?php echo base_url('index.php/studentshome/questionshortanswer/"+ question_id+"'); ?>");
                break;
        }

    }



    function alreadyanswer(question_id) {
        alert("You've already answered the question");
    }

    function closeQuestion() {
        window.location.replace('<?php echo base_url('index.php/students/home/'); ?>' + localStorage.getItem('topicid'))
    }
</script>
</body>

</html>