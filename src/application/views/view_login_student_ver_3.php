<div class="container" id="form-login-student">
    <div class="row justify-content-center mt-5 mx-1">
        <div class="border col-lg-4 col-md-6 shadow bg-white px-4 py-4">
            <div class="text-center mt-4">
                <h3><i class="fa fa-check-circle mr-2 sizef"></i>Quizon</h3>
                <hr class="w-75" />
            </div>
            <form action="<?php echo base_url('index.php/students/processlogin'); ?>" method="post">
                <div class="form-group">
                    <span class="text-danger mt-2"><?php echo $this->session->flashdata("error"); ?></span>
                </div>
                <div class="form-group">
                    <label for="studentid" class="bigFont font-weight-bold dark-green">Student ID</label>
                    <input type="text" name="studentid" class="rounded-0 form-control" id="studentid" aria-describedby="studentidHelp" placeholder="Enter The Student ID">
                    <span class="text-danger"><?php echo form_error('studentid'); ?></span>
                </div>
                <div class="form-group">
                    <label for="password" class="rounded-0 bigFont font-weight-bold dark-green">Password</label>
                    <input type="password" name="password" id="password" class="rounded-0 form-control" aria-describedby="passwordHelp" placeholder="Enter Password">
                    <span class="text-danger"><?php echo form_error('password'); ?></span>
                </div>
                <div class="form-group">
                    <input id="inBattery" type="hidden" name="inBattery" value="">
                    <input id="batteryLevel" type="hidden" name="batteryLevel" value="">

                </div>
                <div class="form-group">
                    <button class="quizbutton shadow btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white mb-5">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script nonce="**CSP_NONCE**">
    $('document').ready(function() {
        $('#form-login-student').fadeIn('slow');

        navigator.getBattery().then(function(battery) {
            function updateAllBatteryInfo() {
                updateChargeInfo();
                updateLevelInfo();
                updateChargingInfo();
                updateDischargingInfo();
            }
            updateAllBatteryInfo();

            battery.addEventListener('chargingchange', function() {
                updateChargeInfo();
            });

            function updateChargeInfo() {
                // console.log("Battery charging? " +
                //     (battery.charging ? "Yes" : "No"));
                $('#inBattery').attr('value',battery.charging ? true : false);

            }

            battery.addEventListener('levelchange', function() {
                updateLevelInfo();
            });

            function updateLevelInfo() {
                // console.log("Battery level: " +
                //     battery.level * 100 + "%");
                $('#batteryLevel').attr('value',battery.level*100);
            }
        });

    });
</script>
</body>

</html>