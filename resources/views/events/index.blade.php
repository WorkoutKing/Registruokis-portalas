@extends('main')

@section('content')
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
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1>Events</h1>

    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">Create Event</a>

    <ul>
        @foreach ($events as $event)
            <li>
                <a href="{{ route('events.show', ['event' => $event->id]) }}">{{ $event->title }}</a><br>
                <span>{{ $event->description }}</span><br>
                <span>Registration deadline: {{ $event->registration_deadline }}</span><br>
                <span>Start Date: {{ $event->start_datetime }}</span><br>
                <span>End Date: {{ $event->end_datetime ?: 'N/A' }}</span><br>
                @if ($event->registrations()->count() < $event->max_participants)
                    <span>Registered Participants: {{ $event->registrations()->count() }} / {{ $event->max_participants }}</span><br>
                @else
                    <span>Registered Participants: {{ $event->max_participants }} / {{ $event->max_participants }}</span><br>
                @endif
                {{--  @if ($event->registrations()->count() < $event->max_participants)
                    <a href="{{ route('event_registration.store', ['event' => $event->id]) }}">Register</a>
                @else
                    <span>Registration Closed</span>
                @endif  --}}
                <a href="{{ route('event_registration.store', ['event' => $event->id]) }}">Register</a>
            </li>
        @endforeach
    </ul>
@endsection
