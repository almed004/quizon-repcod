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

        <div class="row text-right">
            <div class="col-lg-12">
                <form id="formsubmitAnswer" action="<?php echo base_url('index.php/students/submitAnswer'); ?>" method="post">
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>">
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <input id="answerText" name="answerText" type="hidden" value="">
                    <input id="idQuestion" name="idquestion" type="hidden" value="<?php echo trim($questionid, "\""); ?>">
                    <button id="submitAnswer" class="py-1 btn btn-warning rounded-0 mt-4 font-weight-bold not-allowed text-white" value="">Submit Answer</button>
                </form>
            </div>
        </div>

        <script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/prism.js"></script>

        <script nonce="**CSP_NONCE**">
            $(document).ready(function() {
                localStorage.removeItem('localStorage-example');
                let option=[
                '<li data-id="1" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option1, "\""); ?></li>',
                '<li data-id="2" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option2, "\""); ?></li>',
                '<li data-id="3" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option3, "\""); ?></li>',
                '<li data-id="4" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option4, "\""); ?></li>',
                '<li data-id="5" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option5, "\""); ?></li>',
                '<li data-id="6" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option6, "\""); ?></li>',
                '<li data-id="7" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option7, "\""); ?></li>',
                '<li data-id="8" class="orderItem py-2 btn btn-outline-secondary rounded-0 my-1 btn-block"><span class="float-left font-weight-bold">&#8942;</span><?= trim($option8, "\""); ?></li>'];
                shuffle(option);
                // console.log(option);
                $('#simpleList').append(option);

                function shuffle(array) {
                    array.sort(()=>Math.random()-0.5);
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

                $('#trysubmit').click(function(){
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
                $('#submitAnswer').click(function() {
                    $('#alertanswer').removeClass("Disabled");
                    $('#alertanswer').addClass("Enabled");
                });
            });
        </script>
        </body>

        </html>