<!DOCTYPE html>
<html>
<head>
    <title>Registracija ištrinta</title>
</head>
<body>
    <h2>Sveiki, {{ $registration->name }},</h2>
    <p>Norime pranešti, kad jūsų registracija į mūsų <a href="{{ route('events.show', ['event' => $registration->event_id]) }}">įvykį</a> buvo ištrinta.</p>
    <p>Dėl bet kokių klausimų ar papildomos informacijos, prašome susisiekti su mumis.</p>
    <p>Geros dienos, registruoju komanda 😊</p>
</body>
</html>
