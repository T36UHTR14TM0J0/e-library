@extends('landing.app')

@section('title', 'Galeri Perpustakaan')

@section('content')
<section class="py-5 bg-light mt-5">
    <div class="container py-5">
        <div class="row align-items-center mb-4 mb-md-5">
            <div class="col-md-6">
                <h2 class="section-title mb-0">Galeri Perpustakaan</h2>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="btn-group" role="group">
                     <a href="{{ route('peraturan') }}" class="btn btn-outline-primary btn-modern px-4 py-2 me-2">
                        <i class="fas fa-clipboard-list me-2"></i>Peraturan
                    </a>
                     <a href="{{ route('informasi') }}" class="btn btn-primary btn-modern px-4 py-2">
                        <i class="fas fa-info-circle me-2"></i>Informasi
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Gallery Section -->
        <div class="gallery-container">
            <div class="row flex-nowrap flex-md-wrap g-4 pb-3 pb-md-0 overflow-auto">
                @foreach($galeris as $gallery)
                <div class="col-8 col-sm-6 col-md-4 col-lg-2 flex-shrink-0 flex-md-shrink-1">
                    <div class="card gallery-card" data-bs-toggle="modal" data-bs-target="#imageModal" 
                         data-img="{{ asset('storage/' . $gallery->foto) }}"
                         data-title="{{ $gallery->judul }}"
                         data-desc="{{ $gallery->deskripsi }}">
                        <img src="{{ asset('storage/' . $gallery->foto) }}" class="card-img-top" alt="{{ $gallery->judul }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $gallery->judul }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            @if($galeris->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $galeris->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif

                            @foreach(range(1, $galeris->lastPage()) as $i)
                                @if($i >= $galeris->currentPage() - 2 && $i <= $galeris->currentPage() + 2)
                                    <li class="page-item {{ ($galeris->currentPage() == $i) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $galeris->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if($galeris->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $galeris->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="row align-items-center mt-5">
            <div class="col-md-6">
                <h2 class="section-title mb-0">Aktivitas Pengunjung</h2>
            </div>
        </div>
        
        <!-- Activities Section -->
        <div class="gallery-container mt-5">
            <div class="row flex-nowrap flex-md-wrap g-4 pb-3 pb-md-0 overflow-auto">
                @foreach($aktivitass as $aktivitsa)
                <div class="col-8 col-sm-6 col-md-4 col-lg-2 flex-shrink-0 flex-md-shrink-1">
                    <div class="card gallery-card" data-bs-toggle="modal" data-bs-target="#imageModal"
                         data-img="{{ asset('storage/' . $aktivitsa->foto) }}"
                         data-title="{{ $aktivitsa->judul }}"
                         data-desc="{{ $aktivitsa->deskripsi }}">
                        <img src="{{ asset('storage/' . $aktivitsa->foto) }}" class="card-img-top" alt="{{ $aktivitsa->judul }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $aktivitsa->judul }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            @if($aktivitass->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $aktivitass->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif

                            @foreach(range(1, $aktivitass->lastPage()) as $i)
                                @if($i >= $aktivitass->currentPage() - 2 && $i <= $aktivitass->currentPage() + 2)
                                    <li class="page-item {{ ($aktivitass->currentPage() == $i) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $aktivitass->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if($aktivitass->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $aktivitass->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Judul Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid rounded mb-3" alt="Zoomed Image">
                <p id="modalDescription" class="text-muted">Deskripsi gambar akan muncul di sini</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<style>
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        position: relative;
        display: inline-block;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        background: #3498db;
    }
    
    .card-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
        text-align: center;
    }
    
    .gallery-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }
    
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-card img {
        height: 150px;
        object-fit: cover;
    }
    
    .btn-modern {
        border-radius: 8px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border-width: 2px;
    }
    
    .btn-outline-primary {
        color: #3498db;
        border-color: #3498db;
    }
    
    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }
    
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }
    
    /* Pagination Styles */
    .pagination {
        margin-top: 20px;
    }
    
    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
    }
    
    .page-link {
        color: #3498db;
    }
    
    /* Modal Styles */
    .modal-img-container {
        max-height: 70vh;
        overflow: hidden;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 767.98px) {
        .gallery-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .gallery-container .row {
            flex-wrap: nowrap;
            padding-bottom: 15px;
        }
        
        .gallery-container .col-8 {
            width: 220px;
        }
        
        /* Hide scrollbar but keep functionality */
        .gallery-container::-webkit-scrollbar {
            display: none;
        }
        
        /* Modal adjustments for mobile */
        .modal-dialog {
            margin: 10px;
        }
        
        /* Pagination adjustments for mobile */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-item {
            margin: 2px;
        }
    }
</style>
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var imageModal = document.getElementById('imageModal');
        
        imageModal.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            var card = event.relatedTarget;
            
            // Extract info from data-* attributes
            var imageUrl = card.getAttribute('data-img');
            var title = card.getAttribute('data-title');
            var description = card.getAttribute('data-desc');
            
            console.log(imageUrl);
            // Update the modal's content
            var modalTitle = imageModal.querySelector('.modal-title');
            var modalImage = imageModal.querySelector('#modalImage');
            var modalDescription = imageModal.querySelector('#modalDescription');
            
            modalTitle.textContent = title;
            modalImage.src = imageUrl;
            modalImage.alt = title;
            modalDescription.textContent = description;
        });
    });
</script>
@endpush