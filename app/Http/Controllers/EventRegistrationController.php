<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use App\Models\DynamicField;



class EventRegistrationController extends Controller
{
    public function create(Event $event)
    {
        return view('event_registration.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|min:8',
            'comments' => 'nullable|string',
        ]);

        $registrationsCount = $event->registrations()->count();
        if ($registrationsCount >= $event->max_participants) {
            $registration = EventRegistration::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'comments' => $validatedData['comments'],
                'event_id' => $event->id,
                'on_waiting_list' => 1,
            ]);
            if ($request->has('dynamic_fields')) {
                foreach ($request->input('dynamic_fields') as $fieldName => $fieldValue) {
                    if (!empty ($fieldName) && !empty ($fieldValue)) {
                        $dynamicField = new DynamicField([
                            'title' => $fieldName,
                            'options' => $fieldValue,
                            'event_registration_id' => $registration->id,
                        ]);
                        $dynamicField->event_id = $event->id;
                        $dynamicField->save();
                    }
                }
            }

            return redirect()->back()->with('info', 'Event is full. You have been added to the waiting list.');
        } else {

            $registration = EventRegistration::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'comments' => $validatedData['comments'],
                'event_id' => $event->id,
                'on_waiting_list' => 0,
            ]);

            if ($request->has('dynamic_fields')) {
                foreach ($request->input('dynamic_fields') as $fieldName => $fieldValue) {
                    if (!empty ($fieldName) && !empty ($fieldValue)) {
                        $dynamicField = new DynamicField([
                            'title' => $fieldName,
                            'options' => $fieldValue,
                            'event_registration_id' => $registration->id,
                        ]);
                        $dynamicField->event_id = $event->id;
                        $dynamicField->save();
                    }
                }
            }

            return redirect()->back()->with('success', 'Registration successful.');
        }
    }
}
