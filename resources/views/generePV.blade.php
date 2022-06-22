@extends('auth.app')

@section('content')
    @include('inc.nav')

    <div class="container-fluid" style="overflow-x: hidden;">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-text text-white ">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white ">{{ $error }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
            <div class="loader">
                <img src="../img/aaa.gif">
            </div>
        </div>
        <div class="card card-body blur shadow-blur mx-4">
            <input type="hidden" id="{{ $poste }}" class="ha-helpp">
            <div class="row">
                <div class="mx-auto">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            @if ($poste > 0)
                                <li class="nav-item active">
                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#pill0" role="tab"
                                        aria-controls="overview" aria-selected="true">
                                        <span class="ms-1">
                                            PV d'Element
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="#pill1" role="tab"
                                        aria-controls="overview" aria-selected="false">
                                    @else
                                <li class="nav-item active">
                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#pill1" role="tab"
                                        aria-controls="overview" aria-selected="true">
                            @endif
                            <span class="ms-1">
                                @if ($poste == 0)
                                    PV d'Element
                                @else
                                    PV de Module
                                @endif
                            </span>
                            </a>
                            </li>
                            @if ($poste != 0 && $poste != -2)
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pill2" role="tab"
                                        aria-controls="teams" aria-selected="false">
                                        <span class="ms-1">PV de Semestre</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pill3" role="tab"
                                        aria-controls="dashboard" aria-selected="false">
                                        <span class="ms-1">PV de Filière</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid col-lg-10" style="margin-top: 20px;">
        <div class="card card-body mx-4">
            <div class="row">
                <div class="mx-auto">
                    <div class="position-relative end-0">
                        <div class="tab-content tab-space">
                            @if ($poste > 0)
                                <div class="tab-pane active" id="pill0">
                                    <form method="POST" action="{{ route('PV_module.hamzaa') }}">
                                        @csrf
                                        <div class="row p-2">

                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <input type="hidden" value="0" name="id_mm">
                                                    <input type="hidden" id="annee" name="annee"
                                                        value="{{ $anneee }}">
                                                    <select class="custom-select" id="id_el" name="id_module" required>
                                                        <option selected value="">Element</option>
                                                        @foreach ($element as $c)
                                                            <option value="{{ $c->id_element }}">
                                                                {{ $c->nom_element }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="id_sec" name="section" required>
                                                        <option selected value="">Section</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-2"></div>
                                            <div class="form-check text-left col-xl-8 col-md-8 mb-xl-0"></div>
                                            <div class="col-xl-4 col-md-4 mb-xl-0 text-right">
                                                <button type="submit"
                                                    class="col-12 btn bg-gradient-success">Générer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="pill1">
                                @else
                                    <div class="tab-pane active" id="pill1">
                            @endif
                            <form method="POST" id="ajaxform" action="{{ route('PV_module.hamzaa') }}">
                                @csrf
                                <div class="row p-2">
                                    @if ($poste == -1)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="annee" name="annee" required>
                                                    <option selected value="">Année</option>
                                                    @foreach ($anne as $a)
                                                        <option value="{{ $a->annee }}">
                                                            {{ $a->annee }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_cy" required>
                                                    <option selected value="">Cycle</option>
                                                    @foreach ($cycle as $c)
                                                        <option value="{{ $c->id_cycle }}">
                                                            {{ $c->nom_cycle }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_fi" required>
                                                    <option selected value="">Filiere</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($poste != 0)
                                        @if ($poste > 0)

                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" disabled required>
                                                        <option selected value="">{{ $fill }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" id="id_fi" name="id_fi" value="{{ $poste }}">
                                            <input type="hidden" id="annee" name="annee" value="{{ $anneee }}">
                                        @endif
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_mo" name="id_module" required>
                                                    <option selected value="">Modules</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" value="1" name="id_mm">
                                    @else
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <input type="hidden" value="0" name="id_mm">
                                                <input type="hidden" id="annee2" name="annee" value="{{ $anneee }}">
                                                <select class="custom-select" id="id_el" name="id_module" required>
                                                    <option selected value="">Element</option>
                                                    @foreach ($element as $c)
                                                        <option value="{{ $c->id_element }}">
                                                            {{ $c->nom_element }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($poste < 3)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_sec_mo" name="section" required>
                                                    <option selected value="">Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" id="id_sec" name="section" value="-">
                                    @endif
                                    <div class="col-md-6 mb-2"></div>
                                    <div class="form-check text-left col-xl-8 col-md-8 mb-xl-0"></div>
                                    <div class="col-xl-4 col-md-4 mb-xl-0 text-right">
                                        <button type="submit" class="col-12 btn bg-gradient-success">Générer</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        @if ($poste != 0 && $poste != -2)
                            <div class="tab-pane " id="pill2">
                                <form method="POST" id="ajaxform2" action="{{ route('PV_Semestre') }}">
                                    @csrf
                                    <div class="row p-2">
                                        @if ($poste == -1)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="annee2" name="anne" required>
                                                        <option selected value="">Année</option>
                                                        @foreach ($anne as $a)
                                                            <option value="{{ $a->annee }}">
                                                                {{ $a->annee }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="id_cy2" required>
                                                        <option selected value="">Cycle</option>
                                                        @foreach ($cycle as $c)
                                                            <option value="{{ $c->id_cycle }}">
                                                                {{ $c->nom_cycle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="id_fi2" name="id_fi" required>
                                                        <option selected value="">Filière</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" disabled required>
                                                        <option selected value="">{{ $fill }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" id="id_fi2" name="id_fi" value="{{ $poste }}">
                                            <input type="hidden" id="annee2" name="anne" value="{{ $anneee }}">
                                        @endif

                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_se" name="id_se" required>
                                                    <option selected value="">Semestre</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="text-left col-xl-8 col-md-8 "></div>
                                        <div class="col-xl-4 col-md-4 mb-xl-0 text-right">
                                            <button type="submit" class="col-12 btn bg-gradient-success">Générer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="pill3">
                                <form method="POST" id="ajaxform3" action="{{ route('PV_Filiere') }}">
                                    @csrf
                                    <div class="row p-2">
                                        @if ($poste == -1)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="annee3" name="anne" required>
                                                        <option selected value="">Année</option>
                                                        @foreach ($anne as $a)
                                                            <option value="{{ $a->annee }}">
                                                                {{ $a->annee }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="id_cy3" name="id_cy3" required>
                                                        <option selected value="">Cycle</option>
                                                        @foreach ($cycle as $c)
                                                            <option value="{{ $c->id_cycle }}">
                                                                {{ $c->nom_cycle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" id="id_fi3" name="id_fi" required>
                                                        <option selected value="">Filière</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-2"></div>
                                            <div class="form-check text-left col-xl-8 col-md-8 mb-xl-0"></div>
                                        @else
                                            <input type="hidden" id="id_fi3" name="id_fi" value="{{ $poste }}">
                                            <input type="hidden" id="annee3" name="anne" value="{{ $anneee }}">
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <select class="custom-select" disabled required>
                                                        <option selected value="">{{ $fill }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-check col-xl-2 col-md-2 mb-xl-0"></div>
                                        @endif
                                        <input type="hidden" id="id_seccc" name="section" value="">
                                        <div class="col-xl-4 col-md-4 mb-xl-0 text-right">
                                            <button type="submit" class="col-12 btn bg-gradient-success">Générer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scriptt')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let id_fi = $("#id_fi").val();
            let anne = $("#annee").val();
            if (id_fi > 2) {
                $("#id_sec").fadeOut(200);
            } else if (id_fi > 2) {
                $("#id_sec").fadeIn(200);
            }
            if ($(".ha-helpp").attr('id') > 0) {
                let id_fi = $("#id_fi").val();
                let anne = $("#annee").val();
                if (id_fi > 2) {
                    $("#id_sec").fadeOut(200);
                } else {
                    $("#id_sec").fadeIn(200);
                }
                $('#id_mo').empty();
                $('#id_mo').append(`<option selected value="">Modules</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('get_Modules.hamzaa') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_mo').append(
                                `<option value="${element['id_module']}">${element['nom_module']}</option>`
                            );
                        });
                    }
                });
                $('#id_se').empty();
                $('#id_se').append(`<option selected value="">Semestre</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('get_Semestre.hamzaa') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_se').append(
                                `<option value="${element['id_semestre']}">${element['nom_semestre']}</option>`
                            );
                        });
                    }
                });
            }
            $(document).on("change", "#id_cy", function() {
                let idd = $(this).val();
                if (idd >= 2) {
                    $("#id_sec").fadeOut(200);
                } else {
                    $("#id_sec").fadeIn(200);
                }
                $('#id_fi').empty();
                $('#id_fi').append(`<option selected value="">Filiere</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('GetSubCatAgainstMainCatEdit') }}',
                    data: {
                        id: idd
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_fi').append(
                                `<option value="${element['id_filiere']}">${element['nom_filiere']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_fi", function() {
                let id_fi = $(this).val();
                let anne = $("#annee").val();
                $('#id_mo').empty();
                $('#id_mo').append(`<option selected value="">Modules</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('get_Modules.hamzaa') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_mo').append(
                                `<option value="${element['id_module']}">${element['nom_module']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_fi2", function() {
                let id_fi = $(this).val();
                let anne = $("#annee2").val();
                $('#id_se').empty();
                $('#id_se').append(`<option selected value="">Semestre</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('get_Semestre.hamzaa') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_se').append(
                                `<option value="${element['id_semestre']}">${element['nom_semestre']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_mo", function() {

                let id_mo = $(this).val();
                let anne = $("#annee").val();
                $('#id_sec_mo').empty();
                $('#id_sec_mo').append(`<option selected value="">Section</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('get_secc.hamzaa') }}',
                    data: {
                        id_mo: id_mo,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        if (response[0].section == "-") {
                            $('#id_sec_mo').empty();
                        }
                        response.forEach(element => {

                            $('#id_sec_mo').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_cy2", function() {
                let idd = $(this).val();

                $('#id_fi2').empty();
                $("#id_se").empty();
                $('#id_se').append(`<option selected value="">Semestre</option>`);
                $('#id_fi2').append(`<option selected value="">Filiere</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('GetSubCatAgainstMainCatEdit') }}',
                    data: {
                        id: idd
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_fi2').append(
                                `<option value="${element['id_filiere']}">${element['nom_filiere']}</option>`
                            );
                        });
                    }
                });

            });
            $(document).on("change", "#id_el", function() {
                $('#id_sec').fadeIn(0);
                let idd = $(this).val();

                console.log(idd);
                $('#id_sec').empty();

                $('#id_sec').append(`<option selected value="">Section</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('getSection_by_element') }}',
                    data: {
                        id: idd
                    },
                    success: function(response) {
                        console.log(response);
                        if (response[0].section == "-") {
                            $('#id_sec').fadeOut(0);
                            $('#id_sec').empty();
                        }
                        response.forEach(element => {

                            $('#id_sec').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_cy3", function() {
                let idd = $(this).val();
                $('#id_fi3').empty();
                $('#id_fi3').append(`<option selected value="">Filiere</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('GetSubCatAgainstMainCatEdit') }}',
                    data: {
                        id: idd
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_fi3').append(
                                `<option value="${element['id_filiere']}">${element['nom_filiere']}</option>`
                            );
                        });
                    }
                });
            });

        });
    </script>

@endsection
