@extends('layouts.app')
@section('title', 'Data Ebook')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Data Ebook</h5>
                        <a href="{{ route('ebook.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Ebook
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('ebook.index') }}">
                        <div class="row g-3">
                             <div class="col-md-8">
                                <label for="search" class="form-label">Cari E-Book</label>
                                    <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                           placeholder="Judul, penulis,kategori,prodi, atau penerbit..." 
                                           value="{{ request('search') }}">
                                <small class="text-muted">Anda bisa mencari berdasarkan judul, penulis, kategori, prodi, atau penerbit ebook</small>
                            </div>

                            <div class="col-md-4">
                                <label for="izin_unduh" class="form-label">Status Unduh</label>
                                <select name="izin_unduh" id="izin_unduh" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('izin_unduh') == '1' ? 'selected' : '' }}>Diizinkan</option>
                                    <option value="0" {{ request('izin_unduh') == '0' ? 'selected' : '' }}>Tidak Diizinkan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('search') || request()->has('izin_unduh'))
                            <a href="{{ route('ebook.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                            @endif
                            <button type="submit" class="btn btn-sm btn-dark">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3 shadow-sm">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-book me-2"></i> Daftar Ebook</h5>
                        <button id="export-pdf" class="btn btn-sm btn-danger text-white">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="ebooks-table">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white text-center bg-primary" style="width: 5%">No</th>
                                    <th class="text-white text-center bg-primary" style="width: 20%">Judul</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Penerbit</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Kategori</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Prodi</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Uploader</th>
                                    <th class="text-white text-center bg-primary" style="width: 10%">Izin Unduh</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= 1;?>
                                @forelse ($ebooks as $ebook)
                                <tr>
                                    <td class="text-center"><?= $no++;?></td>
                                    <td>{{ $ebook->judul }}</td>
                                    <td>{{ $ebook->penerbit->nama }}</td>
                                    <td>{{ $ebook->kategori->nama ?? '-' }}</td>
                                    <td>{{ $ebook->prodi->nama ?? '-' }}</td>
                                    <td>{{ $ebook->pengunggah->nama_lengkap ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($ebook->izin_unduh)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('ebook.show',$ebook->id) }}" class="btn btn-sm btn-info text-white" 
                                               data-bs-toggle="tooltip" title="Lihat">
                                                Detail
                                            </a>
                                            <a href="{{ route('ebook.edit',$ebook->id) }}" class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger text-white" title="Hapus" 
                                                    onclick="confirmDelete('{{ $ebook->id }}')" data-bs-toggle="tooltip">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $ebook->id }}" action="{{ route('ebook.destroy', $ebook->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari {{ $ebooks->total() }} entri
                        </div>
                        <div>
                            {{ $ebooks->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Add jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>    
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // PDF Export functionality
        document.getElementById('export-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Title
            doc.setFontSize(18);
            doc.text('Daftar Ebook', 105, 15, { align: 'center' });
            
            // Date
            doc.setFontSize(10);
            const now = new Date();
            doc.text(`Dicetak pada: ${now.toLocaleDateString()} ${now.toLocaleTimeString()}`, 105, 22, { align: 'center' });
            
            // Table
            doc.autoTable({
                html: '#ebooks-table',
                startY: 30,
                styles: {
                    cellPadding: 3,
                    fontSize: 10,
                    valign: 'middle',
                    halign: 'center'
                },
                columnStyles: {
                    0: { halign: 'center' }, // No
                    1: { halign: 'left' },   // Judul
                    2: { halign: 'left' },   // Penerbit
                    3: { halign: 'left' },   // Kategori
                    4: { halign: 'left' },   // Prodi
                    5: { halign: 'left' },   // Uploader
                    6: { halign: 'center' }, // Izin Unduh
                    7: { halign: 'center' }  // Aksi (will be removed)
                },
                didParseCell: function(data) {
                    // Remove the "Aksi" column (column index 7)
                    if (data.column.index === 7) {
                        data.cell.text = '';
                    }
                    
                    // Convert badge to text for Izin Unduh column (column index 6)
                    if (data.column.index === 6) {
                        if (data.cell.text.includes('Ya')) {
                            data.cell.text = 'Ya';
                        } else if (data.cell.text.includes('Tidak')) {
                            data.cell.text = 'Tidak';
                        }
                    }
                },
                margin: { top: 30 }
            });
            
            // Save the PDF
            doc.save(`daftar-ebook_${now.getTime()}.pdf`);
        });
    });
</script>
@endpush