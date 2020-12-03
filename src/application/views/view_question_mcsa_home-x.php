<div class="container">
   
    <div id="questionPlace" class="shadow Enabled border mt-3 px-1 py-1 rounded">

        <div class="row mt-2 mx-1">
            <div class="col bg">
                <h6 id="textQuestion"><?php echo trim($questiontext, "\""); ?></h6>
            </div>

        </div>
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

        <div class="row text-right">
            <div class="col-lg-12">
                <form id="formsubmitAnswer" action="<?php echo base_url('index.php/studentshome/submitAnswer'); ?>" method="post">
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <input id="answerText" name="answerText" type="hidden" value="">
                    <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">
                    <button id="submitAnswer" class="py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="" disabled>Submit Answer</button>
                </form>
            </div>
        </div>

        <script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/prism.js"></script>

        <script nonce="**CSP_NONCE**">
            $(document).ready(function() {

                $("#option1").click(function() {
                    $(this).addClass("bg-secondary");
                    $(this).addClass("text-white");
                    $(this).attr("value", "1");
                    $("#studentAnswer").attr("value", "1");
                    $('#answerText').attr("value", <?php echo $option1; ?>);
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
                    $('#answerText').attr("value", <?php echo $option2; ?>);
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
                    $('#answerText').attr("value", <?php echo $option3; ?>);
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
                    $('#answerText').attr("value", <?php echo $option4; ?>);
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
                $('#submitAnswer').click(function() {
                    $('#alertanswer').removeClass("Disabled");
                    $('#alertanswer').addClass("Enabled");
                });

                
            });
        </script>
        </body>

        </html>