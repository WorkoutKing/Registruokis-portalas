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

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $registration->name }}" required>
                        <label for="name">{{ __('Vardas') }}</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ $registration->surname }}" required>
                        <label for="surname">{{ __('Pavardė') }}</label>
                        @error('surname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $registration->email }}" required>
                        <label for="email">{{ __('El.paštas') }}</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $registration->phone }}" required>
                        <label for="phone">{{ __('Telefono numeris') }}</label>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Loop through dynamic fields and display dropdown or checkbox options -->
            @foreach ($event->dynamicFields as $field)
                @if (isset($field->title))
                    @if ($field->type === 'dropdown')
                        <div class="form-floating mb-3">
                            <select style="height:60px;" class="form-control" id="{{ $field->title }}" name="dynamic_fields[{{ $field->title }}]">
                                @foreach (json_decode($field->options) as $option)
                                    <option value="{{ $option }}" @if (in_array($option, $activeOptions)) selected @endif>{{ $option }}</option>
                                @endforeach
                            </select>
                            <label for="{{ $field->title }}">{{ $field->title }}</label>
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
                            <label style="font-size:14px;color:#495057;">{{ $field->title }}</label>
                            @foreach (json_decode($field->options) as $option)
                                <div class="form-check" style="font-size:14px;color:#495057;">
                                    <input type="checkbox" class="form-check-input @if (in_array($option, $flattenedActiveOptions)) active-check @endif" id="{{ $option }}" name="dynamic_fields[{{ $field->title }}][]" value="{{ $option }}" @if (in_array($option, $flattenedActiveOptions)) checked @endif>
                                    <label class="form-check-label" for="{{ $option }}">{{ $option }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @endforeach

            <!-- Textarea for comments -->
            <div class="form-floating mb-3">
                <textarea class="form-control @error('comments') is-invalid @enderror" id="comments" name="comments" required>{{ $registration->comments }}</textarea>
                <label for="comments">{{ __('Komentaras') }}</label>
                @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        </form>
    </div>
@endsection
