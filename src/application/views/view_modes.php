<div class="container" id="modes">

    <div class="mt-4 row justify-content-center">
        <div class="col-lg-4 col-sm-8">
            <button id="option1" class="py-3 btn btn-outline-secondary rounded-0 my-1 btn-lg btn-block font-weight-bold" value='0'><span class="display-4 font-large">At Home</span></button>
        </div>
        <div class="col-lg-4 col-sm-8">
            <button id="option2" class="py-3 btn btn-outline-warning rounded-0 my-1 btn-lg btn-block font-weight-bold" value="1"><span class="display-4 font-large">In Class</span></button>
        </div>
        <script nonce="**CSP_NONCE**">
            $('document').ready(function() {
                $("#option1").click(function() {
                    window.location.replace('<?php echo base_url() . 'index.php/students/home/2'; ?>');
                });
                $("#option2").click(function() {
                    window.location.replace('<?php echo base_url() . 'index.php/students'; ?>');
                });

            });
        </script>
        </body>

        </html>