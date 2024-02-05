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
    <body style="background-image: url('{{ asset('assets/img/backgrounds/login-background.jpg') }}'); background-repeat: no-repeat;  background-size: cover;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <nav class="navbar bg-black" id="sidenavAccordion">
                    <div class="container-xl" style="text-align: center;">
                            <h4 style="margin: 0; text-color:white;">Selamat Datang ke Pautan Pintas</h4>
                    </div>
                </nav>
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <!-- Basic login form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                      
                    <div class="card-header">{{ __('TOTP QR Code') }}</div>

                    <div class="card-body">
                        <!-- Display the QR code image -->
                        <img src="{{ $google2faUrl }}" alt="TOTP QR Code">
                        
                        <p>Scan this QR code using your authenticator app (e.g., Google Authenticator, Authy).</p>
                    </div>
               


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
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
