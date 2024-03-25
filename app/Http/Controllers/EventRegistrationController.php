<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use App\Models\DynamicField;
use Illuminate\Support\Facades\Auth;



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
        $userId = Auth::id();

        $registrationsCount = $event->registrations()->count();
        if ($registrationsCount >= $event->max_participants) {
            $registration = EventRegistration::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'comments' => $validatedData['comments'],
                'event_id' => $event->id,
                'user_id' => $userId,
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
            return redirect()->route('events.show', ['event' => $event->id])->with('info', 'Įvykis pilnas. Jūs esate pridėti į laukimo pozicija.');

        } else {

            $registration = EventRegistration::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'comments' => $validatedData['comments'],
                'event_id' => $event->id,
                'user_id' => $userId,
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
            return redirect()->route('events.show', ['event' => $event->id])->with('success', 'Registracija sėkminga.');
        }
    }
    public function approve(EventRegistration $registration)
    {
        if (auth()->user()->id !== $registration->event->user_id) {
            return redirect()->back()->with('error', 'Jūs neturite teisių patvirtinti registracija!');
        }

        if ($registration->approve()) {
            return redirect()->back()->with('success', 'Registracija patvirtinta sėkmingai.');
        }

        return redirect()->back()->with('info', 'Ši registracija jau patvirtinta.');
    }
    public function destroy(EventRegistration $registration)
    {
        if (auth()->user()->id !== $registration->event->user_id) {
            return redirect()->back()->with('error', 'Jūs neturite teisių ištrinti registracijai.');
        }

        $registration->delete();

        return redirect()->back()->with('success', 'Registracija ištrinta sėkmingai.');
    }

    public function myRegistrations(EventRegistration $registration)
    {
        $registrations = EventRegistration::where('user_id', auth()->id())->get();

        return view('event_registration.my_registrations.index', compact('registrations'));
    }

    public function edit(EventRegistration $registration, Request $request, Event $event)
    {
        if ($registration->user_id !== auth()->id()) {
            return redirect()->route('event_registration.my_registrations.index')->with('error', 'Jūs neturite teisių redaguoti registracijai.');
        }

        $event = $registration->event;

        $activeOptions = $registration->dynamicFieldsreg()->pluck('options')->toArray();


        return view('event_registration.my_registrations.edit', compact('registration', 'event', 'activeOptions'));
    }


    public function update(Request $request, EventRegistration $registration)
    {
        if ($registration->user_id !== auth()->id()) {
            return redirect()->route('event_registration.my_registrations.index')->with('error', 'Jūs neturite teisių redaguoti registracijai.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|min:8',
            'comments' => 'nullable|string',
            // Add validation rules for event fields if needed
        ]);

        // Update registration details
        $registration->update($validatedData);

        // Update dynamic fields
        if ($request->has('dynamic_fields')) {
            foreach ($request->input('dynamic_fields') as $fieldName => $fieldValue) {
                $registration->updateDynamicField($fieldName, $fieldValue);
            }
        }

        return redirect()->route('my_registrations.index')->with('success', 'Registracija sėkmingai paredaguota.');
    }


}
