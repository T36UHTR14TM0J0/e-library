<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link {{ request()->is('Katalog/KatalogBuku*') || request()->is('Katalog/KatalogEbook*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#katalog" 
         aria-expanded="{{ request()->is('Katalog/KatalogBuku*') || request()->is('Katalog/KatalogEbook*') ? 'true' : 'false' }}" 
         aria-controls="katalog">
        <i class="fa fa-book menu-icon"></i>
        <span class="menu-title">Katalog</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('Katalog/KatalogBuku*') || request()->is('Katalog/KatalogEbook*') ? 'show' : '' }}" id="katalog">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('Katalog/KatalogBuku*') ? 'active' : '' }}" href="{{ route('KatalogBuku.index') }}">Buku</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('Katalog/KatalogEbook*') ? 'active' : '' }}" href="{{ route('KatalogEbook.index') }}">Ebook</a>
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
      <a class="nav-link {{ request()->is('histori*') ? 'active' : '' }}" href="{{ route('histori.index') }}">
          <i class="icon-clock menu-icon"></i>
          <span class="menu-title">Histori</span>
      </a>
  </li>
    
    @if (auth()->user()->isAdmin())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#laporan" 
         aria-expanded="{{ request()->is('laporan*') ? 'true' : 'false' }}" 
         aria-controls="laporan">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Laporan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('laporan/anggota*') || request()->is('laporan/Lap_buku*') || request()->is('laporan/Lap_ebook*') ||  request()->is('laporan/Lap_peminjaman*') ? 'show' : '' }}" id="laporan">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('laporan/Lap_peminjaman*') ? 'active' : '' }}" href="{{ route('laporan.peminjaman') }}">Peminjaman</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('laporan/anggota*') ? 'active' : '' }}" href="{{ route('laporan.anggota.index') }}">Anggota</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('laporan/Lap_buku*') ? 'active' : '' }}" href="{{ route('laporan.buku.index') }}">Buku</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('laporan/Lap_ebook*') ? 'active' : '' }}" href="{{ route('laporan.ebook.index') }}">Ebook</a>
          </li>
        </ul>
      </div>
    </li>
    @endif
        
    @if (auth()->user()->isAdmin() || auth()->user()->isDosen() || auth()->user()->isMahasiswa())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('MasterData/kategori*') || request()->is('MasterData/buku*') || request()->is('MasterData/ebook*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#master-buku" 
         aria-expanded="{{ request()->is('MasterData/kategori*') || request()->is('MasterData/buku*') || request()->is('MasterData/ebook*') ? 'true' : 'false' }}" 
         aria-controls="master-buku">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('MasterData/kategori*') || request()->is('MasterData/buku*') || request()->is('MasterData/ebook*') ? 'show' : '' }}" id="master-buku">
        <ul class="nav flex-column sub-menu">
          @if (auth()->user()->isAdmin())
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('MasterData/kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('MasterData/buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Buku</a>
          </li>
          @endif
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('MasterData/ebook*') ? 'active' : '' }}" href="{{ route('ebook.index') }}">Ebook</a>
          </li>
        </ul>
      </div>
    </li>
    @endif

    @if (auth()->user()->isAdmin())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('pengaturan*') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#pengaturan" 
         aria-expanded="{{ request()->is('pengaturan*') ? 'true' : 'false' }}" 
         aria-controls="pengaturan">
        <i class="icon-cog menu-icon"></i>
        <span class="menu-title">Pengaturan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->is('pengaturan/layanan*') || request()->is('pengaturan/prosedur*') || request()->is('pengaturan/jam_layanan*') || request()->is('pengaturan/prodi*') || request()->is('pengaturan/users*') ||  request()->is('pengaturan/penerbit*') ? 'show' : '' }}" id="pengaturan">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/penerbit*') ? 'active' : '' }}" href="{{ route('penerbit.index') }}">Penerbit</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/prodi*') ? 'active' : '' }}" href="{{ route('prodi.index') }}">Program Studi</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/layanan*') ? 'active' : '' }}" href="{{ route('layanan.index') }}">Layanan</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/jam_layanan*') ? 'active' : '' }}" href="{{ route('jam_layanan.index') }}">Jam Layanan</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/prosedur*') ? 'active' : '' }}" href="{{ route('prosedur.index') }}">Prosedur</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ request()->is('pengaturan/logs*') ? 'active' : '' }}" href="{{ route('logs.index') }}">Logs Activity</a>
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