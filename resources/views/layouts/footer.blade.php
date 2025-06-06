<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.select2').select2();

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
                // Kirim form untuk menghapus pengguna
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }

    
  </script>

  @if(session('success'))
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          showSuccessAlert("{{ session('success') }}");
      });
  </script>
  @endif

  @if(session('error'))
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          showErrorAlert("{{ session('error') }}");
      });
  </script>
  @endif


  
@stack('scripts')