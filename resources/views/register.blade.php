@extends('layout.auth')
@section('content')
    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Create an account</h5>
                                    </div>

                                    <form id="registerForm" class="row g-3 needs-validation" novalidate>
                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                        <div class="col-12">
                                            <div class="input-group mb-1">
                                                <span class="input-group-text login-icon" id="basic-addon1">
                                                    <i class="bi bi-person-circle"></i>
                                                </span>
                                                <input type="text" id="username" class="form-control"
                                                    placeholder="Your Name" aria-label="Your Name"
                                                    aria-describedby="basic-addon1" required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="input-group mb-1">
                                                <span class="input-group-text login-icon" id="basic-addon1">
                                                    <i class="bi bi-envelope"></i></span>
                                                <input type="email" id="email" class="form-control"
                                                    placeholder="Your Email" aria-label="Your Email"
                                                    aria-describedby="basic-addon1" required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="input-group mb-1">
                                                <span class="input-group-text login-icon" id="basic-addon1"><i
                                                        class="bi bi-lock"></i></span>
                                                <input type="password" id="password" class="form-control"
                                                    placeholder="Your Password" required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="input-group mb-1">
                                                <span class="input-group-text login-icon" id="basic-addon1"><i
                                                        class="bi bi-lock"></i></span>
                                                <input type="password" id="confirmPassword" class="form-control"
                                                    placeholder="Confirm Your Password">

                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe" required>
                                                <label class="form-check-label" for="rememberMe">I agree with the
                                                    <strong>privacy
                                                        policy</strong> and <strong>terms & conditions</strong></label>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" id="registerBtn"
                                                class="btn btn-primary rounded-pill bgc-primary" disabled>
                                                Register
                                            </button>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="small mb-0">Have account? <a href="pages-register.html">Sign In</a>
                                            </p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <script>
        $(document).ready(function() {
            var passwordsMatch = null

            function checkFormValidity() {
                const form = $('#registerForm')[0];
                const isFormValid = form.checkValidity(); // Check overall form validity
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();
                passwordsMatch = password === confirmPassword && password.length > 0;

                // Enable or disable the button based on both form validity and password match
                $('#registerBtn').prop('disabled', !(isFormValid && passwordsMatch));

                // Custom validation for password matching
                if (passwordsMatch) {
                    $('#confirmPassword').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#confirmPassword').removeClass('is-valid').addClass('is-invalid');
                }
            }

            // Attach event handlers to input fields
            $('#password, #confirmPassword').on('input', checkFormValidity);
            $('#registerForm input').not('#confirmPassword').on('input', function() {
                // Validate individual fields and apply feedback
                if (this.checkValidity()) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
                // Check overall form validity after any input change
                checkFormValidity();
            });

            // Prevent form submission if invalid
            $('#registerForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                const csrfToken = $('#_token').val();
                if (!this.checkValidity() || !passwordsMatch) {
                    $(this).addClass('was-validated');
                    return;
                }

                // Send data to server
                $.ajax({
                    url: "/register",
                    type: 'POST',
                    data: {
                        name: $('#username').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                        }).then(() => {
                            $('#registerForm')[0].reset();
                            $('#registerBtn').prop('disabled',
                            true); // Reset button state
                            window.location.href = '/';
                        });
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            // Handle validation errors
                            for (const key in errors) {
                                $(`#${key}`).addClass('is-invalid').after(
                                    `<div class="invalid-feedback">${errors[key][0]}</div>`);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
