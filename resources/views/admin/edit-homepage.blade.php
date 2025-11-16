@extends('layouts.admin')

@section('title', 'Edit Homepage')

@section('content')
    {{-- Konten --}}
    <div class="d-flex flex-column gap-4">

        {{-- Hero Section Form --}}
        <div class="card" id="hero">
            <div class="card-body">
                <h5 class="card-title-custom">Hero Banner</h5>
                <p class="text-muted mb-4 small">Headline, subheadline, dan gambar utama di halaman depan.</p>
                <form action="{{ route('admin.masterdata.homepage.hero') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="hero_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="hero_title" name="title" value="{{ old('title', $hero->title) }}">
                    </div>
                    <div class="mb-3">
                        <label for="hero_subtitle" class="form-label">Subtitle</label>
                        <textarea class="form-control" id="hero_subtitle" name="subtitle" rows="3">{{ old('subtitle', $hero->subtitle) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="hero_image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="hero_image" name="image" value="{{ old('image', $hero->image) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Hero</button>
                </form>
            </div>
        </div>

        {{-- Stats Section Form --}}
        <div class="card" id="stats">
            <div class="card-body">
                <h5 class="card-title-custom">Statistics</h5>
                <p class="text-muted mb-4 small">4 poin statistik (misal: Member Aktif, Tahun Berdiri).</p>
                <form action="{{ route('admin.masterdata.homepage.stats') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @foreach ($stats as $index => $stat)
                        <div class="row mb-3">
                            <div class="col">
                                <label for="stat_value_{{ $index }}" class="form-label">Value</label>
                                <input type="text" class="form-control" id="stat_value_{{ $index }}" name="stats[{{ $index }}][value]" value="{{ old('stats.'.$index.'.value', $stat->value) }}">
                            </div>
                            <div class="col">
                                <label for="stat_label_{{ $index }}" class="form-label">Label</label>
                                <input type="text" class="form-control" id="stat_label_{{ $index }}" name="stats[{{ $index }}][label]" value="{{ old('stats.'.$index.'.label', $stat->label) }}">
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Update Stats</button>
                </form>
            </div>
        </div>

        {{-- Benefits Section Form --}}
        <div class="card" id="benefits">
            <div class="card-body">
                <h5 class="card-title-custom">Features & Benefits</h5>
                <p class="text-muted mb-4 small">4 poin keunggulan (misal: Expert Trainers, Modern Equipment).</p>
                <form action="{{ route('admin.masterdata.homepage.benefits') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @foreach ($benefits as $index => $benefit)
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="benefit_icon_{{ $index }}" class="form-label">Icon</label>
                                <input type="text" class="form-control" id="benefit_icon_{{ $index }}" name="benefits[{{ $index }}][icon]" value="{{ old('benefits.'.$index.'.icon', $benefit->icon) }}">
                            </div>
                            <div class="col-md-5">
                                <label for="benefit_title_{{ $index }}" class="form-label">Title</label>
                                <input type="text" class="form-control" id="benefit_title_{{ $index }}" name="benefits[{{ $index }}][title]" value="{{ old('benefits.'.$index.'.title', $benefit->title) }}">
                            </div>
                            <div class="col-md-5">
                                <label for="benefit_description_{{ $index }}" class="form-label">Description</label>
                                <input type="text" class="form-control" id="benefit_description_{{ $index }}" name="benefits[{{ $index }}][description]" value="{{ old('benefits.'.$index.'.description', $benefit->description) }}">
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Update Benefits</button>
                </form>
            </div>
        </div>

        {{-- Testimonials Section Form --}}
        <div class="card" id="testimonials">
            <div class="card-body">
                <h5 class="card-title-custom">Testimonials</h5>
                <p class="text-muted mb-4 small">3 testimoni dari member.</p>
                <form action="{{ route('admin.masterdata.homepage.testimonials') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @foreach ($testimonials as $index => $testimonial)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="testimonial_name_{{ $index }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="testimonial_name_{{ $index }}" name="testimonials[{{ $index }}][name]" value="{{ old('testimonials.'.$index.'.name', $testimonial->name) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="testimonial_role_{{ $index }}" class="form-label">Role</label>
                                <input type="text" class="form-control" id="testimonial_role_{{ $index }}" name="testimonials[{{ $index }}][role]" value="{{ old('testimonials.'.$index.'.role', $testimonial->role) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="testimonial_rating_{{ $index }}" class="form-label">Rating</label>
                                <input type="number" class="form-control" id="testimonial_rating_{{ $index }}" name="testimonials[{{ $index }}][rating]" min="1" max="5" value="{{ old('testimonials.'.$index.'.rating', $testimonial->rating) }}">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="testimonial_text_{{ $index }}" class="form-label">Text</label>
                                <textarea class="form-control" id="testimonial_text_{{ $index }}" name="testimonials[{{ $index }}][text]" rows="3">{{ old('testimonials.'.$index.'.text', $testimonial->text) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Update Testimonials</button>
                </form>
            </div>
        </div>

    </div>
@endsection
