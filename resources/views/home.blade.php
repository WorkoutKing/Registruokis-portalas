@extends('main')

@section('content')
    <div class="container">
        {{-- Display success, info, and warning messages --}}
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
        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Visi įvykiai:</h1>
        {{-- Display events in cards with pagination --}}
        <div class="row">
            @foreach ($events as $event)
                    <div class="col-md-6 mb-4 my-event-list">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><a href="{{ route('events.show', ['event' => $event->id]) }}">{{ $event->title }}</a></h5>
                                <p class="card-text"><b>Registracijos pabaiga:</b> {{ \Carbon\Carbon::parse($event->registration_deadline)->format('Y-m-d H:i') }}</p>
                                <p class="card-text"><b>Įvykio data/laikas:</b>
                                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('Y-m-d H:i') }}
                                    @if ($event->end_datetime && $event->start_datetime != $event->end_datetime)
                                        iki {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                                    @elseif ($event->end_datetime && $event->start_datetime == $event->end_datetime)
                                        {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                @if ($event->registrations()->count() < $event->max_participants)
                                    <p class="card-text"><b>Registruotų dalyvių skaičius:</b> {{ $event->registrations()->count() }} / {{ $event->max_participants }}</p>
                                @else
                                    <p class="card-text"><b>Registruotų dalyvių skaičius:</b> {{ $event->max_participants }} / {{ $event->max_participants }}</p>
                                @endif
                                <a class="btn btn-success" href="{{ route('event_registration.store', ['event' => $event->id]) }}" style="margin-top:15px;">Registruotis</a>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div class="my-pagination-lnk">
            {{ $events->links() }}
        </div>
    </div>
@endsection
