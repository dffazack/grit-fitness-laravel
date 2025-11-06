@extends('layouts.admin')

@section('title', 'Data Master - Homepage')

@section('content')

    {{-- Memanggil komponen header dan tab navigasi --}}
    @include('admin.components.datamaster-tabs')

    {{-- Konten --}}
    <div class="d-flex flex-column gap-4">

        <!-- Hero Banner -->
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title-custom">Hero Banner</h5>
                    <p class="text-muted mb-0 small">Headline, subheadline, dan gambar utama di halaman depan.</p>
                </div>
                <a href="{{ route('admin.homepage.edit') }}#hero" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Banner
                </a>
            </div>
        </div>

        <!-- Features & Benefits -->
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title-custom">Features & Benefits</h5>
                    <p class="text-muted mb-0 small">4 poin keunggulan (misal: Expert Trainers, Modern Equipment).</p>
                </div>
                <a href="{{ route('admin.homepage.edit') }}#benefits" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Features
                </a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title-custom">Statistics</h5>
                    <p class="text-muted mb-0 small">4 poin statistik (misal: Member Aktif, Tahun Berdiri).</p>
                </div>
                <a href="{{ route('admin.homepage.edit') }}#stats" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Stats
                </a>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title-custom">Testimonials</h5>
                    <p class="text-muted mb-0 small">3 testimoni dari member.</p>
                </div>
                <a href="{{ route('admin.homepage.edit') }}#testimonials" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Testimonials
                </a>
            </div>
        </div>
        
        <!-- (Opsional) CTA - Desain Anda tidak ada ini, tapi file form Anda ada -->

    </div>

@endsection
