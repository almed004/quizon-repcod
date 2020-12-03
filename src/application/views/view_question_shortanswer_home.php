<div class="container">

    <div class="questionPlace shadow border mt-3 px-1 py-1 rounded justify-content-center">

        <div class="row mt-2 mx-1 text-center">
            <div class="col">
                <h6><?php echo trim($questiontext, "\""); ?></h6>
            </div>
        </div>

        <div class="row pb-2 mx-1 justify-content-center">
            <div class="col">
                <?php if (strlen($questionadds) > 52 || $questionimage !== '') {
                    if ($questionimage !== '') {
                ?>
                        <img src="<?= base_url() . 'img/questions/' . $questionimage; ?>" alt="picture" style="object-fit:none;">
                <?php
                    } else {
                        echo $questionadds;
                    }
                } ?>
            </div>
        </div>

        <div class="row text-center justify-content-center">
            <div class="col-lg-6 col-xs-12 mb-3 mx-1">
                <form id="formsubmitAnswer" action="<?php echo base_url('index.php/studentshome/submitAnswer_shortanswer'); ?>" method="post">
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                    <input id="quizid" name="quizid" type="hidden" value="<?php echo trim($quizid, "\""); ?>">
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">
                    <input type="text" id="answerText" name="answerText" row="3" class="form-control" placeholder="Write your answer here" value="<?php echo (isset($_SESSION['studentanswer'])) ? $_SESSION['studentanswer'] : ''; ?>">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button id="btnSubmitAnswer" class="py-1 mx-1 btn btn-warning rounded-0 mt-4 float-right not-allowed text-white" disabled>Submit Answer</button>
        </div>
    </div>

    <div class="row border-top mt-3">
        <div class="col mt-3">
            <span class="py-1 mt-4 mr-3">
                <strong id="statusanswer">
                    <?php
                    $answerstatus = $this->Model_student_answer->mf_check_shortanswer($questionanswer, $_SESSION['studentanswer']);
                    echo ($answerstatus) ? '<span class="text-success border px-2 py-2 border-success"><i class="fas fa-star sizef mr-2 mb-1"></i>CORRECT</span>' : '<span class="text-danger border px-2 py-2 border-danger">INCORRECT</span>';
                    ?></strong>
            </span>
        </div>
        <div class="col text-right mt-3">
            <a id="btnRetry" class="btn btn-info text-white btn-sm" href="<?= base_url(); ?>index.php/studentshome/startquestionshortanswer">Retry</a>
            <a id="btnNext" class="btn btn-info text-white btn-sm" href="">Next</a>
        </div>
    </div>

    <div class="row border-top mt-3">
        <div class="col text-center mt-3">
            <?php
            $countquestion = $_SESSION['countquestion'];

            for ($i = 1; $i <= $countquestion; $i++) {
                echo '<span class="btn-outline-warning btn ml-1 questionnumber mt-2">' . $i . '</span>';
            }
            ?>

        </div>
    </div>

</div>

<form id="questioncounter" action="<?= base_url(); ?>index.php/studentshome/question_direction" method="post">
    <input id="orderquestionid" type="hidden" name="questionid" value="">
    <input id="orderquizid" name="quizid" type="hidden" value="<?php echo trim($quizid, "\""); ?>">
</form>


<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/prism.js"></script>

<script nonce="**CSP_NONCE**">
    $('document').ready(function() {
        let visitedNo = JSON.parse('<?= json_encode($visitedNo); ?>');
        let answeredCorrect = JSON.parse('<?= json_encode($answeredCorrect); ?>');
        let answeredIncorrect = JSON.parse('<?= json_encode($answeredIncorrect); ?>');

        $('.questionnumber').css('border-width', '1px 1px 7px 1px');
        $('.questionnumber').css('border-bottom', '7px solid #ffc107');


        //search .questionnumber where .html() = localStorage(currentNo)

        $('.questionnumber').each(function() {
            if ($(this).html() === localStorage.getItem('currentNo')) {
                $(this).addClass('bg-warning text-dark');
            }

            for (index = 0; index < visitedNo.length; ++index) {
                // console.log(visitedNo[index]);
                // console.log($(this).html());
                if (parseInt(visitedNo[index]) === parseInt($(this).html())) {
                    $(this).removeClass('btn-outline-warning');
                    $(this).addClass('btn-outline-secondary');
                }

            }

            // for (index = 0; index < answeredCorrect.length; ++index) {
            //     // console.log(answeredCorrect[index]);
            //     // console.log($(this).html());
            //     if (parseInt(answeredCorrect[index]) === parseInt($(this).html())) {
            //         $(this).css('border-bottom', '7px solid #33AE9A');
            //     }

            // }
            // for (index = 0; index < answeredIncorrect.length; ++index) {
            //     // console.log(visitedNo[index]);
            //     // console.log($(this).html());
            //     if (parseInt(answeredIncorrect[index]) === parseInt($(this).html())) {
            //         $(this).css('border-bottom', '7px solid #ed1c24');
            //     }

            // }


        });



        $('#btnSubmitAnswer').click(function() {
            var s = document.getElementById("studentAnswer");
            s.value = document.getElementById("answerText").value;

            $('#formsubmitAnswer').submit();
        });

        $('#formsubmitAnswer').submit(function() {
            var s = document.getElementById("studentAnswer");
            s.value = document.getElementById("answerText").value;
        });

        $('#answerText').click(function() {
            $("#btnSubmitAnswer").removeAttr('disabled');
        });

        $('#btnRetry').click(function() {
            $('#alertanswer').css('display', 'none');
        });

        if ($('#answerText').attr('value') !== '') {
            $('#answerText').attr('disabled', 'true');
        }

        if (!($('#answerText').is(':disabled'))) {
            // console.log('disabled');
            $('#statusanswer').css('display', 'none');
        } else {
            // console.log('enabled');
            $('#statusanswer').css('display', 'inline');
        }

        $('.questionnumber').on('click', function() {
            var orderquestionid = $(this).html();
            var orderquizid = $('#orderquizid').attr('value');
            var questiontype = $(this).attr('questiontype');
            var questionid = $(this).attr('questionid');

            localStorage.setItem('currentNo', orderquestionid);

            $('#orderquestionid').attr('value', orderquestionid);

            $('#questioncounter').submit();
        });


        $('#btnNext').on('click', function() {

            if (localStorage.getItem('currentNo') === '<?= $countquestion; ?>') {
                $(this).attr('href', '<?= base_url(); ?>index.php/students/home/2');
            } else {
                $(this).attr('href', '<?= base_url(); ?>index.php/students/startquestionarray_next/<?= $_SESSION['questionrun'] + 1; ?>');
                localStorage.setItem('currentNo', parseInt(localStorage.getItem('currentNo')) + 1);
            }
        });


    });
</script>
</body>

</html>