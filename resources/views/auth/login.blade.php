<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{URL::asset('assets/images/Logo-fav.png')}}" type="image/x-icon">
        <title>{{config('app.name')}} | Login</title>
        <link rel="stylesheet" href="{{URL::asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            window.setTimeout(function() {
                $(".alert-timeout").fadeTo(500, 0).slideUp(1000, function(){
                    $(this).remove(); 
                });
            }, 8000);
        </script>
    </head>
    <body>
        <!-- Alerts  Start-->
        <div style="position: fixed; top: 10px; right: 20px; z-index: 100000; width: auto;">
            @include('layouts.alert')
        </div>
        <!-- Alerts End -->
        <div id='loader'>
            <div class="loader-inner">
                <div class="loading-content"></div>
            </div>
        </div>
        <!-- login -->
            <section class="login">
                <div class="container-fuild">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-div">
                                        <div class="sidelist">
                                            <a href="{{route('index')}}">
                                                <img src="https://res.cloudinary.com/greenmouse-tech/image/upload/v1660217514/OjaFunnel-Images/Logo_s0wfpp.png" draggable="false" alt="">
                                            </a>
                                            <h4></h4>
                                            <form class="sign-div" action="{{ route('user.login')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <!--Email-->
                                                    <div class="col-lg-12">
                                                        <label>Email/Username</label>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-4">
                                                                <i class="bi bi-envelope"></i>
                                                                <input type="text" placeholder="Enter your email/username" name="email" class="input" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Password-->
                                                    <div class="col-lg-12">
                                                        <label>Password</label>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-4">
                                                                <i class="bi bi-file-lock"></i>
                                                                <input type="password" placeholder="Enter your prefered password" name="password" class="input">
                                                                <i class="toggle-password fa fa-fw fa-eye-slash" title="Toggle to show/hide password"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>
                                                        <a href="{{route('forgot')}}">
                                                            Forgot Password ?
                                                        </a>
                                                    </p>
                                                    <div class="col-md-5 mb-2">
                                                        <button type="submit" name="submit" value="login">
                                                            Log In
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        OR
                                                    </div>
                                                    <div class="col-md-5 mb-2">
                                                        <button type="submit" name="submit" value="magic-link">
                                                            {{ __('Send Magic Link') }}
                                                        </button>
                                                    </div>
                                                    <p class="have">
                                                        Don't have an account ?
                                                        <a href="{{route('signup')}}">
                                                        Sign Up
                                                        </a>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- <div class="btn-divv">
                                <div class="portlog">
                                    <a href="/">
                                        <img src="https://res.cloudinary.com/greenmouse-tech/image/upload/v1662630837/OjaFunnel-Images/signup-image_ecquqt.png" draggable="false">
                                    </a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </section>
        <!-- login Ends -->
    </body>
    <script>
        $(function() {
            $("form").submit(function() {
                $('#loader').show();
            });
        });
    </script>
    <script>
            // Script for Show/Hide Password 
            $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            input = $(this).parent().find("input");
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
</html>