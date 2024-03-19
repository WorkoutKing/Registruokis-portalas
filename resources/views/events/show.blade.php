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
  <div>
        <h2>{{ $event->title }}</h2>
        <p>Description: {{ $event->description }}</p>
        <div>
            <h3>Event Files:</h3>
            @foreach ($event->files as $file)
                @if (Str::endsWith($file->file_path, ['.jpg', '.jpeg', '.png', '.gif']))
                    <!-- Display image -->
                    <p>Image: <img src="{{ Storage::url($file->file_path) }}" alt="{{ basename($file->file_path) }}" style="max-width: 100%;"></p>
                @elseif (Str::endsWith($file->file_path, '.pdf'))
                    <!-- Display download link for PDF -->
                    <p>PDF File: <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a></p>
                @else
                    <!-- Display generic download link for other file types -->
                    <p>File: <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a></p>
                @endif
            @endforeach
        </div>
        <p>Start Date: {{ $event->start_datetime }}</p>
        <p>End Date: {{ $event->end_datetime ?: 'N/A' }}</p>
        <p>Registration Deadline: {{ $event->registration_deadline ?: 'N/A' }}</p>

        <button id="copyEventLinkBtn" class="btn btn-primary">Copy Event Link</button>

        @if(auth()->check())
            <a href="{{ route('event_registration.store', ['event' => $event->id]) }}">Register to event</a>
        @else
            <div class="alert alert-info">
                Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to our site to register for this event.
            </div>
        @endif

        <div class="modal fade" id="eventLinkCopiedModal" tabindex="-1" role="dialog" aria-labelledby="eventLinkCopiedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventLinkCopiedModalLabel">Event Link Copied</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        The event link has been copied successfully.
                    </div>
                </div>
            </div>
        </div>

        <h3>Registered Users:</h3>
<span>Event joiners</span>
<ul>
    {{-- Display registrations without being on waiting list --}}
    @foreach ($event->registrations->where('on_waiting_list', 0) as $registration)
        <li>
            Name: {{ $registration->name }} {{ $registration->surname }}<br>
            Email: {{ $registration->email }}<br>
            Comments: {{ $registration->comments ?: 'None' }}<br>
            Phone: {{ $registration->phone }}<br>
            Selected Options:
            <ul>
                @foreach ($registration->dynamicFieldsreg as $dynamicField)
                    <li>
                        Field Title: {{ $dynamicField->title }}<br>
                        Field Options:
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
                            <li>No options available</li>
                        @endif
                    </li>
                @endforeach
            </ul>
                <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </li>
    @endforeach
    </ul>
    <span>On waiting list</span>
<ul>
    {{-- Display registrations only for the event creator --}}
    @if (auth()->check() && $event->user_id == auth()->user()->id)
        @foreach ($event->registrations->where('on_waiting_list', 1) as $registration)
            <li>
                Name: {{ $registration->name }} {{ $registration->surname }}<br>
                Email: {{ $registration->email }}<br>
                Comments: {{ $registration->comments ?: 'None' }}<br>
                Phone: {{ $registration->phone }}<br>
                Selected Options:
                <ul>
                    @foreach ($registration->dynamicFieldsreg as $dynamicField)
                        <li>
                            Field Title: {{ $dynamicField->title }}<br>
                            Field Options:
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
                                <li>No options available</li>
                            @endif
                        </li>
                    @endforeach
                </ul>
            <form action="{{ route('registrations.destroy', ['registration' => $registration->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
                {{-- Form to approve registration --}}
                <form action="{{ route('approve_registration', ['registration' => $registration->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </li>
        @endforeach
    @endif
</ul>


    </div>
<div>
  @php
        $optionCounts = [];
        $totalOptions = 0;

        foreach ($event->registrations as $registration) {
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

    <h3>Votes Statistics:</h3>
    @foreach ($optionCounts as $title => $options)
        <h4>{{ $title }}</h4>
        <ul>
            @foreach ($options as $option => $count)
                <li>{{ $option }} - {{ $count }}</li>
            @endforeach
        </ul>
    @endforeach

    <p>Total options: {{ $totalOptions }}</p>
</div>
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
@endsection
