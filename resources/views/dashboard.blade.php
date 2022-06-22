@extends('auth.app')

@section('content')
    @include('inc.nav')
    <div class="container-fluid py-4">
        <input type="hidden" id="{{ $poste }}" class="ha-helpp">
        <input type="hidden" class="annee" value="{{ $anneee }}">

        <div class="row">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white ">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($poste == -1 && $poste != -2)

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <a href="{{ route('gestionModule') }}">
                            <div class="dropdown card-body p-3">
                                <div class="row">
                                    <div class="col-8">

                                        <span class="numbers">
                                            <span class="font-weight-bolder mb-0 ">
                                                Gestion des Modules
                                            </span>
                                        </span>

                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                            <i class="fad fa-books text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <a href="{{ route('gestionNote') }}">
                            <div class="card-body p-3">
                                <div class="row">

                                    <div class="col-8">

                                        <span class="numbers">
                                            <span class="font-weight-bolder mb-0">
                                                Gestion des Notes
                                            </span>
                                        </span>

                                    </div>

                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                            <i class="fad fa-book-reader text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <a href="{{ route('gestionEtudiant') }}">
                            <div class="card-body p-3">
                                <div class="row">

                                    <div class="col-8">

                                        <span class="numbers">
                                            <span class="font-weight-bolder mb-0">
                                                Gestion des Etudiants
                                            </span>
                                        </span>

                                    </div>

                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                            <i class="fad fa-user-graduate text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <a href="{{ route('gestionEnseignant') }}">
                            <div class="dropdown card-body p-3">
                                <div class="row">
                                    <div class="col-8">

                                        <span class="numbers">
                                            <span class="font-weight-bolder mb-0 ">
                                                Gestion des Enseignants
                                            </span>
                                        </span>

                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                            <i class="fad fa-user-tie text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
        </div>
        <div class="row my-4 ">
            <div class="col-lg-6">
                <div class="card mb-1">
                    <div class="card-header pb-0" style="height: 5px;">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>MIP</h6>

                            </div>

                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive row">
                            <table class="table align-items-center mb-0 col">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Modules</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Progrès</th>
                                    </tr>
                                </thead>
                                <tbody class="result">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header pb-0" style="height: 5px;">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>BCG</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive row">
                            <table class="table align-items-center mb-0 col">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Modules</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Progrès</th>
                                    </tr>
                                </thead>
                                <tbody class="result1">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- :::::::::::::::::::::header::::::::::::::::::::::::::::::::: -->

        <div class="container-fluid py-4">
            <div class="row">
                @if ($poste >= 0 && $poste != -2)
                    @if ($poste == 0)

                        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                            <a href="{{ route('remplirNotes') }}">
                                <div class="card">
                                    <div class="card-body p-3">

                                        <div class="row">
                                            <div class="col-8 ">
                                                <span class="numbers">
                                                    <span class="font-weight-bolder mb-0">
                                                        Remplir des Notes
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div
                                                    class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="fal fa-table text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                            <a href="{{ route('generePV') }}">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="numbers">
                                                    <span class="font-weight-bolder mb-0">
                                                        Generer PV
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div
                                                    class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="far fa-poll-h text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <a href="{{ route('remplirNotes') }}">
                                <div class="card">
                                    <div class="card-body p-3">

                                        <div class="row">
                                            <div class="col-8 ">
                                                <span class="numbers">
                                                    <span class="font-weight-bolder mb-0">
                                                        Remplir des Notes
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div
                                                    class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="fal fa-table text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <a href="{{ route('generePV') }}">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="numbers">
                                                    <span class="font-weight-bolder mb-0">
                                                        Generer PV
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div
                                                    class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="far fa-poll-h text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4 mx-auto">
                            <a href="{{ route('affectation') }}">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="numbers">
                                                    <span class="font-weight-bolder mb-0">
                                                        Affectation des modules
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div
                                                    class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="fas fa-users-class text-lg opacity-10 mt-0" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
            </div>
            <div class="container-fluid p-2 ">
                <div class="row ">
                    <div class="col-md-10 mx-auto">
                        <div class="card mb-4">
                            @if ($poste > 0)
                                <div class="card-header pb-0">
                                    <h6>{{ $fill }}</h6>
                                </div>
                            @endif
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0 col">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    @if ($poste > 0)
                                                        Modules
                                                    @else
                                                        Elements
                                                    @endif
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Progrès</th>
                                            </tr>
                                        </thead>
                                        <tbody class="result">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @endif
        </div>


        <!-- :::::::::::::::::::::end header::::::::::::::::::::::::::::::::: -->


        <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->


        <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->

    @endsection
    @section('scriptt')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                if ($(".ha-helpp").attr('id') == 0) {
                    let anne = $(".annee").val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: '{{ route('puissance1') }}',
                        data: {
                            annee: anne
                        },
                        success: function(response) {
                            console.log(response);
                            $(".result").html(response);
                        }
                    });
                }
                if ($(".ha-helpp").attr('id') > 0) {
                    let anne = $(".annee").val();
                    let id_fi = $(".ha-helpp").attr('id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: '{{ route('puissance') }}',
                        data: {
                            id_fi: id_fi,
                            annee: anne
                        },
                        success: function(response) {
                            console.log(response);
                            $(".result").html(response);
                        }
                    });
                }
                if ($(".ha-helpp").attr('id') == -1) {
                    let anne = $(".annee").val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: '{{ route('puissance') }}',
                        data: {
                            id_fi: 1,
                            annee: anne
                        },
                        success: function(response) {
                            console.log(response);
                            $(".result").html(response);
                        }
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: '{{ route('puissance') }}',
                        data: {
                            id_fi: 2,
                            annee: anne
                        },
                        success: function(response) {
                            console.log(response);
                            $(".result1").html(response);
                        }
                    });
                }
            });
        </script>
    @endsection
