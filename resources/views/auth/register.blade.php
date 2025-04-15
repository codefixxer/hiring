<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maxton | Bootstrap 5 Admin Dashboard Template</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png">
  <!-- loader--> 
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}">
  <!--bootstrap css-->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/sass/responsive.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

  <div class="section-authentication-cover">
    <div class="">
      <div class="row g-0">

        <!-- left-side image -->
        <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex border-end bg-transparent">
          <div class="card rounded-0 mb-0 border-0 shadow-none bg-transparent">
            <div class="card-body">
              <img src="{{ asset('assets/images/auth/login1.png') }}"
                   class="img-fluid auth-img-cover-login"
                   width="650" alt="">
            </div>
          </div>
        </div>

        <!-- register form -->
        <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center border-top border-4 border-primary border-gradient-1">
          <div class="card rounded-0 m-3 mb-0 border-0 shadow-none bg-none">
            <div class="card-body p-sm-5">
              <img src="{{ asset('assets/images/logo1.png') }}"
                   class="mb-4" width="145" alt="">
              <h4 class="fw-bold">Create Account</h4>
              <p class="mb-0">Enter your details to register a new account</p>

              <!-- (optional) social buttons, if you want to keep them -->
              <div class="row g-3 my-4">
                <div class="col-12 col-lg-6">
                  <form action="{{ route('google.login') }}">
                    @csrf
                    <button class="btn btn-light py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100">
                      <img src="{{ asset('assets/images/apps/05.png') }}" width="20" class="me-2" alt="">Google
                    </button>
                  </form>
                </div>
                <div class="col-12 col-lg-6">
                  <form action="{{ route('facebook.login') }}">
                    @csrf
                    <button class="btn btn-light py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100">
                      <img src="{{ asset('assets/images/apps/17.png') }}" width="20" class="me-2" alt="">Facebook
                    </button>
                  </form>
                </div>
              </div>

              <div class="separator section-padding">
                <div class="line"></div>
                <p class="mb-0 fw-bold">OR</p>
                <div class="line"></div>
              </div>

              <div class="form-body mt-4">
                <form id="registerForm" action="{{ route('register') }}" method="POST">
                  @csrf

                  <!-- Full Name -->
                  <div class="form-group mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input class="form-control" type="text"
                           id="name" name="name"
                           required placeholder="Enter your full name"
                           value="{{ old('name') }}">
                  </div>

                  <!-- Email -->
                  <div class="form-group mb-3">
                    <label for="emailaddress" class="form-label">Email address</label>
                    <input class="form-control" type="email"
                           id="emailaddress" name="email"
                           required placeholder="Enter your email"
                           value="{{ old('email') }}">
                  </div>

                  <!-- Password -->
                  <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                      <input class="form-control" type="password"
                             name="password" id="password"
                             required placeholder="Enter your password">
                      <button class="btn btn-outline-secondary"
                              type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                      </button>
                    </div>
                  </div>

                  <!-- Submit -->
                  <div class="form-group mb-0 row">
                    <div class="col-12">
                      <div class="d-grid">
                        <button class="btn btn-primary" type="submit">Register</button>
                      </div>
                    </div>
                  </div>

                  <!-- Already have account -->
                  <div class="text-center mt-3">
                    <p>Already have an account?
                      <a href="{{ route('loginform') }}">Login</a>
                    </p>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
        <!-- end register form -->

      </div>
      <!--end row-->
    </div>
  </div>

  <!--plugins-->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

  <!-- optional old show/hide script (won't break anything) -->
  <script>
    $(document).ready(function () {
      $("#show_hide_password a").on('click', function (event) {
        event.preventDefault();
        const input = $('#show_hide_password input');
        const icon  = $('#show_hide_password i');
        if (input.attr("type") === "text") {
          input.attr('type', 'password');
          icon.addClass("bi-eye-slash-fill").removeClass("bi-eye-fill");
        } else {
          input.attr('type', 'text');
          icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
      });
    });
  </script>

  <!-- toggle password visibility -->
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const pwd = document.getElementById('password');
      const eye = document.getElementById('eyeIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        pwd.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  </script>
</body>

</html>
