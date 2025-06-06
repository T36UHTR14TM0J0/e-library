<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <h4 class="navbar-brand" href="#">
      <a class="navbar-brand brand-logo me-5" href="#">E-library</a>
      <a class="navbar-brand brand-logo-mini" href="#">Elib</a>
    </h4>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
          <div class="d-flex align-items-center">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="rounded-circle" width="40" height="40">
            @else
                <img src="{{ asset('assets/images/profile_default.png') }}" class="rounded-circle" width="40" height="40">
            @endif
            <span class="ms-2 d-none d-md-block">{{ Auth::user()->username }}</span>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="{{ route('profile.show') }}">
            <i class="ti-user text-primary me-2"></i> Profil
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="ti-power-off text-primary me-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
  </div>
</nav>