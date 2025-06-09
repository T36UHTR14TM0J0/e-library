<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link {{ request()->is('Katalog*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#katalog" 
         aria-expanded="{{ request()->is('Katalog*') ? 'true' : 'false' }}" 
         aria-controls="katalog">
        <i class="fa fa-book menu-icon"></i>
        <span class="menu-title">Katalog</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('katalog*') ? 'show' : '' }}" id="katalog">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('KatalogBuku*') ? 'active' : '' }}" href="{{ route('KatalogBuku.index') }}">Buku</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('KatalogEbook*') ? 'active' : '' }}" href="{{ route('KatalogEbook.index') }}">Ebook</a>
          </li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link {{ request()->is('peminjaman*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Peminjaman</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" href="#">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Laporan</span>
      </a>
    </li>
    
    @if (auth()->user()->isAdmin() || auth()->user()->isDosen())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('kategori*') || request()->is('buku*') || request()->is('ebook*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#master-buku" 
         aria-expanded="{{ request()->is('kategori*') || request()->is('buku*') || request()->is('ebook*') ? 'true' : 'false' }}" 
         aria-controls="master-buku">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('kategori*') || request()->is('buku*') || request()->is('ebook*') ? 'show' : '' }}" id="master-buku">
        <ul class="nav flex-column sub-menu">
          @if (auth()->user()->isAdmin())
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Buku</a>
          </li>
          @endif
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('ebook*') ? 'active' : '' }}" href="{{ route('ebook.index') }}">Ebook</a>
          </li>
        </ul>
      </div>
    </li>
    @endif

    @if (auth()->user()->isAdmin())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('prodi*') || request()->is('users*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#pengaturan" 
         aria-expanded="{{ request()->is('prodi*') || request()->is('users*') ? 'true' : 'false' }}" 
         aria-controls="pengaturan">
        <i class="icon-cog menu-icon"></i>
        <span class="menu-title">Pengaturan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('prodi*') || request()->is('users*') ? 'show' : '' }}" id="pengaturan">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('prodi*') ? 'active' : '' }}" href="{{ route('prodi.index') }}">Prodi</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a>
          </li>
        </ul>
      </div>
    </li>
    @endif
  </ul>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Menangani collapse sidebar
  const navLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
  
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      const target = this.getAttribute('href');
      const collapseElement = document.querySelector(target);
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      // Jika collapse sedang terbuka, tutup yang lain
      if (!isExpanded) {
        document.querySelectorAll('.collapse.show').forEach(openCollapse => {
          if (openCollapse.id !== target.substring(1)) {
            openCollapse.classList.remove('show');
            const parentLink = document.querySelector(`[href="#${openCollapse.id}"]`);
            if (parentLink) {
              parentLink.setAttribute('aria-expanded', 'false');
            }
          }
        });
      }
    });
  });
  
  // Set active parent jika child active
  document.querySelectorAll('.sub-menu .nav-link.active').forEach(activeLink => {
    const parentCollapse = activeLink.closest('.collapse');
    if (parentCollapse) {
      parentCollapse.classList.add('show');
      const parentLink = document.querySelector(`[href="#${parentCollapse.id}"]`);
      if (parentLink) {
        parentLink.classList.add('active');
        parentLink.setAttribute('aria-expanded', 'true');
      }
    }
  });
});
</script>