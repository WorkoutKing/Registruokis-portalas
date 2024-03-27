<!DOCTYPE html>
<html>
<head>
    <title>Registracija patvirtinta</title>
</head>
<body>
    <h2>Sveiki, {{ $user->name }},</h2>
    <p>Džiaugiamės pranešdami, kad jūsų registracija į mūsų įvykį buvo patvirtinta.</p>
    <p>Daugiau informacijos apie įvykį galite rasti <a href="{{ route('events.show', ['event' => $user->event_id]) }}">čia</a>.</p>
    <p>Laukiame jūsų dalyvavimo!</p>
    <p>Geros dienos, registruoju komanda 😊 </p>
</body>
</html>
