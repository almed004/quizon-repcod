<div class="container" id="main-content">
    <div class="row mt-3">
        <div class="col-12 text-center">
            <h3 class="dark-green pt-1"><span id="qtitle">Run Quizzes</span></h3>
            <hr class="w-50">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 text-right">
            <div class="input-group w-25 ml-auto">
                <input id="filterquiztext" type="text" class="form-control btn-outline-secondary rounded-0 border-top-0 border-left-0 border-right-0 border-bottom-1 border-warning" aria-label="Search topic" aria-describedby="inputGroup-sizing" placeholder="Enter topic name">
                <span class="input-group-append">
                    <div class="input-group-text bg-transparent rounded-0 border-top-0 border-left-0 border-right-0 border-bottom-1 border-warning"><i class="fa fa-search"></i></div>
                </span>
            </div>
        </div>       
    </div>

    <div id="quiz-modes"></div>

    <div class="row mt-2">
        <table id="quiztable" class="table table-striped">
            <thead>
                <tr>
                    <th class="td10"></th>
                    <th class="td40">Quiz Name</th>
                    <th class="td30">Questions</th>
                    <th class="td30">Action</th>
                </tr>
            </thead>
            <?php foreach ($view_quiz_all as $quiz) : ?>
                <tr>
                    <td><?php
                        ?></td>
                    <td id="title<?php echo $quiz->quiz_id; ?>"><?php echo $quiz->quiz_title; ?></td>
                    <td><?php echo $quiz->questioncount; ?></td>
                    <td>
                        <?php                        
                            echo "<button class='btn btn-sm bg-warning rounded-0 text-white startquizbutton' value='" . $quiz->quiz_id . "'>Run</button>";                        
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

<script nonce="**CSP_NONCE**">
    $('document').ready(function() {

        $('#filterquiztext').keyup(function() {
            filterQuiz();
        });


        $('.startquizbutton').click(function() {
            var quiz_id = $(this).val();

            var selector = "title" + quiz_id;
            var qtitle = $('#' + selector).html();

            sessionStorage.setItem('qtitle', qtitle);
            var url = "<?php echo base_url() . 'index.php/teacher/startquiz/"+ quiz_id +"'; ?>";
            $(location).attr('href', url);

        })

        $('#showinclass').click(function() {
            var url = "<?php echo base_url() . 'index.php/teacher/quiz/1'; ?>";
            $(location).attr('href', url);
        });
        $('#showathome').click(function() {
            var url = "<?php echo base_url() . 'index.php/teacher/quiz/2'; ?>";
            $(location).attr('href', url);
        });

        //change color on clicked nav-menu item
        //remove all active class, add active class on clicked item


    })

    function filterQuiz() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("filterquiztext");
        filter = input.value.toUpperCase();
        table = document.getElementById("quiztable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<script nonce="**CSP_NONCE**">
    $(document).ready(function() {
        $('.nav-item').removeClass('bg-warning');
        // $('#nav-run').addClass('bg-warning');
        $('#nav-run').addClass('font-weight-bold');
    });
</script>
</body>

</html>