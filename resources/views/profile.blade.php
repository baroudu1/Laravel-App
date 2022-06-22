@extends('auth.app')

@section('content')
    @include('inc.nav')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-text text-white ">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="page-header min-height-100 border-radius-xl mt-4"
            style="background-image: url('../img/curved0.jpg'); background-position-y: 50%; ">
            <span class="mask bg-gradient-dark opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n5">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="../img/bruce-mars.jpg" alt="..." class="w-100 border-radius-lg shadow-sm">

                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100 ">
                        <h5 class="mb-1">
                            {{ $nom . '  ' . $prenom }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm ">
                            @if ($poste == -1)
                                Administrateur
                            @else
                                Departement : <b>{{ $depp }}</b>
                            @endif
                        </p>
                        @if ($poste > 0)
                            <p class="mb-0 font-weight-bold text-sm ">
                                Coordonnateur de Filière : <b>{{ $fill }}</b>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4 ">
        <div class="row">
            <div class="col-12 col-xl-5 col-sm-6">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mt-2">Informations sur le Profil</h6>
                    </div>
                    <hr class="horizontal gray-light my-2">
                    <div class="card-body px-3">
                        <ul class="list-group ">
                            <li class="border-0 ps-0 pb-3 text-sm row">
                                <div class="col-md-3">
                                    <strong class="text-dark">Nom / Prenom:</strong>
                                </div>
                                <div class="col-md-6 mt-2 mb-2">
                                    {{ $nom . ' ' . $prenom }}
                                </div>
                            </li>
                            <li class="border-0 ps-0 pt-2 text-sm row">
                                <div class="col-md-3">
                                    <strong class="text-dark">Email:</strong>
                                </div>
                                <div class="col-md-9 mb-2">
                                    {{ $email }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-1 col-sm-1"></div>
            <div class="col-12 col-xl-5 col-sm-5">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mt-2">Modifier le Mot de Passe</h6>
                    </div>
                    <hr class="horizontal gray-light my-2">
                    <div class="mx-2">
                        @include('flash-message')
                    </div>
                    <div class="card-body px-3">
                        <form method="POST" class="row" id="ajaxform">
                            <div class="ajaxnot"></div>
                            @csrf
                            <div class="col-md-6 mb-2 text-center">
                                <label class="block font-medium text-sm text-gray-700" for="current_password">
                                    Ancienne Mot de Passe
                                </label>
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <input id="current_password" type="password" class="form-control" type="password"
                                    name="current_password" required autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-2 text-center">
                                <label class="block font-medium text-sm text-gray-700" for="New_Password">
                                    Nouveau Mot de Passe
                                </label>
                            </div>
                            <div class="col-md-6 mb-2">
                                <input id="New_Password" type="password" class="form-control" type="password"
                                    name="New_Password" required autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-2 text-center">
                                <label class="block font-medium text-sm text-gray-700" for="Confirm_Password">
                                    Confirmez le Mot de Passe
                                </label>
                            </div>
                            <div class="col-md-6 mb-2">
                                <input id="Confirm_Password" type="password" class="form-control" type="password"
                                    name="Confirm_Password" required autocomplete="off">
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn bg-gradient-dark w-80 btn-round mt-2 ">Modifier Mot de
                                    Passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scriptt')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(document).on("submit", "#ajaxform", function(e) {
                    e.preventDefault();
                    $(".ajaxnot").empty();

                    if ( $('#current_password').val() != "" && $('#New_Password').val() != "" && $('#Confirm_Password').val() == $('#New_Password')
                        .val()) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: '{{ route('change.password1') }}',
                            data: new FormData(this),
                            contentType: false,
                            cache: false, // To unable request pages to be cached
                            processData: false,
                            success: function(response) {
                                console.log(response);
                                if (response.ha)
                                    $(".ajaxnot").prepend(
                                        '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>' +
                                        response.message +
                                        ' </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                    );
                                else
                                    $(".ajaxnot").prepend(
                                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> ' +
                                        response.message +
                                        ' </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                    );
                                $('#ajaxform')[0].reset();

                            },
                            error: function(response) {
                                $(".ajaxnot").prepend(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Ces mots de passe ne correspondent pas. Veuillez réessayer  </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );
                                $('#ajaxform')[0].reset();

                            }
                        });
                    } else {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Ces mots de passe ne correspondent pas. Veuillez réessayer  </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                        $('#ajaxform')[0].reset();
                    }

                });

            });
        </script>
    @endsection
