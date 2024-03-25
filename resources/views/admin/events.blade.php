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

        <h1>Įvykių zona:</h1>

        {{-- Display events in a responsive table --}}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pavadinimas</th>
                        <th style="min-width:200px">Registracijos pabaiga</th>
                        <th style="min-width:300px">Įvykio data/laikas</th>
                        <th>Registruotų dalyvių skaičius</th>
                        <th>Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td><a href="{{ route('events.show', ['event' => $event->id]) }}">{{ $event->title }}</a></td>
                            <td>{{ \Carbon\Carbon::parse($event->registration_deadline)->format('Y-m-d H:i') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($event->start_datetime)->format('Y-m-d H:i') }}
                                @if ($event->end_datetime && $event->start_datetime != $event->end_datetime)
                                    iki {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                                @elseif ($event->end_datetime && $event->start_datetime == $event->end_datetime)
                                    {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($event->registrations()->count() < $event->max_participants)
                                    {{ $event->registrations()->count() }} / {{ $event->max_participants }}
                                @else
                                    {{ $event->max_participants }} / {{ $event->max_participants }}
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('events.destroy', ['event' => $event->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Ištrinti</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="my-pagination-lnk">
            {{ $events->links() }}
        </div>
    </div>
@endsection
