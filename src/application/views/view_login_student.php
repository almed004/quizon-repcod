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
        .bigFont {
            font-size: 20px;
        }

        .login-or {
            position: relative;
            color: #aaa;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .span-or {
            display: block;
            position: absolute;
            left: 50%;
            top: -2px;
            margin-left: -25px;
            background-color: #fff;
            width: 50px;
            text-align: center;
        }

        .hr-or {
            height: 1px;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
        }
    /* color theme */
    .main-yellow {
            color: hsl(45, 100%, 51%);
        }

        .light-yellow {
            color: hsl(45, 70%, 71%);
        }

        .bg-light-yellow {
            background-color: hsl(45, 51%, 89%);
        }

        .bg-main-green {
            background-color: #607d8b;
        }

        .main-green {
            color: #607d8b;
        }

        .dark-green {
            color: hsl(206, 43%, 16%);
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row text-center mt-3">
            <div class="col">

            <i class="fa fa-check-circle main-green" style="font-size:20pt;"></i>
                <span class="font-weight-bold main-green" style="font-size:20pt;">Quizitor</span>

            

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">

            </div>

            <div class="col-sm-6">

                <form action="<?php echo base_url('students/processlogin'); ?>" method="post">
                    <div class="form-group">
                        <label><?php //echo $title; ?></label>
                        <span class="text-danger mt-2"><?php echo $this->session->flashdata("error"); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email" class="bigFont font-weight-bold">Email address</label>
                        <input type="email" name="email" class="bigFont form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password" class="bigFont font-weight-bold">Password</label>
                        <input type="password" name="password" id="password" class="bigFont form-control" aria-describedby="emailHelp" placeholder="Enter Password">
                        <span class="text-danger"><?php echo form_error('password'); ?></span>
                    </div>
                    <div class="form-group">
                        <button class="shadow py-3 btn btn-warning rounded-0 mt-4 btn-lg btn-block font-weight-bold text-white">Login</button>
                        
                    </div>
                </form>
                <div class="form-group">
                    <span><a href="#" class="text-secondary">Forgot Password?</a></span>
                </div>
                <div class="form-group">
                    <div class="login-or">
                        <hr class="hr-or">
                        <span class="span-or">or</span>
                    </div>
                </div>
                <div class="text-center form-group">
                    <span class="text-secondary">Don't have account? <a href="<?php echo base_url('students/registerstudent'); ?>" id="signup">Sign up here</a></span>
                </div>

            </div>
        </div>
    </div>
</body>

</html>