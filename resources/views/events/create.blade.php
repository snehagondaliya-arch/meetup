@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@section('content')
    <div class="container mt-4">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Create Event</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('organization.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">

                    {{-- Category --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Category</label>

                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                >

                            <option value="">Select Category</option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Organization --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Organization</label>

                        <select name="group_id"
                                class="form-select @error('group_id') is-invalid @enderror"
                                >

                            <option value="">Select Organization</option>

                            @foreach($organizations as $organization)

                                <option value="{{ $organization->id }}"
                                    {{ old('group_id') == $organization->id ? 'selected' : '' }}>

                                    {{ $organization->organization_name }}

                                </option>

                            @endforeach

                        </select>

                        @error('group_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Title</label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Start Time</label>

                        <input type="datetime-local"
                               name="start_time"
                               value="{{ old('start_time') }}"
                               class="form-control @error('start_time') is-invalid @enderror">

                        @error('start_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- End Time --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">End Time</label>

                        <input type="datetime-local"
                               name="end_time"
                               value="{{ old('end_time') }}"
                               class="form-control @error('end_time') is-invalid @enderror">

                        @error('end_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Timezone --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Timezone</label>

                        <select name="timezone"
                                class="form-select @error('timezone') is-invalid @enderror">

                            <option value="">Select Timezone</option>

                            <option value="Asia/Kolkata"
                                {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>
                                Asia/Kolkata
                            </option>

                            <option value="UTC"
                                {{ old('timezone') == 'UTC' ? 'selected' : '' }}>
                                UTC
                            </option>

                            <option value="America/New_York"
                                {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>
                                America/New_York
                            </option>

                            <option value="Europe/London"
                                {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>
                                Europe/London
                            </option>

                        </select>

                        @error('timezone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Venue Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Venue Name</label>

                        <input type="text"
                               name="venue_name"
                               value="{{ old('venue_name') }}"
                               class="form-control @error('venue_name') is-invalid @enderror">

                        @error('venue_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Full Address --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">Full Address</label>

                        <textarea name="full_address"
                                  rows="3"
                                  class="form-control @error('full_address') is-invalid @enderror">{{ old('full_address') }}</textarea>

                        @error('full_address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Latitude --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Latitude</label>

                        <input type="number"
                               step="any"
                               name="latitude"
                               value="{{ old('latitude') }}"
                               class="form-control @error('latitude') is-invalid @enderror"
                               placeholder="19.0760">

                        @error('latitude')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Longitude --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Longitude</label>

                        <input type="number"
                               step="any"
                               name="longitude"
                               value="{{ old('longitude') }}"
                               class="form-control @error('longitude') is-invalid @enderror"
                               placeholder="72.8777">

                        @error('longitude')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Event Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Event Image</label>

                        <input type="file"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">

                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Group Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Group Image</label>

                        <input type="file"
                               name="group_image"
                               class="form-control @error('group_image') is-invalid @enderror"
                               accept="image/*">

                        @error('group_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">Description</label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Host Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Host Name</label>

                        <input type="text"
                               name="host_name"
                               value="{{ old('host_name') }}"
                               class="form-control @error('host_name') is-invalid @enderror">

                        @error('host_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Host Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Host Image</label>

                        <input type="file"
                               name="host_image"
                               class="form-control @error('host_image') is-invalid @enderror"
                               accept="image/*">

                        @error('host_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Price</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="price"
                               value="{{ old('price') }}"
                               class="form-control @error('price') is-invalid @enderror">

                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Is Online --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label d-block">Is Online?</label>

                        <div class="form-check form-check-inline">

                            <input class="form-check-input"
                                   type="radio"
                                   name="is_online"
                                   value="1"
                                   {{ old('is_online') == '1' ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Yes
                            </label>
                        </div>

                        <div class="form-check form-check-inline">

                            <input class="form-check-input"
                                   type="radio"
                                   name="is_online"
                                   value="0"
                                   {{ old('is_online', '0') == '0' ? 'checked' : '' }}>

                            <label class="form-check-label">
                                No
                            </label>
                        </div>

                        @error('is_online')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Save Event
                </button>
            </form>
        </div>
    </div>
</div>
@endsection