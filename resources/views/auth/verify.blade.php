@extends('main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">{{ __('Patvirtinkite savo el. pašto adresą') }}</h3></div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Naujas patvirtinimo nuorodas išsiųstas į jūsų el. pašto adresą.') }}
                        </div>
                    @endif

                    <p class="text-center">{{ __('Prie tęsiant, prašome patikrinti savo el. paštą dėl patvirtinimo nuorodos.') }}</p>
                    <p class="text-center">{{ __('Jei negavote el. laiško,') }},</p>
                    <form class="d-inline text-center" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('spauskite čia, kad galėtumėte paprašyti kito') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
