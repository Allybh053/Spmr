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
                                    <h3 class="fw-light my-4">SAML2 Login</h3>
                                </div>
                                <div class="card-body">
                                    <!-- SAML2 login form -->
                                    <form method="GET" action="{{ route('saml.login') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-rounded btn-primary btn-block mt-2" style="width:40%;">Login with SAML2</button>
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
