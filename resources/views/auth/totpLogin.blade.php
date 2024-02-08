<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your styles and scripts -->
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">TOTP Login</h3>
                                </div>
                                <div class="card-body">
                                    <!-- TOTP login form -->
                                    <form method="POST" action="{{ route('2fa.verify') }}">
                                        @csrf
                                        <div class="mb-3 form-group">
                                            <label class="small mb-1" for="inputTOTP">TOTP Code</label>
                                            <input class="form-control" id="totp_code" name="totp_code" type="text" placeholder="Enter TOTP Code" required autofocus/>
                                        </div>
                                        <button type="submit" class="btn btn-rounded btn-primary btn-block mt-2" style="width:40%;">Verify TOTP</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Include your scripts -->
</body>
</html>
