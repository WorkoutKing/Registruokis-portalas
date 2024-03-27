<!DOCTYPE html>
<html>
<head>
    <title>Registracija progrese</title>
</head>
<body>
    <h2>Sveiki, {{ $user->name }},</h2>
    <p>Ačiū už registraciją į mūsų įvykį.</p>
    <p>Dėl didelio susidomėjimo šiuo įvykiu, mes jūsų registraciją patvirtinsime tik tada, kai bus vietų.</p>
    <p>Toliau galite sekti įvykio informaciją ir būti informuoti apie jo eigą <a href="{{ route('events.show', ['event' => $user->event_id]) }}">čia</a>.</p>
    <p>Ačiū, kad pasirinkote mūsų įvykį!</p>
    <p>Geros dienos, registruoju komanda 😊 </p>
</body>
</html>
