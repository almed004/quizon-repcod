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


</head>

<body>
    <div class="container">
        <div class="row text-center mt-3">
            <div class="col">
                <p style="font-size:40px;" class="text-warning">
                    <i class="fa fa-check-circle"></i>
                    Quiz Tools
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 bg-secondary">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link text-white font-weight-bold" href="#">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white font-weight-bold" href="<?php echo base_url() . 'teacher/quiz'; ?>" onclick="warning(1);">Quiz</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white font-weight-bold" href="#">Result</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-sm-2 bg-light text-center">
                <!-- <a class="text-white" href="<?php echo base_url() . 'teacher/clearquiz' ?>">End the quiz</a> -->
                <a class="btn btn-outline-danger mt-2" href="<?php echo base_url() . 'teacher/clearquiz' ?>">End the quiz</a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-6">
                <h4>Quiz: <span id="qtitle"></span> (PIN: <span id="pin"><?php echo $_SESSION['pin']; ?></span>)</h4>

            </div>
            <div class="col-sm-6 text-right">
                <!-- <a class="btn btn-outline-danger" href="<?php echo base_url() . 'teacher/clearquiz' ?>">End the quiz</a> -->
            </div>
        </div>
        <div class="row mt-2">

        
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Question Text</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Time</th>
                    <th>Student Answered</th>
                    <th>Result</th>
                </tr>
                <?php foreach ($view_question_alt as $question) : ?>
                    <tr>
                        <td><?php echo $question->question_no ?></td>
                        <td><?php echo $question->question_text ?></td>
                        <td><?php echo $question->question_type ?></td>
                        <td id="status"></td>
                        <td>
                            <?php
                            if ($question->status_disable === '1') { ?>
                                <button disabled="true" id="<?php echo $question->question_id; ?>" class="btn btn-sm btn-secondary rounded-0" value="<?php echo $question->status_start; ?>">Stop</button>
                                <?php } else {
                                if ($question->status_start === '1') { ?>
                                    <button id="<?php echo $question->question_id; ?>" class="btn btn-sm btn-danger rounded-0" value="<?php echo $question->status_start; ?>">Stop</button>
                                <?php    } else { ?>
                                    <button id="<?php echo $question->question_id; ?>" class="btn btn-sm btn-warning rounded-0" value="<?php echo $question->status_start; ?>">Start</button>
                            <?php    }
                            }

                            ?>

                        </td>
                        <td>
                            <?php
                            if ($question->status_start === '1') {
                                echo "<div id='timer'></div>";
                            } else {
                                // echo "-";
                                echo $question->time_elapsed;
                            }
                            ?>

                        </td>
                        <td>
                            <?php
                            if ($question->status_start === '1') {
                                echo "<div id='studentanswering'></div>";
                            } else {
                                // echo "-";
                                echo $question->total;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($question->status_start === '0' && $question->status_disable === '1') {
                            ?>
                                <form id="showResult" class="form-group" action="<?php echo base_url('teacher/result/' . $question->question_id); ?>" method="GET">
                                    <input type="hidden" value="<?php echo $question->question_id; ?>">
                                    <input type="submit" class="tex-white btn btn-sm btn-secondary rounded-0" value="Show">
                                </form>
                            <?php
                            } else {
                            ?>
                                <input disabled="true" type="submit" class="tex-white btn btn-sm btn-secondary rounded-0" value="Show">
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        $('document').ready(function() {
            $('#qtitle').html(sessionStorage.getItem('qtitle'));

            //start the timer
            if (document.getElementById('timer') != null) {
                var count = 1;
                var countDown = setInterval(function() {

                    document.getElementById("timer").innerHTML = count;
                    count++;
                    if (count === 0) {
                        document.getElementById("timer").innerHTML = "Time is up";
                        console.log("Happy New Year");
                        clearInterval(countDown);
                    }
                }, 1000);
            }

            $("span").click(function() {
                alert(document.getElementById("timer").innerHTML);
            });



            //start the question
            $('button').click(function() {

                var qstatus = $(this).val();
                var question_id = $(this).attr("id");
                var time = $("#timer").html(); //nanti diubah ke angka real dari database jawaban mhs

                if (qstatus === "1") {
                    var url_timer = "<?php echo base_url('/teacher/update_question_time/"+ question_id +"?time="+ time +"'); ?>";
                    $(location).attr('href', url_timer);


                    // alert(url_timer);
                    // alert($("#timer").html());
                } else {
                    var url = "<?php echo base_url('/teacher/update_question_status/"+ question_id +"?status="+ qstatus +"'); ?>";
                    // alert(url);
                    $(location).attr('href', url);

                }

            })

        })


        // SSE
        var qpin = '<?php echo $_SESSION['pin'];  ?>';

        var source = new EventSource("<?php echo base_url('teacher/load_answer/' . $_SESSION['questionrun'] . '.' . $_SESSION['pin']); ?>");

        source.onmessage = function(event) {
            document.getElementById("studentanswering").innerHTML = event.data;
        }
    </script>
    <script>
        function warning(id) {
            confirm("This will restart the quiz");
        }
    </script>

</body>

</html>