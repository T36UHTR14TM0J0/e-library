<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library | Perpustakaan Digital Modern</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            padding: 15px 0;
            transition: all 0.3s;
            background-color: rgba(26, 26, 46, 0.9) !important;
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            background-color: var(--dark-color) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .navbar-brand span {
            color: var(--accent-color);
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
            position: relative;
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            bottom: 0;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-link:hover:after {
            width: 100%;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(63, 55, 201, 0.9)), 
                        url('https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
            background-size: cover;
            min-height: 90vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }
        
        .hero-content {
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-light {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            font-weight: 700;
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: var(--accent-color);
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .prosedur-list {
            list-style-type: none;
            padding-left: 0;
        }
        
        .prosedur-list li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }
        
        .prosedur-list li:last-child {
            border-bottom: none;
        }
        
        .prosedur-number {
            background-color: var(--accent-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .schedule-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .schedule-table thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
        }
        
        .schedule-table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .service-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
            border-left: 4px solid var(--accent-color);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .service-icon {
            font-size: 1.8rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        
        .about-section {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.03), rgba(63, 55, 201, 0.03));
            position: relative;
        }
        
        .about-img {
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .footer-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background: var(--accent-color);
            bottom: -8px;
            left: 0;
            border-radius: 2px;
        }
        
        .footer-links {
            list-style: none;
            padding-left: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-3px);
        }
        
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">E<span>Library</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prosedur">Prosedur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jam-pelayanan">Jam Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light rounded-pill" href="#login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <h1 class="hero-title">Akses Pengetahuan Tanpa Batas</h1>
                        <p class="hero-subtitle">E-Library menyediakan koleksi digital terlengkap dengan akses 24 jam untuk mendukung pembelajaran dan penelitian Anda.</p>
                        <div class="d-flex gap-3">
                            <a href="#prosedur" class="btn btn-primary">Mulai Jelajahi</a>
                            <a href="#tentang" class="btn btn-outline-light">Tentang Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="feature-title">Koleksi Lengkap</h3>
                        <p>Lebih dari 100.000 judul buku digital dan 50.000 buku fisik dari berbagai disiplin ilmu.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">Akses 24/7</h3>
                        <p>Perpustakaan digital dapat diakses kapan saja, di mana saja melalui berbagai perangkat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Layanan Profesional</h3>
                        <p>Tim pustakawan siap membantu Anda dalam menemukan referensi yang dibutuhkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prosedur Kunjungan Section -->
    <section id="prosedur" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="section-title">Prosedur Kunjungan</h2>
                    <p class="mb-4">Ikuti langkah-langkah berikut untuk mengakses layanan perpustakaan kami:</p>
                    
                    <ul class="prosedur-list">
                        <li>
                            <div class="prosedur-number">1</div>
                            <div>Daftar sebagai anggota melalui website atau langsung di perpustakaan</div>
                        </li>
                        <li>
                            <div class="prosedur-number">2</div>
                            <div>Verifikasi data diri dan email Anda</div>
                        </li>
                        <li>
                            <div class="prosedur-number">3</div>
                            <div>Login menggunakan akun yang telah terdaftar</div>
                        </li>
                        <li>
                            <div class="prosedur-number">4</div>
                            <div>Untuk kunjungan fisik, bawa kartu anggota saat datang</div>
                        </li>
                        <li>
                            <div class="prosedur-number">5</div>
                            <div>Patuhi semua peraturan yang berlaku di perpustakaan</div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Prosedur Kunjungan" class="img-fluid rounded about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Jam Pelayanan Section -->
    <section id="jam-pelayanan" class="py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="section-title">Jam Pelayanan</h2>
                    <p class="lead">Berikut adalah jam operasional layanan perpustakaan kami</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="schedule-table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Buka</th>
                                    <th>Jam Tutup</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senin - Kamis</td>
                                    <td>08:00</td>
                                    <td>18:00</td>
                                    <td>Layanan penuh</td>
                                </tr>
                                <tr>
                                    <td>Jumat</td>
                                    <td>08:00</td>
                                    <td>17:00</td>
                                    <td>Istirahat 12:00-13:30</td>
                                </tr>
                                <tr>
                                    <td>Sabtu</td>
                                    <td>09:00</td>
                                    <td>15:00</td>
                                    <td>Layanan terbatas</td>
                                </tr>
                                <tr>
                                    <td>Minggu</td>
                                    <td>10:00</td>
                                    <td>14:00</td>
                                    <td>Layanan digital saja</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-muted">* Perpustakaan digital dapat diakses 24 jam melalui platform kami</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Sirkulasi Section -->
    <section id="layanan" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h2 class="section-title">Layanan Sirkulasi</h2>
                    <p class="lead">Berbagai layanan yang kami sediakan untuk kebutuhan literasi Anda</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Peminjaman Buku</h4>
                        <p>Maksimal 5 buku fisik untuk 14 hari dengan opsi perpanjangan 1x7 hari.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h4>E-Book</h4>
                        <p>Akses 30 hari untuk setiap judul e-book dengan batas 5 judul sekaligus.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h4>Perpanjangan</h4>
                        <p>Perpanjangan online sebelum masa pinjam berakhir jika tidak ada reservasi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Reservasi</h4>
                        <p>Booking buku yang sedang dipinjam oleh anggota lain.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="about-section py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Tentang Kami" class="img-fluid rounded about-img">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p class="mb-4">E-Library adalah perpustakaan digital modern yang didirikan pada tahun 2020 dengan misi untuk menyediakan akses pengetahuan yang mudah dan merata bagi semua kalangan.</p>
                    
                    <p>Kami memiliki koleksi lebih dari 100.000 judul buku digital dan 50.000 buku fisik dari berbagai disiplin ilmu. Tim kami terdiri dari profesional di bidang perpustakaan dan teknologi informasi yang siap membantu kebutuhan literasi Anda.</p>
                    
                    <div class="mt-4">
                        <h5 class="fw-bold">Visi Kami:</h5>
                        <p>Menjadi pusat pengetahuan terdepan yang mendukung pembelajaran sepanjang hayat dan penelitian inovatif.</p>
                        
                        <h5 class="fw-bold mt-3">Misi Kami:</h5>
                        <ul class="ps-3">
                            <li>Menyediakan akses pengetahuan yang mudah dan merata</li>
                            <li>Mendukung pendidikan dan penelitian berkualitas</li>
                            <li>Mengembangkan budaya literasi digital</li>
                            <li>Menjadi mitra pembelajaran sepanjang hayat</li>
                        </ul>
                    </div>
                    
                    <a href="#" class="btn btn-primary mt-3">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 class="footer-title">E<span>Library</span></h3>
                    <p class="mt-3">Perpustakaan digital modern dengan koleksi terlengkap dan layanan profesional untuk mendukung kebutuhan literasi Anda.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h3 class="footer-title">Tautan Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#prosedur">Prosedur</a></li>
                        <li><a href="#jam-pelayanan">Jam Layanan</a></li>
                        <li><a href="#layanan">Layanan</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h3 class="footer-title">Layanan</h3>
                    <ul class="footer-links">
                        <li><a href="#">Peminjaman Buku</a></li>
                        <li><a href="#">E-Book</a></li>
                        <li><a href="#">Perpanjangan Online</a></li>
                        <li><a href="#">Reservasi Buku</a></li>
                        <li><a href="#">Bantuan Pustakawan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h3 class="footer-title">Kontak</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Perpustakaan No. 123, Jakarta</li>
                        <li><i class="fas fa-phone-alt me-2"></i> (021) 1234-5678</li>
                        <li><i class="fas fa-envelope me-2"></i> info@elibrary.id</li>
                        <li><i class="fas fa-clock me-2"></i> Senin-Minggu: 08.00-18.00</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-5" style="background-color: rgba(255, 255, 255, 0.1);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 E-Library. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> by E-Library Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Back to top button
        const backToTopButton = document.querySelector('.back-to-top');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>