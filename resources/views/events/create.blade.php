@extends('main')

@section('content')
    <main>
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
        <div class="container">
            <form action="{{ route('events.store') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required><br>

                <label for="description">Description:</label><br>
                <textarea id="description" name="description" required></textarea><br>

                <label for="files">Upload Files:</label>
                <input type="file" name="files[]" id="files" multiple accept=".jpg,.jpeg,.png,.pdf">
                <small class="form-text text-muted">You can upload multiple files (JPEG, PNG, PDF).</small>


                <label for="registration_deadline">Registration Deadline:</label>
                <input type="datetime-local" id="registration_deadline" name="registration_deadline"><br>


                <label for="start_datetime">Start Date and Time:</label>
                <input type="datetime-local" id="start_datetime" name="start_datetime" required><br>

                <label for="end_datetime">End Date and Time:</label>
                <input type="datetime-local" id="end_datetime" name="end_datetime"><br>

                <label for="max_participants">Max Participants:</label>
                <input type="number" id="max_participants" name="max_participants" min="1" required><br>


                <div id="dynamic-fields">
                    <div class="dynamic-field">
                        <input type="text" name="dynamic_fields[0][title]" placeholder="Field Title">
                        <select name="dynamic_fields[0][type]" class="field-type">
                            <option value="">select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                        <div class="options" style="display: none;">
                            <!-- Options for checkboxes or dropdowns will be added dynamically -->
                        </div>
                        <button type="button" class="add-option-btn" style="display: none;">Add Option</button>
                        <button type="button" class="remove-field-btn">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-field-btn">Add Field</button>
                <button type="submit">Create Event</button>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let fieldIndex = 1;

            $("#add-field-btn").click(function () {
                $("#dynamic-fields").append(
                    '<div class="dynamic-field">' +
                        '<input type="text" name="dynamic_fields[' + fieldIndex + '][title]" placeholder="Field Title">' +
                        '<select name="dynamic_fields[' + fieldIndex + '][type]" class="field-type">' +
                            '<option value="">select</option>' +
                            '<option value="checkbox">Checkbox</option>' +
                            '<option value="dropdown">Dropdown</option>' +
                        '</select>' +
                        '<div class="options" style="display: none;">' +
                            '<input type="text" name="dynamic_fields[' + fieldIndex + '][options][]" placeholder="Option 1">' +
                        '</div>' +
                        '<button type="button" class="add-option-btn" style="display: none;">Add Option</button>' +
                        '<button type="button" class="remove-field-btn">Remove</button>' +
                    '</div>'
                );
                fieldIndex++;
            });

            $(document).on("change", ".field-type", function () {
                let options = $(this).closest(".dynamic-field").find(".options");
                let addOptionBtn = $(this).closest(".dynamic-field").find(".add-option-btn");

                if ($(this).val() === "dropdown") {
                    options.show();
                    addOptionBtn.show();
                } else {
                    options.html('<input type="text" name="dynamic_fields[' + $(this).closest(".dynamic-field").index() + '][options][]" placeholder="Option 1">');
                    options.show();
                    addOptionBtn.show();
                }

                // Log data for debugging
                console.log("Title:", $(this).closest(".dynamic-field").find("input[name*='title']").val());
                console.log("Type:", $(this).val());
                console.log("Options:", $(this).closest(".dynamic-field").find("input[name*='options']").map(function() { return $(this).val(); }).get());
            });
        $(document).on("click", ".add-option-btn", function () {
            let optionsContainer = $(this).prev(".options");
            let optionCount = optionsContainer.children("input").length + 1;
            let fieldName = optionsContainer.closest(".dynamic-field").find("select[name*='type']").attr("name").replace("type", "options");
            optionsContainer.append('<input type="text" name="' + fieldName + '[]" placeholder="Option ' + optionCount + '">');
        });


            $(document).on("click", ".remove-field-btn", function () {
                $(this).parent(".dynamic-field").remove();
            });
        });
    </script>
@endsection
