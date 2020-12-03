<div class="container" id="main-content">

    <div class="row mt-3 text-center">
        <div class="col bg">
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
                    <div class="row justify-content-center">
                        <div class="col">
                        </div>
                        <div class="col-lg-12">
                            <button id="option1" class="shadow py-3 btn btn-outline-secondary rounded-0 my-1 btn-lg btn-block" value='0'><?php echo trim($option1, "\"") ?></button>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col">

                </div>
                        <div class="col-lg-12">
                            <button id="option2" class="shadow py-3 btn btn-outline-secondary rounded-0 my-1 btn-lg btn-block value='0'"><?php echo trim($option2, "\"") ?></button>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col">

                </div>
                        <div class="col-lg-12">
                            <button id="option3" class="shadow py-3 btn btn-outline-secondary rounded-0 my-1 btn-lg btn-block value='0'"><?php echo trim($option3, "\"") ?></button>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col">

                </div>
                        <div class="col-lg-12">
                            <button id="option4" class="shadow py-3 btn btn-outline-secondary rounded-0 my-1 btn-lg btn-block value='0'"><?php echo trim($option4, "\""); ?></button>
                        </div>
                    </div>


                    <div class="row justify-content-center">
                        <div class="col"></div>
                        <div class="col-lg-12">
                            <form id="formsubmitAnswer" action="<?= base_url() . 'index.php/students/submitAnswer'; ?>" method="post">
                                <input id="idQuestionStart" name="questionStart" type="hidden" value="<?= trim($idstart, "\""); ?>">
                                <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                                <button id="submitAnswer" class="shadow py-3 btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white" value="">Submit Answer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


    </div>


    <script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/prism.js"></script>
    <script nonce="**CSP_NONCE**">
        var source = new EventSource("<?= base_url() . 'index.php/students/check_started_question'; ?>");
        source.onmessage = function(event) {
            console.log(event.data);
            if (event.data === '0') {
                window.location.replace("<?= base_url() . 'index.php/students'; ?>");
            }
        }


        $(document).ready(function() {

            $("#option1").click(function() {
                $(this).addClass("text-white");
                $(this).addClass("bg-primary");
                $(this).attr("value", "1");
                $("#studentAnswer").attr("value", "1");



                $("#option2").removeClass("bg-primary");
                $("#option2").removeClass("text-white");
                $("#option2").attr("value", "0");


                $("#option3").removeClass("bg-primary");
                $("#option3").removeClass("text-white");
                $("#option2").attr("value", "0");

                $("#option4").removeClass("bg-primary");
                $("#option4").removeClass("text-white");
                $("#option2").attr("value", "0");

            })
            $("#option2").click(function() {
                $(this).addClass("text-white");
                $(this).addClass("bg-primary");
                $(this).attr("value", "1");
                $("#studentAnswer").attr("value", "2");

                $("#option1").removeClass("bg-primary");
                $("#option1").removeClass("text-white");
                $("#option1").attr("value", "0");

                $("#option3").removeClass("bg-primary");
                $("#option3").removeClass("text-white");
                $("#option3").attr("value", "0");

                $("#option4").removeClass("bg-primary");
                $("#option4").removeClass("text-white");
                $("#option4").attr("value", "0");

            })
            $("#option3").click(function() {
                $(this).addClass("text-white");
                $(this).addClass("bg-primary");
                $(this).attr("value", "1");
                $("#studentAnswer").attr("value", "3");

                $("#option2").removeClass("bg-primary");
                $("#option2").removeClass("text-white");
                $("#option2").attr("value", "0");

                $("#option1").removeClass("bg-primary");
                $("#option1").removeClass("text-white");
                $("#option1").attr("value", "0");

                $("#option4").removeClass("bg-primary");
                $("#option4").removeClass("text-white");
                $("#option4").attr("value", "0");

            })
            $("#option4").click(function() {
                $(this).addClass("text-white");
                $(this).addClass("bg-primary");
                $(this).attr("value", "1");
                $("#studentAnswer").attr("value", "4");

                $("#option2").removeClass("bg-primary");
                $("#option2").removeClass("text-white");
                $("#option2").attr("value", "0");

                $("#option3").removeClass("bg-primary");
                $("#option3").removeClass("text-white");
                $("#option3").attr("value", "0");

                $("#option1").removeClass("bg-primary");
                $("#option1").removeClass("text-white");
                $("#option1").attr("value", "0");

            })
        })
    </script>
    </body>

    </html>