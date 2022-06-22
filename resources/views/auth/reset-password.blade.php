@extends('auth.app')

@section('content')
<section class="mb-8">
    <div class="container">
        <div class="row" style="margin-top: 15vh;">
            <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="card z-index-0 shadow">
                    <div class="card-header text-center pt-4">
                        <h5 class="text-info">Nouvelle Mot de Passe</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}" class="px-4">
                            @csrf
                            <x-jet-validation-errors class="mt-1 d-flex flex-column font-medium text-sm text-danger" />
                            @if (session('status'))
                                <div class="mt-1 d-flex flex-column mb-4 font-medium text-sm text-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="mb-3">
                                <x-jet-input id="email" class="form-control" type="email" name="email"
                                    :value="old('email', $request->email)" required autofocus />
                            </div>
                            <div class="mb-3">
                                <input id="password" class="form-control" type="password" name="password" required
                                    autocomplete="new-password" placeholder="Nouveau Password" />
                            </div>
                            <div class="mb-3">
                                <input id="password_confirmation" class="form-control" type="password"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm Password" />
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn bg-gradient-info my-4 mb-2">Confirmer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
