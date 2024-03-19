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

        <h1>Renginio kūrimas</h1>

        <form action="{{ route('events.store') }}" method="POST"  enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Pavadinimas:</label>
                <input type="text" id="title" name="title" required class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Aprašymas:</label>
                <textarea id="description" name="description" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="files">Nuotraukos arba failo pridėjimas:</label>
                <div class="custom-file">
                    <input type="file" name="files[]" id="files" multiple accept=".jpg,.jpeg,.png,.pdf" class="custom-file-input" onchange="updateFileName()">
                    <label class="custom-file-label" for="files" id="file-label">Pasirinkti failą</label>
                </div>
                <small class="form-text text-muted" id="file-count">Galimi failų formatai: JPEG, PNG, PDF.</small>
            </div>

            <div class="form-group">
                <label for="registration_deadline">Registracijos pabaiga:</label>
                <input type="datetime-local" id="registration_deadline" name="registration_deadline" class="form-control">
            </div>

            <div class="form-group">
                <label for="start_datetime">Renginio pradžia:</label>
                <input type="datetime-local" id="start_datetime" name="start_datetime" required class="form-control">
            </div>

            <div class="form-group">
                <label for="end_datetime">Renginio pabaiga:</label>
                <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control">
            </div>

            <div class="form-group">
                <label for="max_participants">Maksimalus dalyvių skaičius:</label>
                <input type="number" id="max_participants" name="max_participants" min="1" required class="form-control">
            </div>

            <div id="dynamic-fields">
                {{--  <div class="dynamic-field">
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
                </div>  --}}
            </div>

            <button type="button" id="add-field-btn" class="btn btn-primary">Pridėti dinaminius laukus</button>
            <button type="submit" class="btn btn-success">Sukurti renginį</button>
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let fieldIndex = 1;

        $("#add-field-btn").click(function () {
            $("#dynamic-fields").append(
                '<div class="dynamic-field form-group">' +
                    '<input type="text" name="dynamic_fields[' + fieldIndex + '][title]" placeholder="Dinaminio lauko pavadinimas" class="form-control">' +
                    '<select name="dynamic_fields[' + fieldIndex + '][type]" class="field-type form-control">' +
                        '<option value=""> -- prašome pasirinkti lauko tipą -- </option>' +
                        '<option value="checkbox">Checkbox</option>' +
                        '<option value="dropdown">Dropdown</option>' +
                    '</select>' +
                    '<div class="options" style="display: none;">' +
                        '<input type="text" name="dynamic_fields[' + fieldIndex + '][options][]" placeholder="Pasirinkimas" class="form-control">' +
                    '</div>' +
                    '<button type="button" class="add-option-btn btn btn-secondary" style="display: none; margin-right:3px;">Pridėti pasirinkimą</button>' +
                    '<button type="button" class="remove-field-btn btn btn-danger">Ištrinti dinaminį lauką</button>' +
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
                options.html('<input type="text" name="dynamic_fields[' + $(this).closest(".dynamic-field").index() + '][options][]" placeholder="Pasirinkimas" class="form-control">');
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
            optionsContainer.append('<input type="text" name="' + fieldName + '[]" placeholder="Pasirinkimas ' + optionCount + '" class="form-control">');
        });

        $(document).on("click", ".remove-field-btn", function () {
            $(this).parent(".dynamic-field").remove();
        });
    });
    // Upload file changing label
    function updateFileName() {
        const input = document.getElementById('files');
        const label = document.getElementById('file-label');
        const countInfo = document.getElementById('file-count');

        // Update the label text with the number of files selected
        if (input.files && input.files.length > 0) {
            const fileCount = input.files.length;
            const labelText = `${fileCount} failai pasirinkti`;
            label.innerText = labelText;
        } else {
            // If no files are selected, revert to the default text
            label.innerText = 'Pasirinkti failą';
        }

        // Display a hint about selected files
        if (input.files && input.files.length > 0) {
            countInfo.innerText = 'Pasirinkti failai:';
        } else {
            countInfo.innerText = 'Galimi failų formatai: JPEG, PNG, PDF.';
        }
    }
</script>
@endsection
