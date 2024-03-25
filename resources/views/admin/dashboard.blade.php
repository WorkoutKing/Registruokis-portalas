@extends('main')

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>Administratoriaus zona</h1>
        </div>
        <div class="row">
            <div class="col-md-6 extra_class_card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Vartotojų konfiguracija</h5>
                        <p class="card-text">Valdykite vartotojų paskyras ir privilegijas.</p>
                        <a href="/admin/users" class="btn btn-primary">Valdyti vartotojus</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 extra_class_card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Eventų trinimas</h5>
                        <p class="card-text">Trinkite įvykius iš sistemos.</p>
                        <a href="/admin/events" class="btn btn-danger">Trinti įvykius</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
