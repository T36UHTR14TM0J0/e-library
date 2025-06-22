<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-library | Lupa Password</title>
    
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
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(58, 42, 31, 0.1);
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
      
      .invalid-feedback {
        font-size: 0.85rem;
        color: #dc3545;
      }
      
      .alert {
        border-radius: 8px;
        font-size: 0.9rem;
      }
      
      /* Animation for form */
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      .auth-form-light {
        animation: fadeIn 0.6s ease-out;
      }
      
      .back-to-login {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
      }
      
      /* Responsive adjustments */
      @media (max-width: 576px) {
        .auth-form-light {
          padding: 2rem 1.5rem !important;
        }
        
        .brand-logo h2 {
          font-size: 2rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0 bg-primary">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <h2 class="text-center text-primary">E-library</h2>
                  <h4 class="font-weight-light text-center">Reset Password</h4>
                </div>
                
                @if (session('status'))
                  <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                  </div>
                @endif
                
                <form class="pt-3" method="POST" action="{{ route('password.email') }}">
                  @csrf
                  
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                      Kirim Link Reset Password
                    </button>
                  </div>
                  
                  <div class="back-to-login">
                    <a href="{{ route('login') }}" class="text-primary">Kembali ke halaman login</a>
                  </div>
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

      @if(session('status'))
      document.addEventListener('DOMContentLoaded', function() {
          showSuccessAlert("{{ session('status') }}");
      });
      @endif

      @if($errors->any())
      document.addEventListener('DOMContentLoaded', function() {
          @foreach ($errors->all() as $error)
              showErrorAlert("{{ $error }}");
          @endforeach
      });
      @endif
    </script>
  </body>
</html>