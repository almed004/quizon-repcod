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
        <div class="row text-center my-3">
            <div class="col">

                <i class="fa fa-check-circle main-green" style="font-size:20pt;"></i>
                <span class="font-weight-bold main-green" style="font-size:20pt;">Quizitor</span>

            </div>
        </div>
        <div class="row bg-warning py-1">
            <nav class="navbar navbar-expand-lg navbar-light py-1">
                <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link text-white font-weight-bold" href="<?php echo base_url() . 'students/modes'; ?>">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white font-weight-bold" href="<?php echo base_url() . 'students/changepassword'; ?>">Change Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white font-weight-bold" href="<?php echo base_url() . 'students/logout'; ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="row text-center mt-5">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
               <h5 class="text-danger"><?php echo $error_message; ?></h5>
            </div>
        </div>

        <div class="row text-center mt-5">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <button id="option1" class="shadow py-3 btn btn-danger rounded-0 my-1 btn-lg btn-block" value='0'>Back</button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <!-- <button id="option2" class="shadow py-3 btn btn-warning rounded-0 my-1 btn-lg btn-block value='0'">In Class</button> -->
            </div>
        </div>
    </div>
    </div>

    <script nonce="**CSP_NONCE**">
        $("#option1").click(function() {
            window.location.replace('<?php echo base_url() . 'students/home'; ?>');
        });
        $("#option2").click(function() {
            window.location.replace('<?php echo base_url() . 'students'; ?>');
        });
    </script>
</body>

</html>