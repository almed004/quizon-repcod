    <div class="container">
        <div class="row mt-3 text-center">
            <div class="col">

                <h6 id="textQuestion"><?php echo trim($questiontext, "\""); ?></h6>
                <hr />
            </div>
        </div>

        <?php if (strlen($questionadds) > 52 || $questionimage !== '') { ?>
            <div class="row">
                <div class=" col-lg-3">
                </div>
                <div class="col-lg-6">
                    <?php
                    if ($questionimage !== '') {
                    ?>
                        <img src="<?= base_url() . 'img/questions/' . $questionimage; ?>" alt="pic\ture" style="object-fit:none;">
                    <?php
                    } else {
                        echo $questionadds;
                    }
                    ?>
                </div>
            </div>
        <?php } ?>

        <div class="row text-center justify-content-center mt-4">
            <div class="col-lg-12 col-xs-12 mb-3 mx-1">
                <form id="formsubmitAnswer" action="<?= base_url() . 'index.php/students/submitAnswer_shortanswer'; ?>" method="post">
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <!-- <input id="studentID" name="studentID" type="hidden" value="eummy001"> -->
                    <input type="text" id="textAnswer" name="answerText" row="3" class="form-control" placeholder="Write your answer here">
                    <button id="btnSubmit" class="py-1 btn btn-warning rounded-0 mt-4 btn-lg float-right font-weight-bold text-white">Submit Answer</button>
                </form>
            </div>
        </div>

    </div>

    <script nonce="**CSP_NONCE**">
        var source = new EventSource("<?= base_url() . 'index.php/students/check_started_question'; ?>");
        source.onmessage = function(event) {
            console.log(event.data);
            if (event.data === '0') {
                window.location.replace("<?= base_url() . 'index.php/students'; ?>");
            }
        }

        $(document).ready(function() {
            $('#btnSubmit').click(function() {
                textAnswer();
            })
        });

        function textAnswer() {
            var s = document.getElementById("studentAnswer");
            s.value = document.getElementById("textAnswer").value;

        }
    </script>
    </body>

    </html>