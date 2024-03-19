<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDynamicField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }
    public function create()
    {
        return view('events.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'files.*' => 'mimes:jpeg,png,pdf',
                'registration_deadline' => 'nullable|date|after_or_equal:today',
                'start_datetime' => 'required|date',
                'end_datetime' => 'nullable|date|after:start_datetime',
                'max_participants' => 'nullable',
                'dynamic_fields.*.title' => 'nullable|required_if:add_dynamic_fields,true|string|required_with:dynamic_fields.*.type',
                'dynamic_fields.*.type' => 'nullable|string|required_if:add_dynamic_fields,true|in:text,checkbox,dropdown',
                'dynamic_fields.*.options.*' => 'nullable|string|required_if:add_dynamic_fields,true|required_if:dynamic_fields.*.type,dropdown',
            ],
            [
                'registration_deadline.after_or_equal' => 'The registration deadline must be a future date.',
            ]
        );

        $event = Event::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'registration_deadline' => $validatedData['registration_deadline'],
            'start_datetime' => $validatedData['start_datetime'],
            'end_datetime' => $validatedData['end_datetime'],
            'max_participants' => $validatedData['max_participants'],
        ]);


        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('event_files', 'public');
                $event->files()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }


        if (isset ($validatedData['dynamic_fields'])) {
            foreach ($validatedData['dynamic_fields'] as $field) {
                if (!empty ($field['title']) && !empty ($field['type'])) {
                    $dynamicField = new EventDynamicField([
                        'title' => $field['title'],
                        'type' => $field['type'],
                        'options' => json_encode($field['options'] ?? []),
                    ]);
                    $event->dynamicFields()->save($dynamicField);
                }

            }
        }

        return Redirect::route('events.show', ['event' => $event->id])->with('success', 'Event created successfully.');
    }

}


