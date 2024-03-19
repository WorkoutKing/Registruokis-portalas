@extends('main')

@section('content')
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

        <a href="{{ route('event_registration.store', ['event' => $event->id]) }}">Register to event</a>

        <button id="copyLinkBtn">Copy Event Page Link</button>

        <h3>Registered Users:</h3>
        <ul>
            @foreach ($event->registrations as $registration)
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
                </li>
            @endforeach
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
        document.getElementById("copyLinkBtn").addEventListener("click", function() {
            var url = window.location.href;
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = url;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            alert("Event page link copied to clipboard: " + url);
        });
    </script>
@endsection
