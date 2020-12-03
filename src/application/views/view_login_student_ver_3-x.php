<div class="container" id="form-login-student">
    <div class="row justify-content-center mt-5">
        <div class="border col-lg-4 col-md-6 shadow bg-white">
            <div class="text-center mt-4">
                <h3><i class="fa fa-check-circle mr-2"></i>Quizon</h3>
                <hr class="w-75" />
            </div>
            <form action="<?php echo base_url('index.php/students/processlogin'); ?>" method="post">
                <div class="form-group">
                    <label><?php //echo $title; 
                            ?></label>
                    <span class="text-danger mt-2"><?php echo $this->session->flashdata("error"); ?></span>
                </div>
                <div class="form-group">
                    <label for="studentid" class="bigFont font-weight-bold dark-green">Student ID</label>
                    <input type="text" name="studentid" class="rounded-0 bigFont form-control" id="studentid" aria-describedby="studentidHelp" placeholder="Enter The Student ID">
                    <span class="text-danger"><?php echo form_error('studentid'); ?></span>
                </div>
                <div class="form-group">
                    <label for="password" class="rounded-0 bigFont font-weight-bold dark-green">Password</label>
                    <input type="password" name="password" id="password" class="bigFont form-control" aria-describedby="passwordHelp" placeholder="Enter Password">
                    <span class="text-danger"><?php echo form_error('password'); ?></span>
                </div>
                <div class="form-group">
                    <button class="quizbutton shadow btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white mb-5">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script nonce="**CSP_NONCE**">
    $('document').ready(function() {
        $('#form-login-student').fadeIn('slow');
    });
</script>
</body>

</html>