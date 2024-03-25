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
            <h1>Redaguoti profilį</h1>
        </div>

        {{-- Profilio atnaujinimo forma --}}
        <form method="POST" action="{{ route('my-profile.update', ['user' => $user->id]) }}">
            @csrf
            @method('PATCH')

            {{-- Vardo laukas --}}
            <div class="form-floating mb-3">
                <input type="text" id="name" name="name" required class="form-control shadow-sm" value="{{ $user->name }}">
                <label for="name">Vardas</label>
            </div>

            {{-- Pavardės laukas --}}
            <div class="form-floating mb-3">
                <input type="text" id="surname" name="surname" required class="form-control shadow-sm" value="{{ $user->surname }}">
                <label for="surname">Pavardė</label>
            </div>

            {{-- El. pašto laukas --}}
            <div class="form-floating mb-3">
                <input type="email" id="email" name="email" required class="form-control shadow-sm" value="{{ $user->email }}">
                <label for="email">El. paštas</label>
            </div>

            {{-- Telefono numerio laukas --}}
            <div class="form-floating mb-3">
                <input type="tel" id="phone_number" name="phone_number" class="form-control shadow-sm" value="{{ $user->phone }}">
                <label for="phone_number">Telefono numeris</label>
            </div>

            {{-- Slaptažodžio laukas --}}
            <div class="form-floating mb-3">
                <input type="password" id="password" name="password" autocomplete="new-password" class="form-control shadow-sm">
                <label for="password">Slaptažodis</label>
            </div>

            {{-- Slaptažodžio patvirtinimo laukas --}}
            <div class="form-floating mb-3">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control shadow-sm">
                <label for="password_confirmation">Patvirtinkite slaptažodį</label>
            </div>

            {{-- Pateikti mygtuką --}}
            <button type="submit" class="btn btn-primary">Atnaujinti profilį</button>
        </form>
    </div>

    {{-- JavaScript, skirtas patikrinti, ar slaptažodžiai sutampa --}}
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                var password = $('input[name="password"]').val();
                var confirmPassword = $('input[name="password_confirmation"]').val();

                if (password !== confirmPassword) {
                    event.preventDefault(); // Sustabdyti formos pateikimą
                    alert('Slaptažodis ir patvirtinti slaptažodis nesutampa.');
                }
            });
        });
    </script>
@endsection
