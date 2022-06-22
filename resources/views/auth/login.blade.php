@extends('auth.app')

@section('content')
    <section class="ha-mar">
        <div class="page-header section-height-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column" style="height:100vh;">
                        <div class="card card-plain mt-8" id="ha-mar">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient">Connexion</h3>
                            </div>
                            <div class="card-body">
                                @if (session('errors'))
                                    <div class="mt-1 d-flex flex-column mb-4 font-medium text-sm text-danger text-center">
                                        @if (str_contains(session('errors'), 't find a user'))
                                        <li>
                                            Nous ne pouvons pas trouver d'utilisateur avec cette adresse e-mail.
                                        </li>
                                        @else
                                        <li>
                                            E-mail ou Le mot de passe est incorrect .
                                        </li>
                                        @endif
                                    </div>

                                @endif
                                @if (session('status'))
                                    <div class="mt-1 d-flex flex-column mb-4 font-medium text-sm text-success">
                                        @if (str_contains(session('status'), 'We have emailed your password'))
                                        <li>
                                            Un e-mail a été envoyer a votre adresse mail.
                                        </li>
                                        @else
                                        <li>
                                            Votre mot de passe a été réinitialisé!
                                        </li>
                                        @endif
                                    </div>
                                @endif
                                <form role="form text-left" id="ha-ss" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <x-jet-label for="email" value="{{ __('Email') }}" />
                                    <div class="mb-3">
                                        <input type="email" class="form-control" type="email" name="email"

                                            id="ha-email" required autofocus>
                                    </div>
                                    <x-jet-label for="password" value="{{ __('Mot de Passe') }}" />
                                    <div class="mb-2">
                                        <input type="password" class="form-control" type="password" id="ha-pass"
                                            name="password" required autocomplete="current-password">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn bg-gradient-info w-100 mt-3 mb-0">S'identifier</button>
                                    </div>
                                </form>
                                <div class="card-footer pt-0 px-lg-2 px-1" style="margin-top: 20px;">
                                    <label class="form-check-label"
                                        for="flexSwitchCheckChecked"><b>{{ __('Mot de passe oublié ?') }}</b></label>
                                    <div class="form-switch" style="display: inline-block;">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div id="hide-show" style="display: none;">
                                        <x-jet-label for="email"
                                            value="{{ __('Tapez votre adresse académique ici :') }}" />
                                        <div class="mb-3">
                                            <input id="email" class="form-control" type="email" name="email"

                                                placeholder="{{ __('@usmba.ac.ma') }}" required autofocus />
                                        </div>
                                        <div class="footer text-right" style="margin-top: -20px;">
                                            <button type="submit"
                                                class="btn bg-gradient-info btn-round mt-4 mb-0">Envoyer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div>
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                                style="background-image:url('img/banner4.jpg')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scriptt')
    @if (session('errors'))
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        @if (str_contains(session('errors'), 't find a user'))
        <script type="text/javascript">
            $(document).ready(function() {
                $('#email').addClass('is-invalid');
            });
        </script>
        @else
        <script type="text/javascript">
            $(document).ready(function() {
                $('#ha-email').addClass('is-invalid');
                $('#ha-pass').addClass('is-invalid');
            });
        </script>
        @endif

    @endif
@endsection
