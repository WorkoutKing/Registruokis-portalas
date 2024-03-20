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
                    <div class="form-group">
                        <label for="name">Vardas:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="surname">Pavardė:</label>
                        <input type="text" class="form-control" id="surname" name="surname" value="{{ auth()->user()->surname }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">El.paštas:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefono numeris:</label>
                        <input type="phone" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
                    </div>
                    @foreach ($event->dynamicFields as $field)
                        @if (isset($field->title))
                            @if ($field->type === 'dropdown')
                                <div class="form-group">
                                    <label for="{{ $field->title }}">{{ $field->title }}:</label>
                                    <select class="form-control" id="{{ $field->title }}" name="dynamic_fields[{{ $field->title }}]">
                                        @foreach (json_decode($field->options) as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif ($field->type === 'checkbox')
                                <div class="form-group">
                                    <label>{{ $field->title }}:</label>
                                    @foreach (json_decode($field->options) as $option)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="{{ $option }}" name="dynamic_fields[{{ $field->title }}][]" value="{{ $option }}">
                                            <label class="form-check-label" for="{{ $option }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    @endforeach

                    <div class="form-group">
                        <label for="comments">Komentaras:</label>
                        <textarea class="form-control" id="comments" name="comments"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Siųsti</button>
                </form>
            @else
                {{-- Message for non-authenticated users --}}
                <div class="alert alert-info">
                    Prašome <a href="{{ route('login') }}">prisijungti</a> arba <a href="{{ route('register') }}">užsiregistruoti</a> mūsų svetainėje kad galėtumėte užsiregistruoti šiame renginyje.
                </div>
        @endif
    </div>
@endsection
