@extends('layout.auth')
@section('content')
    <main>
        @if (session('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Welcome!</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <form class="row g-3 needs-validation" action="{{ route('login-process') }}" novalidate
                                        method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text login-icon" id="basic-addon1"><i
                                                        class="bi bi-envelope"></i></span>
                                                <input name="email" type="text" class="form-control"
                                                    placeholder="Your Email" aria-label="Your Email"
                                                    aria-describedby="basic-addon1">
                                            </div>
                                            @error('email')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text login-icon" id="basic-addon1"><i
                                                        class="bi bi-lock"></i></span>
                                                <input name="password" type="password" class="form-control"
                                                    placeholder="Your Password" aria-label="Your Password"
                                                    aria-describedby="basic-addon1">
                                            </div>
                                            @error('password')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12 login-flex">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                            <div class="form-check">
                                                <a href="pages-register.html">Forgot your password?</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary rounded-pill bgc-primary">Sign
                                                in</button>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create
                                                    an account</a></p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>

        @if ($message = Session::get('failed'))
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ $message }}",
                });
            </script>
        @endif
    </main><!-- End #main -->
@endsection
