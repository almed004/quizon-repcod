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
        .blue {
            background-color: #428bca;
        }

        .grey {
            background-color: #6c757d;
        }
    </style>

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

        <div class="row mt-3">

            <div class="col bg">
                <h4><?php echo trim($questiontext, "\""); ?>.</h4>
                <hr />
            </div>

        </div>

        <!-- Latest compiled and minified CSS -->
        <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" /> -->


        <!-- Latest Sortable -->
        <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>


        <div class="row">
            <div class="col">
                <div id="quest" class="list-group">
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching1, "\"") ?></p>
                    </div>
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching2, "\"") ?></p>
                    </div>
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching3, "\"") ?></p>
                    </div>
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching4, "\"") ?></p>
                    </div>
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching5, "\"") ?></p>
                    </div>
                    <div class="list-group-item" style="background-color:#6c757d; margin-top:0.2em;">
                        <p style="font-size:20px;" class="text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($matching6, "\"") ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div id="simpleList" class="list-group">
                    <div data-id="1" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option1, "\"") ?></p>
                    </div>
                    <div data-id="2" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option2, "\"") ?></p>
                    </div>
                    <div data-id="3" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option3, "\"") ?></p>
                    </div>
                    <div data-id="4" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option4, "\"") ?></p>
                    </div>
                    <div data-id="5" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option5, "\"") ?></p>
                    </div>
                    <div data-id="6" class="list-group-item" style="background-color:#428bca; margin-top:0.2em; cursor:move;" ondrag="drag(event);" ondrop="drop(event);">
                        <p style="font-size:20px;" class="text-white"><i class="fas fa-ellipsis-v"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($option6, "\"") ?></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="row text-center">
            <!-- <div class="col-sm-3">

            </div> -->
            <div class="col">
                <form id="formsubmitAnswer" action="<?php echo base_url('/students/submitAnswer'); ?>" method="post"> 
                    <input id="idQuestionStart" name="questionStart" type="hidden" value="<?php echo trim($idstart, "\""); ?>"> 
                    <input id="studentAnswer" name="studentAnswer" type="hidden" value="">
                    <input id="studentID" name="studentID" type="hidden" value="eummy001">

                    <button class="shadow py-3 btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white" onclick="sortable();">Submit Answer</button>
                </form>
            </div>
        </div>

    </div>
    <!-- <script src="http://localhost/quizalpha_ci3/js/Sortable.js"></script> -->
    <script>
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

        // // List with handle
        // Sortable.create(listWithHandle, {
        //     // handle: '.glyphicon-move',
        //     // animation: 150
        // });
        function sortable() {
            // alert(Sortable.get(simpleList).toArray());
            var s = document.getElementById("studentAnswer");
            s.value = Sortable.get(simpleList).toArray();
            // console.log(Sortable.get(simpleList).toArray());
        }

        function drag(event) {
            event.currentTarget.style.background = "#6c757d";
        }

        function drop(event) {
            event.currentTarget.style.background = "#428bca";
        }
    </script>
</body>

</html>

<!-- Simple List -->
<!-- <div id="simpleList" class="list-group">
            <div class="list-group-item">This is <a href="http://rubaxa.github.io/Sortable/">Sortable</a></div>
            <div class="list-group-item">It works with Bootstrap...</div>
            <div class="list-group-item">...out of the box.</div>
            <div class="list-group-item">It has support for touch devices.</div>
            <div class="list-group-item">Just drag some elements around.</div>
        </div> -->

<!-- <div id="listWithHandle" class="list-group">
            <div class="list-group-item">
                <span class="badge">14</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                Drag me by the handle
            </div>
            <div class="list-group-item">
                <span class="badge">2</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                You can also select text
            </div>
            <div class="list-group-item">
                <span class="badge">1</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                Best of both worlds!
            </div>
        </div> -->