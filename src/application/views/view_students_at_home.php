<div class="wrapper">
    <nav id="sidebar" class="">

        <ul class="pl-2 list-unstyled components">
            <?php $list_topic = $view_list_topic; ?>
            <?php foreach ($list_topic as $bar) : ?>
                <li class="sidetab-components" topicid="<?= $bar->id; ?>">
                    <a class="display-4 topic-bar" href="<?= base_url() . 'index.php/students/home/' . $bar->id; ?>" topicid="<?= $bar->id; ?>"><?= $bar->topic_title; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="container">
        <div class="row mx-2">

            <div class="col">
                <div class="row">
                    <button class="btn mx-1 my-2  text-secondary" id="sidebarCollapse">
                        <i class="fas fa-bars sizef"></i><span class="ml-2">Quizzes</span>
                    </button>
                </div>

                <div class="row">
                    <?php $newlist = json_decode($view_quiz_home); ?>
                    <?php foreach ($newlist as $view) : ?>
                        <div class="col-lg-4 col-sm-12">
                            <div class="card rounded mt-3 startquestion cursor-pointer" id="<?php echo $view->quiz_id; ?>" quiztitle="<?= $view->quiz_title ?>">

                                <div class="card-body">
                                    <h4 class="card-title"><?= $view->quiz_title; ?></h4>
                                    <span class="mb-3"><?= $view->num_question; ?> questions</span>

                                    <div class="card-text">

                                        <hr class="bg-info" />
                                        <div class="float-left">
                                            <div class="emoji">

                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="form-open-quiz" action="" method="POST">
                            <input type="hidden" name="quizid" value="" id="input-quizid">
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>


</div>


<script nonce="**CSP_NONCE**">
    $('document').ready(function() {
        localStorage.setItem('currentNo', 1);
        $('.sidetab-components').each(function() {

            var topicid = $(this).attr('topicid');
            if (parseInt(topicid) === parseInt(localStorage.getItem('topicid'))) {
                $(this).children().addClass('border border-top-0 border-left-0 border-right-0 border-info font-weight-bold');
            }

        });

        $('.startquestion').click(function() {
            gotoquestion($(this).attr('id')); //quiz_id
            localStorage.setItem('quiztitle', $(this).attr('quiztitle'));
        });

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        })

        $('.topic-bar').on('click', function() {
            $('.topic-bar').removeClass('text-warning');
            $(this).addClass('text-warning');
            localStorage.setItem('topicid', $(this).attr('topicid'));
        });

    });

    function gotoquestion(quiz_id) {
        document.getElementById('form-open-quiz').action = "<?= base_url(); ?>index.php/students/startquestionarray/1";
        document.getElementById('input-quizid').value = quiz_id;
        document.getElementById('form-open-quiz').submit();
    }
</script>


</body>

</html>