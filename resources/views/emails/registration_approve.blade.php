<!DOCTYPE html>
<html>
<head>
    <title>Registracija patvirtinta</title>
</head>
<body>
    <h2>Sveiki, {{ $registration->name }},</h2>
    <p>Mes norime pranešti, kad jūsų registracija į mūsų įvykį buvo patvirtinta.</p>
    <p>Galite sužinoti daugiau apie įvykį ir peržiūrėti jo detales <a href="{{ route('events.show', ['event' => $registration->event_id]) }}">čia</a>.</p>
    <p>Laukiame jūsų dalyvavimo!</p>
    <p>Geros dienos, registruoju komanda 😊 </p>
</body>
</html>
