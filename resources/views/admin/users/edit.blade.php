@extends('main')

@section('content')
    <div class="container">
        {{-- Rodyti sėkmės, informacijos ir įspėjimo pranešimus --}}
        @if (session('info'))
            <div class="alert alert-success">
                {{ session('info') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-success">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="page-title">
            <h1>Redaguoti vartotoją</h1>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" id="name" name="name" required class="form-control shadow-sm" value="{{ $user->name }}">
                <label for="name">Vardas</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="surname" name="surname" required class="form-control shadow-sm" value="{{ $user->surname }}">
                <label for="surname">Pavardė</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" id="email" name="email" required class="form-control shadow-sm" value="{{ $user->email }}">
                <label for="email">El. paštas</label>
            </div>
            <div class="form-floating mb-3">
                <select id="role" name="role" required class="form-control shadow-sm">
                    <option value="">Pasirinkite Rolę</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>{{ $role->role }}</option>
                    @endforeach
                </select>
                <label for="role">Pasirinkite Rolę</label>
            </div>
            <button type="submit" class="btn btn-primary">Atnaujinti vartotoją</button>
        </form>
    </div>
@endsection
