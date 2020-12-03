<div class="container">

    <div id="questionPlace" class="shadow Enabled border mt-3 px-1 py-1 rounded bg-white">

        <div class="row mt-2 mx-1">
            <div class="col">
                <h6 id="textQuestion"><?php echo trim($questiontext, "\""); ?></h6>
            </div>
        </div>

        <div class="row pb-2 mx-1">
            <?php if (strlen($questionadds) > 52 || $questionimage !== '') { ?>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col">

                            <?php if ($questionimage !== '') { ?>
                                <img src="<?= base_url() . 'img/questions/' . $questionimage; ?>" alt="picture" style="object-fit:none;">
                            <?php } else {
                                echo $questionadds;
                            }

                            ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                        </div>
                    </div>
                <?php  } else { ?>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col">

                            </div>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="col-lg-4">
                        <?php
                        $option = array(
                            '<div class="row text-center">
                                <div class="col">
                                </div>
                                <div class="col-lg-12">
                                    <button id="option1" class="py-2 btn btn-outline-secondary rounded-0 my-1 btn-block" value="">' . trim($option1, "\"") . '</button>
                                </div>
                            </div>',

                            '<div class="row text-center">
                                <div class="col">
                                </div>
                                <div class="col-lg-12">
                                    <button id="option2" class="py-2 btn btn-outline-secondary rounded-0 my-1 btn-block" value="">' . trim($option2, "\"") . '</button>
                                </div>
                            </div>',
                            '<div class="row text-center">
                                <div class="col">
                                </div>
                                <div class="col-lg-12">
                                    <button id="option3" class="py-2 btn btn-outline-secondary rounded-0 my-1 btn-block" value="">' . trim($option3, "\"") . '</button>
                                </div>
                            </div>',
                            '<div class="row text-center">
                                <div class="col">
                                </div>
                                <div class="col-lg-12">
                                    <button id="option4" class="py-2 btn btn-outline-secondary rounded-0 my-1 btn-block" value="">' . trim($option4, "\"") . '</button>
                                </div>
                            </div>'
                        );

                        $numbers = range(1, 4);
                        shuffle($numbers);
                        foreach ($numbers as $number) {
                            echo $option[$number - 1];
                        }
                        ?>
                    </div>
                </div>
        </div>
    </div>

</div>

<div class="container">
    <div class="row text-right">
        <div class="col-lg-12">
            <form id="formsubmitAnswer" method="post" action="<?= base_url(); ?>index.php/studentshome/submitAnswer_ajax">
                <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                <input id="quizid" name="quizid" type="hidden" value="<?php echo trim($quizid, "\""); ?>">
                <input id="answerText" name="answerText" type="hidden" value="">
                <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">
                <!-- <button id="submitAnswer" class="py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="" disabled>Submit Answer</button> -->
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button id="submitAnswer" class="float-right py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="">Submit Answer</button>
        </div>
    </div>

    <div class="row border-top mt-3">
        <div class="col mt-3">
            <span class="py-1 mt-4 mr-3">
                <strong id="statusanswer">
                    <?php
                    $answerstatus = $this->Model_student_answer->mf_check_mcsa($questionanswer, $_SESSION['studentanswer']);
                    echo ($answerstatus) ? '<span class="text-success border px-2 py-2 border-success"><i class="fas fa-star sizef mr-2 mb-1"></i>CORRECT</span>' : '<span class="text-danger border px-2 py-2 border-danger">INCORRECT</span>';
                    ?>

                </strong>
            </span>
            <!-- <a id="btnRetry" class="btn btn-info rounded-0 text-white btn-sm" href="<?= base_url(); ?>index.php/studentshome/startquestionshortanswer">Retry</a> -->
            <a id="btnNext" class="btn btn-info rounded-0 text-white btn-sm" href="">Next</a>
        </div>
    </div>

    <div class="row border-top mt-3">
        <div class="col text-center mt-3">
            <?php
            $countquestion = $_SESSION['countquestion'];
            for ($i = 1; $i <= $countquestion; $i++) {
                echo '<span class="btn-outline-warning btn ml-1 questionnumber">' . $i . '</span>';
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
    $(document).ready(function() {

        // hljs.initHighlightingOnLoad();

        $("#option1").click(function() {
            $(this).addClass("bg-secondary");
            $(this).addClass("text-white");
            $(this).attr("value", "1");
            $("#studentAnswer").attr("value", "1");
            $('#answerText').attr("value", "<?php echo trim($option1, "\""); ?>");
            $('#submitAnswer').prop('disabled', false);

            $("#option2").removeClass("bg-secondary");
            $("#option2").removeClass("text-white");
            $("#option2").attr("value", "0");

            $("#option3").removeClass("bg-secondary");
            $("#option3").removeClass("text-white");
            $("#option3").attr("value", "0");

            $("#option4").removeClass("bg-secondary");
            $("#option4").removeClass("text-white");
            $("#option4").attr("value", "0");

        })
        $("#option2").click(function() {
            $(this).addClass("bg-secondary");
            $(this).addClass("text-white");
            $(this).attr("value", "1");
            $("#studentAnswer").attr("value", "2");
            $('#answerText').attr("value", "<?php echo trim($option2, "\""); ?>");
            $('#submitAnswer').prop('disabled', false);

            $("#option1").removeClass("bg-secondary");
            $("#option1").removeClass("text-white");
            $("#option1").attr("value", "0");

            $("#option3").removeClass("bg-secondary");
            $("#option3").removeClass("text-white");
            $("#option3").attr("value", "0");

            $("#option4").removeClass("bg-secondary");
            $("#option4").removeClass("text-white");
            $("#option4").attr("value", "0");

        })
        $("#option3").click(function() {
            $(this).addClass("bg-secondary");
            $(this).addClass("text-white");
            $(this).attr("value", "1");
            $("#studentAnswer").attr("value", "3");
            $('#answerText').attr("value", "<?php echo trim($option3, "\""); ?>");
            $('#submitAnswer').prop('disabled', false);

            $("#option2").removeClass("bg-secondary");
            $("#option2").removeClass("text-white");
            $("#option2").attr("value", "0");

            $("#option1").removeClass("bg-secondary");
            $("#option1").removeClass("text-white");
            $("#option1").attr("value", "0");

            $("#option4").removeClass("bg-secondary");
            $("#option4").removeClass("text-white");
            $("#option4").attr("value", "0");


        })
        $("#option4").click(function() {
            $(this).addClass("bg-secondary");
            $(this).addClass("text-white");
            $(this).attr("value", "1");
            $("#studentAnswer").attr("value", "4");
            $('#answerText').attr("value", "<?php echo trim($option4, "\""); ?>");
            $('#submitAnswer').prop('disabled', false);

            $("#option2").removeClass("bg-secondary");
            $("#option2").removeClass("text-white");
            $("#option2").attr("value", "0");

            $("#option3").removeClass("bg-secondary");
            $("#option3").removeClass("text-white");
            $("#option3").attr("value", "0");

            $("#option1").removeClass("bg-secondary");
            $("#option1").removeClass("text-white");
            $("#option1").attr("value", "0");

        })
    })
</script>


<script nonce="**CSP_NONCE**">
    $(document).ready(function() {
        console.log(window.location);
        let visitedNo = JSON.parse('<?= json_encode($visitedNo); ?>');
        let answeredCorrect = JSON.parse('<?= json_encode($answeredCorrect); ?>');
        let answeredIncorrect = JSON.parse('<?= json_encode($answeredIncorrect); ?>');

        $('.questionnumber').css('border-width', '1px 1px 7px 1px');
        $('.questionnumber').css('border-bottom', '7px solid #ffc107');

        //search .questionnumber where .html() = localStorage(currentNo)
        $('.questionnumber').each(function() {
            if ($(this).html() === localStorage.getItem('currentNo')) {
                $(this).addClass('bg-warning');

            }

            for (index = 0; index < visitedNo.length; ++index) {
                // console.log(visitedNo[index]);
                // console.log($(this).html());
                if (parseInt(visitedNo[index]) === parseInt($(this).html())) {
                    $(this).removeClass('btn-outline-warning');
                    $(this).addClass('btn-outline-secondary');
                }

            }
        });
        // $('#statusanswer').css('display', 'none');

        $('#submitAnswer').click(function() {
            // $('#alertanswer').removeClass("Disabled");
            // $('#alertanswer').addClass("Enabled");
            // $('#statusanswer').css('display', 'inline');
            // console.log($('#studentAnswer').attr('value'));
            $('#formsubmitAnswer').submit();

        });

        $('#formsubmitAnswer').submit(function(event) {
            event.preventDefault();

            var getUrl = $(this).attr('action');
            var formMethod = $(this).attr('method');
            var formData = $(this).serialize();
            $.ajax({
                url: getUrl,
                type: formMethod,
                data: formData,
                success: function(data) {
                    var studentanswer = data;
                    var questionanswer = '<?= $questionanswer ?>';
                    if (studentanswer === questionanswer) {
                        $('#statusanswer').text('CORRECT');
                    } else {
                        $('#statusanswer').text('INCORRECT');
                    }

                },
                error: function(e) {
                    $('#output').text(e.responseText);
                }
            });
        });


        $('.questionnumber').on('click', function() {

            var orderquestionid = $(this).html();
            var orderquizid = $('#orderquizid').attr('value');
            var questiontype = $(this).attr('questiontype');
            var questionid = $(this).attr('questionid');

            localStorage.setItem('currentNo', orderquestionid);

            // console.log('orderquestionid: '+orderquestionid);
            // console.log('orderquizid: '+orderquizid);
            // console.log('questiontype: '+questiontype);
            // console.log('questionid: '+questionid);


            $('#orderquestionid').attr('value', orderquestionid);

            // switch (questiontype) {
            //     case '1':
            //         $('#questioncounter').attr('action','<?= base_url(); ?>index.php/studentshome/startquestionmcsa_next');
            //         break;
            //     case '3':
            //         $('#questioncounter').attr('action','<?= base_url(); ?>index.php/studentshome/startquestionsorting_next');
            //         break;
            //     case '5':
            //         $('#questioncounter').attr('action','<?= base_url(); ?>index.php/studentshome/startquestionshortanswer_next');
            //         break;
            // }
            $('#questioncounter').submit();
        });

        $('#btnNext').on('click', function() {
            // console.log()
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