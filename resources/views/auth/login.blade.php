<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SPMR</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body style="background-image: url('{{ asset('assets/img/backgrounds/wallpaper_bg.jpeg') }}'); background-repeat: no-repeat;  background-size: cover;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <!-- Basic login form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    {{--<div class="card-header justify-content-center text-center"><h3 class="fw-light my-4">SPMR</h3></div>--}}
                                    <div class="card-header justify-content-center text-center">
                                        <img class="img-fluid" src="{{ asset('assets/img/mygovuc3.0.png') }}" alt="MyGovUC 3.0">
                                        <h3 class="my-4"><b>SISTEM PENGURUSAN MAIL RELAY <span>(SPMR)</span></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Login form-->
                                        <form method="POST" action="{{ route('login') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="login_method" value="local">
                                            <!-- Form Group (email address)-->
                                            <div class="mb-3 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label class="small mb-1" for="inputUsername">{{ trans('indexMessage.login.emailAddress') }}</label>
                                                <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter Email"   required autofocus/>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- Form Group (password)-->
                                            <div class="mb-3 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control" id="password" name="password" type="password" placeholder="Enter password" required/>
                                            </div>
                                           <!-- Form Group (remember password checkbox) -->
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="rememberPasswordCheck" name="remember" type="checkbox" value="{{ old('remember') ? 'checked' : '' }}" />
                                                    <label class="form-check-label" for="rememberPasswordCheck">Remember password</label>
                                                </div>
                                            </div>

                                            <!-- Form Group (login box)-->
                                            <div class="d-flex flex-column align-items-center justify-content-between mt-4 mb-0">
                                                <!-- <a class="small" href="auth-password-basic.html">Forgot Password?</a> -->
                                                <button type="submit" class="btn btn-rounded btn-primary btn-block mt-2 mx-auto" style="width:40%;">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer" hidden>
                <footer class="footer-admin mt-auto footer-dark" style="background-color:rgb(20, 20, 20);">
                    <div class="container-xl">
                        <div class="text-center">
                            <p style="margin: 0;">Ini adalah akses yang tertutup untuk digunakan oleh MAMPU, Jabatan Perdana Menteri</p>
                            <p style="margin: 0;">Copyright &copy; {{ date('Y') }} Unit Pemodenan Tadbiran dan Perancangan Pengurusan Malaysia (MAMPU)</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
