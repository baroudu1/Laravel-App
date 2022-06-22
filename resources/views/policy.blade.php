@extends('auth.app')

@section('content')

@include('flash-message')

        <form method="POST" action="{{ route('register.info') }}">
            @csrf
            <div>
                <label for="CIN" value="CIN" >
                <input id="CIN" class="block mt-1 w-full" type="text" name="CIN"  required autofocus autocomplete="nom" />
            </div>
            <div>
                <label for="nom" value="Nom" >
                <input id="nom" class="block mt-1 w-full" type="text" name="nom" required autofocus autocomplete="nom" />
                <input id="name" class="block mt-1 w-full" type="hidden" name="name" value="hamza" required autofocus autocomplete="name" />
            </div>
            <div>
                <label for="prenom" value="Prenom" >
                <input id="prenom" class="block mt-1 w-full" type="text" name="prenom" required autofocus autocomplete="nom" />
            </div>
            <div class="mt-4">
                <label for="email" value="Email" >
                <input id="email" class="block mt-1 w-full" type="email" name="email" required />
            </div>

            <div class="mt-4">
                <label for="password" value="Password" >
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label for="password_confirmation" value="Confirm Password" >
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>



            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    Already registered?
                </a>

                <button class="ml-4" type="submit">
                    Register
                </button>
            </div>
        </form>
@endsection
