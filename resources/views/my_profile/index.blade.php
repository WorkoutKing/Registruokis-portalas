@extends('main')

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>Mano zona</h1>
        </div>
        <div class="row">
            <div class="col-md-6 extra-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mano duomenų konfiguracija</h5>
                        <p class="card-text">Pakeiskite savo duomenis.</p>
                        <a href="/my-profile" class="btn btn-primary">Koreguoti</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 extra-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mano renginių konfiguracija</h5>
                        <p class="card-text">Valdykite savo renginius.</p>
                        <a href="/my-events" class="btn btn-primary">Valdyti renginius</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 extra-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mano registracijų konfiguracija</h5>
                        <p class="card-text">Valdykite savo renginių registracijas.</p>
                        <a href="/my-registrations" class="btn btn-danger">Valdykite registracijas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
