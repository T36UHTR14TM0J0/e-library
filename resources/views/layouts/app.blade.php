  @include('layouts.header')
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      @include('layouts.navbar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        @include('layouts.sidebar')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
           <div class="row">
              <!-- [ Sample Page ] mulai -->
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header bg-primary">
                    <h2 class="text-light">@yield('title')</h2>  <!-- Menampilkan judul halaman yang akan diatur di section 'title' -->
                  </div>
                  <div class="card-body">
                    @yield('content')  <!-- Menampilkan konten dinamis dari halaman yang menggunakan layout ini -->
                  </div>
                </div>
              </div>
              <!-- [ Sample Page ] selesai -->
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ms-1"></i></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include('layouts.footer')
  </body>
</html>