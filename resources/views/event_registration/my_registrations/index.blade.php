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
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <h1>Mano registracijos</h1>

    <!-- Check if there are any registrations -->
    @if ($registrations->isEmpty())
    <p>nesate padarę jokių registracijų.</p>
    @else
    <!-- Loop through each registration -->
    @foreach ($registrations->where('on_waiting_list', 0) as $registration)

    <div class="row_fl mb-4 border rounded p-3">
        <!-- Registration details -->
        <div class="col-md-4 reg_user_data">
            <i style="color:green;display:block;">Registracija sėkminga.</i>
            <strong>Vardas/Pavardė:</strong> {{ $registration->name }} {{ $registration->surname }}<br>
            <strong>El.paštas:</strong> {{ $registration->email }}<br>
            <strong>Komentaras:</strong> {{ $registration->comments ?: 'None' }}<br>
            <strong>Telefono numeris:</strong> {{ $registration->phone }}<br>
            <strong>Renginys:</strong> <a href="{{ route('events.show', ['event' => $registration->event->id]) }}">{{ $registration->event->title }}</a><br>
        </div>
        <!-- Selected options -->
        <div class="col-md-4 reg-selections">
            <strong>Pasirinkimai:</strong>
            <div class="reg_selection_options">
                @foreach ($registration->dynamicFieldsreg as $dynamicField)
                    <i>{{ $dynamicField->title }}:</i><br>
                    @if (!empty($dynamicField->options))
                        @if (is_array($dynamicField->options))
                            <ul>
                                @foreach ($dynamicField->options as $option)
                                    <li>{{ $option }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ $dynamicField->options }}</p>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
        <!-- Delete and Update buttons -->
        <div class="col-md-4 d-flex align-items-center justify-content-end reg_action_btn">
            <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST" class="mr-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Ištrinti registracija</button>
            </form>
            <a href="{{ route('my_registrations.edit', ['registration' => $registration->id]) }}" class="btn btn-primary">Koreguoti</a>
        </div>
    </div>
    @endforeach
     @foreach ($registrations->where('on_waiting_list', 1) as $registration)

    <div class="row_fl mb-4 border rounded p-3">
        <!-- Registration details -->
        <div class="col-md-4 reg_user_data">
            <i style="color:red;display:block;">Laukime kol atsiras laisva vieta</i>
            <strong>Vardas/Pavardė:</strong> {{ $registration->name }} {{ $registration->surname }}<br>
            <strong>El.paštas:</strong> {{ $registration->email }}<br>
            <strong>Komentaras:</strong> {{ $registration->comments ?: 'None' }}<br>
            <strong>Telefono numeris:</strong> {{ $registration->phone }}<br>
            <strong>Renginys:</strong> <a href="{{ route('events.show', ['event' => $registration->event->id]) }}">{{ $registration->event->title }}</a><br>
        </div>
        <!-- Selected options -->
        <div class="col-md-4 reg-selections">
            <strong>Pasirinkimai:</strong>
            <div class="reg_selection_options">
                @foreach ($registration->dynamicFieldsreg as $dynamicField)
                    <i>{{ $dynamicField->title }}:</i><br>
                    @if (!empty($dynamicField->options))
                        @if (is_array($dynamicField->options))
                            <ul>
                                @foreach ($dynamicField->options as $option)
                                    <li>{{ $option }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ $dynamicField->options }}</p>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
        <!-- Delete and Update buttons -->
        <div class="col-md-4 d-flex align-items-center justify-content-end reg_action_btn">
            <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST" class="mr-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Ištrinti registracija</button>
            </form>
            <a href="{{ route('my_registrations.edit', ['registration' => $registration->id]) }}" class="btn btn-primary">Koreguoti</a>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
