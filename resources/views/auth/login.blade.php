<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-library | Login</title>
    
    <!-- Google Fonts - Modern Font Combination -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icon Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    
    <!-- Custom Style -->
    <style>
      body {
        font-family: 'Poppins', sans-serif;
      }
      
      .auth-form-light {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(58, 42, 31, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }
      
      .brand-logo h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        font-size: 2.5rem;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
      }
      
      .brand-logo h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 1.1rem;
        opacity: 0.8;
        margin-bottom: 2rem;
      }
      
      .logo-img {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        display: block;
        object-fit: contain;
      }
     
      .invalid-feedback {
        font-size: 0.85rem;
        color: #dc3545;
      }
      
      .alert {
        border-radius: 8px;
        font-size: 0.9rem;
      }
      
      /* Password toggle button */
      .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #6c757d;
      }
      
      .password-input-group {
        position: relative;
      }
      
      /* Animation for form */
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      .auth-form-light {
        animation: fadeIn 0.6s ease-out;
      }
      
      /* Background styles */
      .content-wrapper {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                    url("{{ asset('assets/images/bg-login.jpeg') }}") no-repeat center center;
        background-size: cover;
        min-height: 100vh;
      }
      
      /* Alternative background options (uncomment to use) */
      /*
      .content-wrapper {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                    url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center;
        background-size: cover;
      }
      */
      
      /* Responsive adjustments */
      @media (max-width: 576px) {
        .auth-form-light {
          padding: 2rem 1.5rem !important;
        }
        
        .brand-logo h2 {
          font-size: 2rem;
        }
        
        .logo-img {
          width: 60px;
          height: 60px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo text-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="E-library Logo" class="logo-img">
                  <h2 class="text-center text-primary">E-library</h2>
                  <h4 class="font-weight-light text-center">Halaman Login</h4>
                </div>
              
                @if(session('error'))
                  <div class="alert alert-danger">
                    {{ session('error') }}
                  </div>
                @endif
                
                <form class="pt-3" method="POST" action="{{ route('loginProses') }}">
                  @csrf
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                           id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                    @error('username')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group password-input-group">
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Password">
                    <button type="button" class="password-toggle" id="togglePassword">
                      <i class="fa fa-eye"></i>
                    </button>
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
                  </div>
                  <br>
                  <center>
                    <a class="text-center" href="{{ route('lupa_password') }}">Lupa password</a>
                  </center>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      // Password toggle functionality
      document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
          // Toggle the type attribute
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          
          // Toggle the eye icon
          this.querySelector('i').classList.toggle('fa-eye');
          this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Fungsi dasar SweetAlert
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: message,
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: message,
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }

        function confirmDelete(userId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }

        @if(session('success'))
        showSuccessAlert("{{ session('success') }}");
        @endif

        @if(session('error'))
        showErrorAlert("{{ session('error') }}");
        @endif
      });
    </script>
  </body>
</html>