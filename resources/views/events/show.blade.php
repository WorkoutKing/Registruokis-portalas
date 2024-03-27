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
        <div class="event-info">
            <h1>{{ $event->title }}</h1>
            <p><b>Įvykio data/laikas:</b>
                {{ \Carbon\Carbon::parse($event->start_datetime)->format('Y-m-d H:i') }}
                    @if ($event->end_datetime && $event->start_datetime != $event->end_datetime)
                        iki {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                    @elseif ($event->end_datetime && $event->start_datetime == $event->end_datetime)
                        {{ \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d H:i') }}
                    @else
                        N/A
                    @endif
            </p>

            <p><b>Registracijos pabaiga:</b> {{ \Carbon\Carbon::parse($event->registration_deadline)->format('Y-m-d H:i') }}</p>

            <p><b>Aprašymas:</b> {{ $event->description }}</p>

            <div class="event-files">
                <h3>Įvykio failai:</h3>
                <div class="file-list">
                    @foreach ($event->files as $file)
                        <div class="file-item">
                            @if (Str::endsWith($file->file_path, ['.jpg', '.jpeg', '.png', '.gif']))
                                <!-- Display image -->
                                <div class="file-preview">
                                    <img src="{{ Storage::url($file->file_path) }}" alt="{{ basename($file->file_path) }}">
                                </div>
                            @elseif (Str::endsWith($file->file_path, '.pdf'))
                                <!-- Display download link for PDF -->
                                <div class="file-link">
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
                                </div>
                            @else
                                <!-- Display generic download link for other file types -->
                                <div class="file-link">
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="event-details">
            </div>

            <button id="copyEventLinkBtn" class="btn btn-primary">Gauti įvykio linką</button>

            @if(auth()->check())
                <a href="{{ route('event_registration.store', ['event' => $event->id]) }}" class="btn btn-success">Registruotis</a>
            @else
                <div class="alert alert-info alert-extra-class">
                    Prašome <a href="{{ route('login') }}">prisjungti</a> or <a href="{{ route('register') }}">užsiregistruoti</a> mūsų svetainėja kad galėtumėte užsiregistruoti į renginį.
                </div>
            @endif
        </div>
            <div class="modal fade" id="eventLinkCopiedModal" tabindex="-1" role="dialog" aria-labelledby="eventLinkCopiedModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eventLinkCopiedModalLabel">Įvykio linko gavimas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Įvykio linkas sėkmingai nukopijuotas.
                        </div>
                    </div>
                </div>
            </div>
            <div class="registered-users">
                <h3>Užsiregistravę dalyviai:</h3>
                <div class="user-list">
                    <ul>
                        {{-- Display registrations without being on waiting list --}}
                        @foreach ($event->registrations->where('on_waiting_list', 0) as $registration)
                            <li>
                                <div class="user-details">
                                    <div>
                                        <strong>Vardas/Pavardė:</strong> {{ $registration->name }} {{ $registration->surname }}<br>
                                        @if (auth()->check() && $event->user_id == auth()->user()->id || auth()->check() && auth()->user()->isAdmin())
                                            <strong>El.paštas:</strong> {{ $registration->email }}<br>
                                        @endif
                                            <strong>Komentaras:</strong> {{ $registration->comments ?: 'None' }}<br>
                                        @if (auth()->check() && $event->user_id == auth()->user()->id || auth()->check() && auth()->user()->isAdmin())
                                            <strong>Telefonas:</strong> {{ $registration->phone }}<br>
                                        @endif
                                    </div>
                                    @if (auth()->check() && $event->user_id == auth()->user()->id || auth()->check() && auth()->user()->isAdmin())
                                        <div class="options-list">
                                            <strong>Pasirinkimai:</strong>
                                            <ul>
                                                @foreach ($registration->dynamicFieldsreg as $dynamicField)
                                                    <div>
                                                        <i>{{ $dynamicField->title }}</i><br>
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
                                                        @else
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                @if (auth()->check() && $event->user_id == auth()->user()->id || auth()->check() && auth()->user()->isAdmin())
                                    {{--  <div class="actions">
                                        <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ištrinti registracija</button>
                                        </form>
                                    </div>  --}}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if (auth()->check() && $event->user_id == auth()->user()->id)
                    <div class="waiting-list">
                        <span>Laukiančiųjų sąrašas:</span>
                        <ul>
                            {{-- Display registrations only for the event creator --}}
                                @foreach ($event->registrations->where('on_waiting_list', 1) as $registration)
                                    <li>
                                        <div class="user-details">
                                            <div>
                                                <strong>Vardas/Pavardė:</strong> {{ $registration->name }} {{ $registration->surname }}<br>
                                                <strong>El.paštas:</strong> {{ $registration->email }}<br>
                                                <strong>Komentaras:</strong> {{ $registration->comments ?: 'None' }}<br>
                                                <strong>Telefonas:</strong> {{ $registration->phone }}<br>
                                            </div>
                                            <div class="options-list">
                                                <strong>Pasirinkimai:</strong>
                                                <ul>
                                                    @foreach ($registration->dynamicFieldsreg as $dynamicField)
                                                        <div>
                                                            <i>{{ $dynamicField->title }}</i><br>
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
                                                            @else
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ištrinti registracija</button>
                                            </form>
                                            {{-- Form to approve registration --}}
                                            <form action="{{ route('approve_registration', ['registration' => $registration->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">Patvirtinti registracija</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="single_event_stats">
                @php
                        $optionCounts = [];
                        $totalOptions = 0;

                        foreach ($event->registrations->where('on_waiting_list', 0) as $registration) {
                            foreach ($registration->dynamicFieldsreg as $dynamicField) {
                                if (!empty($dynamicField->options)) {
                                    if (is_array($dynamicField->options)) {
                                        foreach ($dynamicField->options as $option) {
                                            if (!isset($optionCounts[$dynamicField->title][$option])) {
                                                $optionCounts[$dynamicField->title][$option] = 1;
                                            } else {
                                                $optionCounts[$dynamicField->title][$option]++;
                                            }
                                            $totalOptions++;
                                        }
                                    } else {
                                        $option = $dynamicField->options;
                                        if (!isset($optionCounts[$dynamicField->title][$option])) {
                                                $optionCounts[$dynamicField->title][$option] = 1;
                                        } else {
                                            $optionCounts[$dynamicField->title][$option]++;
                                        }
                                        $totalOptions++;
                                    }
                                }
                            }
                        }
                    @endphp

                    <span class="event_votes_title">Balsavimų statistika:</span>
                    <div class="event_vote_pos">
                        @php
                            // Sort the $optionCounts array based on the count in descending order
                            arsort($optionCounts);
                        @endphp
                        @foreach ($optionCounts as $title => $options)
                            <div class="event_vote_container">
                                <span class="vote_title">{{ $title }}</span>
                                <ul>
                                    @php
                                        // Sort the options array based on the count in descending order
                                        arsort($options);
                                    @endphp
                                    @foreach ($options as $option => $count)
                                        <li>{{ $option }} - {{ $count }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    {{--  <p>Total options: {{ $totalOptions }}</p>  --}}
                    <script>
                        function copyEventLink() {
                            var eventLink = window.location.href;
                            navigator.clipboard.writeText(eventLink);
                            $('#eventLinkCopiedModal').modal('show');
                            setTimeout(function() {
                                $('#eventLinkCopiedModal').modal('hide');
                            }, 2000);
                        }

                        document.getElementById('copyEventLinkBtn').addEventListener('click', copyEventLink);
                    </script>
                </div>
    </div>
@endsection
