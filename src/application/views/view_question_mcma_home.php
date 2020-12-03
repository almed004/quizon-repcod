<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js" integrity="sha256-2JRzNxMJiS0aHOJjG+liqsEOuBb6++9cY4dSOyiijX4=" crossorigin="anonymous"></script>
    <style>
        /* color theme */
        .main-yellow {
            color: hsl(45, 100%, 51%);
        }

        .light-yellow {
            color: hsl(45, 70%, 71%);
        }

        .bg-light-yellow {
            background-color: hsl(45, 51%, 89%);
        }

        .bg-main-green {
            background-color: #607d8b;
        }

        .main-green {
            color: #607d8b;
        }

        .dark-green {
            color: hsl(206, 43%, 16%);
        }

        .bg-dark-green {
            background-color: hsl(206, 43%, 16%);
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row text-center mt-3">
            <div class="col">

                <i class="fa fa-check-circle main-green" style="font-size:20pt;"></i>
                <span class="font-weight-bold main-green" style="font-size:20pt;">Quizitor</span>


            </div>
        </div>

        <div class="row mt-3">

            <div class="col bg">
                <h4><?php echo trim($questiontext, "\""); ?>.</h4>
                <hr />
            </div>

        </div>
        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option1" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option1, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option2" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option2, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option3" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option3, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option4" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option4, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option5" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option5, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option6" class="shadow py-3 btn btn-secondary rounded-0 my-1 btn-lg btn-block" value="0"><?php echo trim($option6, "\"") ?></button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <form id="formsubmitAnswer" action="<?php echo base_url('/studentshome/submitAnswer'); ?>" method="post">
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">

                    <button id="submitAnswer" class="shadow py-3 btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white">Submit Answer</button>
                </form>
            </div>
        </div>


    </div>
    <script>
        $('document').ready(function() {
            $('#option1').click(function() {
                $(this).removeClass("bg-secondary");
                $(this).addClass("bg-primary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }

            });

            $('#option2').click(function() {
                $(this).removeClass("bg-secondary");
                $(this).addClass("bg-primary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }
            });

            $('#option3').click(function() {
                $(this).removeClass("bg-secondary");
                $(this).addClass("bg-primary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }
            });

            $('#option4').click(function() {
                $(this).addClass("bg-primary");
                $(this).removeClass("bg-secondary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }
            });

            $('#option5').click(function() {
                $(this).removeClass("bg-secondary");
                $(this).addClass("bg-primary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }
            });

            $('#option6').click(function() {
                $(this).removeClass("bg-secondary");
                $(this).addClass("bg-primary");
                if ($(this).attr("value") === '1') {
                    $(this).attr("value", '0');
                    $(this).removeClass("bg-primary");
                    $(this).addClass("bg-secondary");
                } else {
                    $(this).attr("value", '1');
                    $(this).removeClass("bg-secondary");
                    $(this).addClass("bg-primary");
                }
                console.log($(this).attr("value"));

            });

            $("#submitAnswer").click(function() {
                // alert("Hi");
                var answers = [
                    $("#option1").attr("value"),
                    $("#option2").attr("value"),
                    $("#option3").attr("value"),
                    $("#option4").attr("value"),
                    $("#option5").attr("value"),
                    $("#option6").attr("value"),
                ];
                var compiled_answer = answers[0] + answers[1] + answers[2] + answers[3] + answers[4] + answers[5];
                $("#studentAnswer").attr("value", compiled_answer);

                console.log(answers[0]);
                console.log(compiled_answer);
                // console.log(answers);
            });

        });
    </script>
</body>

</html>