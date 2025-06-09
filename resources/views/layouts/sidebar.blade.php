<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#katalog" aria-expanded="false" aria-controls="katalog">
        <i class="fa fa-book menu-icon"></i>
        <span class="menu-title">Katalog</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="katalog">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('KatalogBuku.index') }}">Buku</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('KatalogEbook.index') }}">Ebook</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Peminjaman</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Laporan</span>
      </a>
    </li>
    
    @if (auth()->user()->isAdmin() || auth()->user()->isDosen())
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#master-buku" aria-expanded="false" aria-controls="master-buku">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="master-buku">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('buku.index') }}">Buku</a></li>
          {{-- @if (auth()->user()->isDosen()) --}}
          <li class="nav-item"> <a class="nav-link" href="{{ route('ebook.index') }}">Ebook</a></li>
          {{-- @endif --}}
        </ul>
      </div>
    </li>
    @endif

    @if (auth()->user()->isAdmin())
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#pengaturan" aria-expanded="false" aria-controls="pengaturan">
        <i class="icon-cog menu-icon"></i>
        <span class="menu-title">Pengaturan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="pengaturan">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('prodi.index') }}">Prodi</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
        </ul>
      </div>
    </li>
    @endif
  </ul>
</nav>
