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

        <h1>Koreguoti registracija</h1>

        <form action="{{ route('my_registrations.update', ['registration' => $registration->id]) }}" method="POST" class="edit_reg_form">
            @csrf
            @method('PUT')
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <!-- Loop through event attributes and exclude certain keys -->
            @foreach ($event->getAttributes() as $key => $value)
                @if ($key !== 'id' && $key !== 'user_id' && $key !== 'created_at' && $key !== 'updated_at' && $key !== 'max_participants')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <!-- Form inputs for name, surname, email, and phone -->
            <div class="form-group">
                <label for="name">Vardas:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $registration->name }}" required>
            </div>
            <div class="form-group">
                <label for="surname">Pavardė:</label>
                <input type="text" class="form-control" id="surname" name="surname" value="{{ $registration->surname }}" required>
            </div>
            <div class="form-group">
                <label for="email">El.paštas:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $registration->email }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefono numeris:</label>
                <input type="phone" class="form-control" id="phone" name="phone" value="{{ $registration->phone }}" required>
            </div>

            <!-- Loop through dynamic fields and display dropdown or checkbox options -->
            @foreach ($event->dynamicFields as $field)
                @if (isset($field->title))
                    @if ($field->type === 'dropdown')
                        <div class="form-group">
                            <label for="{{ $field->title }}">{{ $field->title }}:</label>
                            <select class="form-control" id="{{ $field->title }}" name="dynamic_fields[{{ $field->title }}]">
                                @foreach (json_decode($field->options) as $option)
                                    <option value="{{ $option }}" @if (in_array($option, $activeOptions)) selected @endif>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif ($field->type === 'checkbox')
                    <?php
                    // Flatten the array
                    if (!function_exists('flattenArray')) {
                        function flattenArray($array) {
                            $result = [];

                            foreach ($array as $item) {
                                if (is_array($item)) {
                                    $result = array_merge($result, flattenArray($item));
                                } else {
                                    $result[] = $item;
                                }
                            }

                            return $result;
                        }
                    }
                    // Flatten the $activeOptions array
                    $flattenedActiveOptions = flattenArray($activeOptions);
                    ?>
                        <div class="form-group">
                            <label>{{ $field->title }}:</label>
                            @foreach (json_decode($field->options) as $option)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input @if (in_array($option, $flattenedActiveOptions)) active-check @endif" id="{{ $option }}" name="dynamic_fields[{{ $field->title }}][]" value="{{ $option }}" @if (in_array($option, $flattenedActiveOptions)) checked @endif>
                                    <label class="form-check-label" for="{{ $option }}">{{ $option }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @endforeach

            <!-- Textarea for comments -->
            <div class="form-group">
                <label for="comments">Komentaras:</label>
                <textarea class="form-control" id="comments" name="comments">{{ $registration->comments }}</textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        </form>
    </div>
@endsection
