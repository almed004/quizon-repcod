<div class="container">

    <div id="questionPlace" class="shadow Enabled border mt-3 px-1 py-1 rounded bg-white">

        <div class="row mt-2 mx-1">
            <div class="col bg">
                <h6 id="textQuestion"><?php echo trim($questiontext, "\""); ?></h6>
            </div>
        </div>

        <script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/Sortable.js"></script>

        <div class="row pb-2 mx-1">
            <?php if (strlen($questionadds) > 52) { ?>
                <div class="col-lg-8">
                    <div class=" row">
                        <div class="col">
                            <?php echo $questionadds; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                        </div>
                    </div>
                <?php  } else { ?>
                    <div class="col-lg-4">
                        <div class=" row">
                        </div>
                        <div class="row">
                            <div class="col">

                            </div>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="col-lg-4">
                        <ul id="simpleList">

                        </ul>

                    </div>
                </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row text-right">
        <div class="col-lg-12">
            <form id="formsubmitAnswer" action="<?php echo base_url('index.php/studentshome/submitAnswer_ajax'); ?>" method="post">
                <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                <input id="quizid" name="quizid" type="hidden" value="<?php echo trim($quizid, "\""); ?>">
                <input id="answerText" name="answerText" type="hidden" value="">
                <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">
                <!-- <button id="submitAnswer" class="py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="">Submit Answer</button> -->
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button id="submitAnswer" class="float-right py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="">Submit Answer</button>
        </div>
    </div>

    <div class="row border-top mt-3">
        <div class="col text-right mt-3">
            <span class="py-1 mt-4 mr-3"><strong id="statusanswer">
                    <?php
                    $answerstatus = $this->Model_student_answer->mf_check_shortanswer($questionanswer, $_SESSION['studentanswer']);
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
        localStorage.removeItem('localStorage-example');
        let option = [
            '<li data-id="1" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option1, "\""); ?></li>',
            '<li data-id="2" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option2, "\""); ?></li>',
            '<li data-id="3" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option3, "\""); ?></li>',
            '<li data-id="4" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option4, "\""); ?></li>',
            '<li data-id="5" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option5, "\""); ?></li>',
            '<li data-id="6" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option6, "\""); ?></li>',
            '<li data-id="7" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option7, "\""); ?></li>',
            '<li data-id="8" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option8, "\""); ?></li>'
        ];
        shuffle(option);
        // console.log(option);
        $('#simpleList').append(option);

        function shuffle(array) {
            array.sort(() => Math.random() - 0.5);
        }

        //Get current list
        $('#getSort').on('click', function() {
            console.log(Sortable.get(simpleList).toArray());
            $('#resultAnswer').html(Sortable.get(simpleList).toArray());
        });

        // Simple list
        Sortable.create(simpleList, {
            group: "localStorage-example",
            store: {
                /**
                 * Get the order of elements. Called once during initialization.
                 * @param   {Sortable}  sortable
                 * @returns {Array}
                 */
                get: function(sortable) {
                    var order = localStorage.getItem(sortable.options.group.name);
                    return order ? order.split('|') : [];
                },

                /**
                 * Save the order of elements. Called onEnd (when the item is dropped).
                 * @param {Sortable}  sortable
                 */
                set: function(sortable) {
                    var order = sortable.toArray();
                    localStorage.setItem(sortable.options.group.name, order.join('|'));
                }
            }
        });

        function sortable() {
            // alert(Sortable.get(simpleList).toArray());
            var s = document.getElementById("studentAnswer");
            s.value = Sortable.get(simpleList).toArray();
        }

        $('#trysubmit').click(function() {
            console.log('Test answer');
            console.log($('#studentAnswer').value);
        });

        $('#formsubmitAnswer').submit(function() {
            $('#studentAnswer').attr('value', Sortable.get(simpleList).toArray());
        });

        $('.orderItem').on('drag', 'drag(event)');
        $('.orderItem').on('drop', 'drop(event)');

    })
</script>
<script nonce="**CSP_NONCE**">
    $(document).ready(function() {
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
        $('#submitAnswer').click(function() {
            // $('#alertanswer').removeClass("Disabled");
            // $('#alertanswer').addClass("Enabled");
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

            // console.log('orderquestionid: ' + orderquestionid);
            // console.log('orderquizid: ' + orderquizid);
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
            console.log()
            if (localStorage.getItem('currentNo') === '<?= $countquestion; ?>') {
                $(this).attr('href', '<?= base_url(); ?>index.php/students/home/2');
            } else {
                $(this).attr('href', '<?= base_url(); ?>index.php/students/startquestionarray/<?= $_SESSION['questionrun'] + 1; ?>');
                localStorage.setItem('currentNo', parseInt(localStorage.getItem('currentNo')) + 1);
            }
        });

    });
</script>
</body>

</html>