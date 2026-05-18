<!-- Start Login-Modal -->
<div class="modal login-modal fade gt-bg-s3" id="loginBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="loginBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content theme-bg-s1 box-shadow-s1 rounded-12 border-0">

            <div class="modal-body p-md-5 p-4 fixed-modal-body">

                <!-- Close Button -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="row">
                    <div class="col-12">

                        <!-- Heading -->
                        <div class="text-center mb-3">
                            <h3 class="change-fs-28px-24px gt-text-theme fw-600 mb-0">
                                Meetup
                            </h3>
                        </div>

                        <!-- Tabs -->
                        <!-- Tabs -->
                        <ul class="nav nav-pills mb-4 justify-content-center gap-2" id="loginTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active custom-tab-btn" id="social-login-tab"
                                    data-bs-toggle="pill" data-bs-target="#social-login" type="button" role="tab">
                                    Social Login
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link custom-tab-btn" id="organisation-login-tab"
                                    data-bs-toggle="pill" data-bs-target="#organisation-login" type="button" role="tab">
                                    Login as Organisation
                                </button>
                            </li>

                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="loginTabContent">

                            <!-- Social Login Tab -->
                            <div class="tab-pane fade show active" id="social-login" role="tabpanel">

                                <div class="text-center pb-3 mb-3 border-bottom">
                                    <h3 class="fs-24px">Sign in</h3>

                                    <p class="mb-0 fs-14px">
                                        You can now sign up on Web-Name with your Google or Facebook account.
                                    </p>
                                </div>

                                <!-- Google Login -->
                                <div class="text-center mb-3">
                                    <a href="auth/google/redirect" class="btn btn-primary w-100" id="loginBtn">

                                        <span class="bg-white py-1 px-2 rounded-8 me-2">

                                            <svg width="20px" height="20px" viewBox="-3 0 262 262"
                                                xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">

                                                <path
                                                    d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                                                    fill="#4285F4" />

                                                <path
                                                    d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.02 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                                                    fill="#34A853" />

                                                <path
                                                    d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                                                    fill="#FBBC05" />

                                                <path
                                                    d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                                                    fill="#EB4335" />

                                            </svg>
                                        </span>

                                        Connect With Google
                                    </a>
                                </div>

                                <!-- Terms -->
                                <div class="text-center fs-14px">
                                    <p class="mb-0">
                                        By signing up, you agree to the
                                        <a href="./term-condition.html" class="gt-text-theme">
                                            Terms & Conditions
                                        </a>
                                        and
                                        <a href="./privacy-policy.html" class="gt-text-theme">
                                            Privacy Policy
                                        </a>,
                                        including Cookie Use.
                                    </p>
                                </div>

                            </div>

                            <!-- Organisation Login Tab -->
                            <!-- Organisation Login/Register Tab -->
                            <div class="tab-pane fade" id="organisation-login" role="tabpanel">

                                <!-- Heading -->
                                <div class="text-center pb-3 mb-4">
                                    <h3 class="fs-24px">Organisation Access</h3>

                                    <p class="mb-0 fs-14px">
                                        Login or register your organisation account.
                                    </p>
                                </div>

                                <!-- Inner Tabs -->
                                <ul class="nav nav-tabs organisation-auth-tabs mb-4 justify-content-center"
                                    id="organisationAuthTab" role="tablist">

                                    <!-- Login Tab -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="org-login-tab" data-bs-toggle="tab"
                                            data-bs-target="#org-login" type="button" role="tab">
                                            Login
                                        </button>
                                    </li>

                                    <!-- Register Tab -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="org-register-tab" data-bs-toggle="tab"
                                            data-bs-target="#org-register" type="button" role="tab">
                                            Register
                                        </button>
                                    </li>

                                </ul>

                                <!-- Inner Tab Content -->
                                <div class="tab-content">

                                    <!-- Login Form -->
                                    <div class="tab-pane fade show active" id="org-login" role="tabpanel">
                                        <p id="login-error" style="color:red;"></p>
                                        <form id='loginForm'>
                                            @csrf
                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>

                                                <input type="text" class="form-control" name="email"
                                                    placeholder="Enter email">
                                                <span class="text-danger error-text" id="email_error"></span>
                                                
                                            </div>

                                            <!-- Password -->
                                            <div class="mb-4">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Enter password">
                                                <span class="text-danger error-text" id="password_error"></span>
                                            </div>

                                            <!-- Submit -->
                                            <!-- Login Button -->
                                            <button type="submit" class="btn theme-auth-btn w-100 rounded-8 py-2">
                                                Login
                                            </button>


                                        </form>

                                    </div>

                                    <!-- Register Form -->
                                    <div class="tab-pane fade" id="org-register" role="tabpanel">

                                        <form id="registrationForm">
                                            @csrf
                                            <!-- Organisation Name -->
                                            <div class="mb-3">
                                                <label class="form-label">Organisation Name</label>

                                                <input type="text" class="form-control" name="organization_name"
                                                    placeholder="Enter organisation name"
                                                    value="{{ old('organization_name') }}">
                                               <span class="text-danger error-text" id="organization_name_error"></span>
                                            </div>

                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>

                                                <input type="text" class="form-control" name="email"
                                                    placeholder="Enter email" value="{{ old('email') }}">
                                                <span class="text-danger error-text" id="email_error"></span>
                                            </div>

                                            <!-- Password -->
                                            <div class="mb-4">
                                                <label class="form-label">Password</label>

                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Create password">
                                                 <span class="text-danger error-text" id="password_error"></span>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    placeholder="Confirm Password">
                                            </div>

                                            <!-- Submit -->
                                            <button type="submit" class="btn theme-auth-btn w-100 rounded-8 py-2">
                                                Register
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- End Login-Modal -->