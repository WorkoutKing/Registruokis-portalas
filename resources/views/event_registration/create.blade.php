@extends('main')

@section('content')
    <div class="container">
        <!-- Display success, info, or warning messages -->
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

        <h1>Registracija į renginį</h1>

        @if ($event->registrations()->count() > $event->max_participants)
            <i class="errors_log">Dalyvių sąrašas pilnas busite įtraukti i laukimo sąrašą.</i>
        @else
        @endif

        @if(auth()->check()) {{-- Check if user is authenticated --}}
            {{-- Registration Form --}}
            <form action="{{ route('event_registration.store', ['event' => $event->id]) }}" method="POST" class="form_event_reg">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="form-floating mb-3">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ auth()->user()->name }}" required>
                    <label for="name">{{ __('Vardas') }}</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ auth()->user()->surname }}" required>
                    <label for="surname">{{ __('Pavardė') }}</label>
                    @error('surname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label for="email">{{ __('Email Address') }}</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ auth()->user()->phone }}" required>
                    <label for="phone">{{ __('Telefono numeris') }}</label>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @foreach ($event->dynamicFields as $field)
                    @if (isset($field->title))
                        @if ($field->type === 'dropdown')
                            <div class="form-floating mb-3">
                                <select style="height:60px;" class="form-control" id="{{ $field->title }}" name="dynamic_fields[{{ $field->title }}]">
                                    @foreach (json_decode($field->options) as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                <label for="{{ $field->title }}">{{ $field->title }}</label>
                            </div>
                        @elseif ($field->type === 'checkbox')
                            <div class="mb-3">
                                <label style="color:#495057;font-size:14px;">{{ $field->title }}</label>
                                @foreach (json_decode($field->options) as $option)
                                    <div class="form-check" style="font-size:14px;color:#495057;">
                                        <input type="checkbox" class="form-check-input" id="{{ $option }}" name="dynamic_fields[{{ $field->title }}][]" value="{{ $option }}">
                                        <label class="form-check-label" for="{{ $option }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endforeach

                <div class="form-floating mb-3">
                    <textarea class="form-control" id="comments" name="comments"></textarea>
                    <label for="comments">{{ __('Komentaras') }}</label>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Siųsti') }}</button>
            </form>
        @else
            {{-- Message for non-authenticated users --}}
            <div class="alert alert-info">
                Prašome <a href="{{ route('login') }}">prisijungti</a> arba <a href="{{ route('register') }}">užsiregistruoti</a> mūsų svetainėje kad galėtumėte užsiregistruoti šiame renginyje.
            </div>
        @endif
    </div>
@endsection
