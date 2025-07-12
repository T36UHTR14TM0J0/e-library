@extends('layouts.app')
@section('title', 'Daftar Peraturan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('peraturan.create') }}" class="btn btn-primary">
            Tambah Peraturan
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h2 class="h5 mb-0">Peraturan Umum</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($peraturanUmum as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">{{ $item->urut }}.</span> {{ $item->text }}
                            </div>
                             <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('peraturan.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" 
                                    data-bs-toggle="tooltip" title="Edit">
                                    Edit
                                </a>
                                <button class="btn btn-danger btn-sm text-white" onclick="confirmDelete('{{ $item->id }}')"
                                    data-bs-toggle="tooltip" title="Hapus">
                                    Hapus
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('peraturan.destroy', $item->id) }}" 
                                        method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0">Peraturan Ruang Baca</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($peraturanRuangBaca as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">{{ $item->urut }}.</span> {{ $item->text }}
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('peraturan.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" 
                                    data-bs-toggle="tooltip" title="Edit">
                                    Edit
                                </a>
                                <button class="btn btn-danger btn-sm text-white" onclick="confirmDelete('{{ $item->id }}')"
                                    data-bs-toggle="tooltip" title="Hapus">
                                    Hapus
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('peraturan.destroy', $item->id) }}" 
                                        method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection