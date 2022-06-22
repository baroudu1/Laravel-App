@extends('auth.app')

@section('content')
    @include('inc.nav')
    <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
        <div class="loader">
            <img src="../img/aaa.gif">
        </div>
    </div>
    <div class="container-fluid" style="overflow-x: hidden;">
        <div class="card card-body blur shadow-blur mx-4">
            <div class="row">
                <div class="mx-auto">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white ">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active hahahahah" data-bs-toggle="tab" href="#pill1"
                                    role="tab" aria-controls="overview" aria-selected="true">
                                    <span class="ms-1">Générer / Modifier liste</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pill2" role="tab"
                                    aria-controls="teams" aria-selected="false">
                                    <span class="ms-1">Importer liste</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid col-lg-12" style="margin-top: 20px;">
        <div class="card">
            <div class="row">
                <div class="mx-auto">
                    <div class="position-relative end-0">
                        <div class="tab-content tab-space">
                            <div class="tab-pane active" id="pill1">
                                <div class="container-fluid py-4">

                                    <div class="row">
                                        <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->

                                        <div>
                                            <div class="p-3">
                                                <form method="POST" id="formme">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group ha-haha">
                                                                <select class="custom-select" id="annee" required>
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
                                                            <div class="form-group ha-haha">
                                                                <select class="custom-select" id="id_cy1" name="id_cy1"
                                                                    required>
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
                                                            <div class="form-group ha-haha">
                                                                <select class="custom-select" id="id_fi1" required>
                                                                    <option selected value="">Filière</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group ha-haha">
                                                                <select class="custom-select" id="id_se" required>
                                                                    <option selected value="">Semestre</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group ha-haha" name="id_sec11">
                                                                <select class="custom-select" id="id_sec">
                                                                    <option selected value="">Section</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit"
                                                            class="btn bg-gradient-success col-12 col-xl-3 col-md-4 mb-xl-0">suivant</button>
                                                    </div>
                                                </form>
                                                <div id="rts" class="mt-4" style="display: none;">
                                                    <div class="mt-3 mb-4" style="background-color: gray; height: 3px;">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-1 custum_responsive_btn_2">
                                                            <form action="{{ route('exportEtudients.hamza') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" id="anana" name="anne">
                                                                <input type="hidden" id="anana1" name="id_fi">
                                                                <input type="hidden" id="anana2" name="id_se">
                                                                <input type="hidden" id="anana3" name="id_sec">
                                                                <button type="submit" class="btn btn-primary btn-circle"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalMessage2">
                                                                    <i class="far fa-file-excel fa-lg "></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="col-1 custum_responsive_btn_2">
                                                            <button type="button" class="btn btn-success btn-circle ha-edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalMessage">
                                                                <i class="fas fa-plus fa-lg"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-1 custum_responsive_btn_2">
                                                            <button type="button" class="btn bg-gradient-danger btn-circle"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalMessage1">
                                                                <i class="fas fa-minus fa-lg"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col custum_responsive_btn_search">
                                                            <form id="demo-2">
                                                                <input class="search2" id="search" type="search"
                                                                    placeholder="Search">
                                                            </form>
                                                        </div>
                                                        <div class="card-header pb-0">
                                                            <h6>Projects table</h6>
                                                        </div>
                                                        <div class="card-body px-0 pt-0 pb-2">
                                                            <div class="table-responsive p-0">
                                                                <table
                                                                    class="table align-items-center justify-content-center mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th
                                                                                class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                                                                <div class="form-check text-left">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" value=""
                                                                                        id="checkAll">
                                                                                    <label
                                                                                        class="form-check-label custom-control-label"
                                                                                        for="checkAll"><b>Tous</b></label>
                                                                                </div>
                                                                            </th>
                                                                            <th
                                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                                CNE </th>
                                                                            <th
                                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                                Nom </th>
                                                                            <th
                                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                                Prenom </th>
                                                                            <th
                                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                                Nbr des Modules</th>

                                                                            <th></th>
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
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane " id="pill2">
                                <div class="container-fluid ">

                                    <div class="p-3 mt-4">

                                        <form id="ajaxform" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="ajaxnot" id="ajaxnot"></div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <select class="custom-select" id="id_cy" name="id_cy" required>
                                                            <option selected value="">Cycle</option>
                                                            @foreach ($cycle as $c)
                                                                <option value="{{ $c->id_cycle }}">{{ $c->nom_cycle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <select class="custom-select" id="id_fi" name="id_fi" required>
                                                            <option selected value="">Filière</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <div class="form-group">
                                                        <div class="custom-file" id="nari">
                                                            <input class="custom-file-input" multiple
                                                                id="validationServer03" name="file[]"
                                                                aria-describedby="validationServer03Feedback"
                                                                onchange="checkfile(this)" type="file"
                                                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                                required />
                                                            <label class="custom-file-label" id="L_file"
                                                                for="customFile">Selectioner les
                                                                fichiers</label>
                                                            <div id="validationServer03Feedback"
                                                                class="invalid-feedback mx-3">format Incorrect.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <input id="btn_submit" type="submit"
                                                    class="btn bg-gradient-success col-12 col-xl-3 col-md-4 mb-xl-0 "
                                                    value="Importer">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div id="idid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal view -->
    <div class="modal fade" id="exampleModalview" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" class="mol-md-12">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Des Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Modules </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="result1">
                                </tbody>
                            </table>
</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header ha-help">
                    <h5 class="modal-title" id="exampleModalLabel11"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">X</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="POST" id="form_insert_update">
                        <div id="ajaxnot1" class="ajaxnot1"></div>
                        <div class="form-group">
                            <label for="CNE">CNE :</label>
                            <input class="form-control" id="CNE" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom :</label>
                                    <input class="form-control" id="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prenom :</label>
                                    <input class="form-control" id="prenom" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="custom-select" id="id_sec1">
                                <option selected value="">Section</option>
                            </select>
                        </div>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Modules</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="resultt2">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn bg-gradient-info">ENREGISTRER</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>

    <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->

    <!-- Modal -->
    <div class="modal fade ha-mod-a" id="exampleModalMessage1" id="delet-user" role="dialog"
        aria-labelledby="exampleModalMessage1Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content ">
                <div class="modal-header mx-auto ha-help">
                    <h5 class="modal-title" id="exampleModalLabelTitle">ÊTES-VOUS SÛR ??</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-footer mx-auto">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal"
                        id="close_me">Fermer</button>
                    <button type="submit" class="btn bg-gradient-danger" data-bs-dismiss="modal"
                        id="blala">Supprimer</button>
                </div>

            </div>
        </div>
    </div>
    <!------ end Modal view ------>
@endsection



@section('scriptt')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("change", "#id_cy", function() {

                let idd = $(this).val();

                $('#id_fi').empty();

                $('#id_fi').append(`<option selected value="">Filière</option>`);
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
            $(document).on("submit", "#form_insert_update", function(e) {
                e.preventDefault();
                var allVals = [];
                $('#ajaxnot1').empty();
                var id_se = $('#id_se').val();
                var id_sec = $('#id_sec').val();
                var id_sec1 = $('#id_sec1').val();
                var id_fi = $('#id_fi1').val();
                var anne = $('#annee').val();
                var id = $('.ha-help').attr('id');
                var CNE = $("#CNE").val();
                var nom = $("#nom").val();
                var prenom = $("#prenom").val();
                var id_ni = "";
                if (id_se < 2) {
                    id_ni = 1;
                } else if (id_se < 5) {
                    id_ni = 2;
                } else if (id_se < 7) {
                    id_ni = 3;
                }
                if (id_sec1 == '') {
                    if (id_fi > 2) {
                        id_sec1 = "-";
                    } else {
                        id_sec1 = "";
                    }
                }
                if (id_sec == '') {
                    if (id_fi > 2) {
                        id_sec = "-";
                    } else {
                        id_sec = "";
                    }
                }
                $(".checkcheck:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });
                if (allVals.length <= 0) {
                    alert("Please select Module.");
                } else {
                    var join_selected_values = allVals.join(",");

                    $.ajax({
                        url: '{{ route('insert_update_etudiant.hamza') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            modules: join_selected_values,
                            CNE: CNE,
                            nom: nom,
                            prenom: prenom,
                            annee: anne,
                            id_ni: id_ni,
                            id_se: id_se,
                            id_sec: id_sec,
                            id_sec1: id_sec1,
                            id_fi: id_fi,
                        },
                        success: function(response) {
                            if (id == 1) {
                                $('#form_insert_update')[0].reset();
                            }
                            $('#search').trigger('keyup');
                            $("#ajaxnot1").prepend(
                                '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong> </strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        },
                        error: function(response) {
                            $("#ajaxnot1").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    });
                }
            });
            $(document).on("click", ".ha-edit", function() {
                $('#ajaxnot1').empty();
                if ($(this).prop('id') == "") {
                    $('#exampleModalLabel11').html("Ajouter Etudiant");
                    $('.ha-help').attr('id', 1);
                    $('#CNE').prop('disabled', false);
                } else {
                    $('#exampleModalLabel11').html("Modifier Etudiant");
                    $('.ha-help').attr('id', 0);
                    $('#CNE').prop('disabled', true);

                }
                let CNE = $(this).prop('id').replace('edit-', '');
                var anne = $("#annee").val();
                var id_fi = $("#id_fi1").val();
                var id_se = $("#id_se").val();
                var id_sec = $("#id_sec").val();
                $("#id_sec1").val(id_sec);
                $.ajax({
                    url: '{{ route('fetchInfoEtudiant.hamza') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        CNE: CNE,
                        anne: anne,
                        id_fi: id_fi,
                        id_se: id_se,
                    },
                    success: function(response) {
                        $('.resultt2').empty();
                        $("#CNE").val(response.CNE);
                        $("#nom").val(response.nom);
                        $("#prenom").val(response.prenom);
                        $('.resultt2').prepend(response.output);
                    },
                    error: function(response) {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });

            });
            $(document).on("click", ".ha-view", function() {
                let CNE = $(this).attr('id').replace("view-", "");
                var id_se = $('#id_se').val();
                var anne = $('#annee').val();
                $.ajax({
                    url: '{{ route('getInfoEtudiant.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        anne: anne,
                        id_se: id_se,
                        CNE: CNE,
                    },
                    success: function(response) {
                        $(".result1").html(response);
                    }
                });

            });
            $(document).on("change", "#id_se", function() {

                let id_se = $(this).val();
                console.log(id_se)
                var id_ni = "";

                if (id_se < 3) {
                    id_ni = 1;
                } else if (id_se < 5) {
                    id_ni = 2;
                }
                var id_fi = $('#id_fi1').val();
                var anne = $('#annee').val();
                $('#id_sec').empty();
                $('#id_sec').append(`<option selected value="">Section</option>`);
                $('#id_sec1').empty();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('getSection') }}',
                    data: {
                        anne: anne,
                        id_se: id_se,
                        id_ni: id_ni,
                        id_fi: id_fi,
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_sec').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                            $('#id_sec1').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_cy1", function() {
                let idd = $(this).val();
                if (idd >= 2) {
                    $("#id_sec").fadeOut(200);
                    $("#id_sec1").fadeOut(200);
                } else {
                    $("#id_sec").fadeIn(200);
                    $("#id_sec1").fadeIn(200);
                }
                $('#id_fi1').empty();
                $("#id_se").empty();
                $('#id_se').append(`<option selected value="">Semestre</option>`);
                $('#id_fi1').append(`<option selected value="">Filière</option>`);
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
                            $('#id_fi1').append(
                                `<option value="${element['id_filiere']}">${element['nom_filiere']}</option>`
                            );
                        });
                    }
                });
                var cycle = $(this).val();
                if (cycle == 1) {

                    var i;
                    for (i = 1; i <= 4; i++) {
                        $("#id_se").append(new Option('S' + i, i));
                    }
                } else if (cycle == 2) {
                    var i;
                    for (i = 5; i <= 6; i++) {
                        $("#id_se").append(new Option('S' + i, i));
                    }
                } else if (cycle == 3) {
                    var i;
                    for (i = 7; i <= 10; i++) {
                        $("#id_se").append(new Option('S' + i, i));
                    }
                } else if (cycle == 4) {
                    var i;
                    for (i = 5; i <= 10; i++) {
                        $("#id_se").append(new Option('S' + i, i));
                    }
                }
            });
            $(document).on("change", ".ha-haha select", function(e) {
                $('#rts').css('display', 'none');
            });

            $(document).on("submit", "#formme", function(e) {
                e.preventDefault();
                var anne = $("#annee").val();
                var id_fi = $("#id_fi1").val();
                var id_se = $("#id_se").val();
                var id_sec = $("#id_sec").val();
                if (id_sec == '') {
                    if (id_fi > 2) {
                        id_sec = "-";
                    } else {
                        id_sec = "";
                    }
                }
                loadData("", anne, id_fi, id_se, id_sec);
                $('#rts').fadeIn(2000);;
                ////////////////////
                $("#anana").val(anne);
                $("#anana1").val(id_fi);
                $("#anana2").val(id_se);
                $("#anana3").val(id_sec);
            });
            $(document).on("submit", "#ajaxform", function(e) {
                $('.ha-gif').fadeIn(200);
                e.preventDefault();
                $(".ajaxnot").empty();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    type: 'POST',
                    url: '{{ route('upload.hamza') }}',
                    data: new FormData(this),
                    contentType: false,
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    success: function(response) {
                        $('.ha-gif').fadeOut(200);

                        if (response.success) {

                            $(".ajaxnot").prepend(
                                '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong> </strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                            $('#L_file').html("Selectioner les fichier");
                            $('#validationServer03').removeClass(
                                'is-valid');
                            $('#ajaxform')[0].reset();
                            $("#annee").empty();
                            $('#annee').prepend(`<option selected value="">Année</option>`);
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                type: 'post',
                                url: '{{ route('GetAnnne.hamza') }}',
                                data: {},
                                success: function(response) {
                                    console.log(response);
                                    response.forEach(element => {
                                        $('#annee').append(
                                            `<option value="${element['annee']}">${element['annee']}</option>`
                                        );
                                    });
                                }
                            });
                        } else {
                            $(".ajaxnot").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>' +
                                response.message +
                                ' </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    },
                    error: function(response) {
                        $('.ha-gif').fadeOut(200);
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });

            });


            function loadData(coco, anne, id_fi, id_se, id_sec) {
                $.ajax({
                    url: '{{ route('getEtudiant.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coco: coco,
                        anne: anne,
                        id_fi: id_fi,
                        id_se: id_se,
                        id_sec: id_sec,
                    },
                    success: function(response) {
                        $(".result").html(response);
                    },
                    error: function(response) {
                        $("#ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            }
            $(document).on("change", "#id_fi1", function() {
                $("#id_se option[value='']").remove();
                $('#id_se').prepend(`<option selected value="">Semestre</option>`);
            });
            $("#search").keyup(function() {
                var search = $(this).val();
                var anne = $("#annee").val();
                var id_fi = $("#id_fi1").val();
                var id_se = $("#id_se").val();
                var id_sec = $("#id_sec").val();
                if (id_sec == '') {
                    if (id_fi > 2) {
                        id_sec = "-";
                    } else {
                        id_sec = "";
                    }
                }
                if (search != "") {
                    loadData(search, anne, id_fi, id_se, id_sec);
                } else {
                    loadData("", anne, id_fi, id_se, id_sec);
                }
            });

            $('#blala').on('click', function(e) {
                var anne = $("#annee").val();
                var id_fi = $("#id_fi1").val();
                var id_se = $("#id_se").val();
                var id_sec = $("#id_sec").val();
                if (id_sec == '') {
                    if (id_fi > 2) {
                        id_sec = "-";
                    } else {
                        id_sec = "";
                    }
                }
                var allVals = [];
                $(".check:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });
                if (allVals.length > 0) {

                    var join_selected_values = allVals.join(",");
                    $.ajax({
                        url: '{{ route('SupprimerEtudiant.hamza') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: join_selected_values,
                        },
                        success: function(data) {
                            if (data['success']) {
                                $('#close_me').trigger('click');
                                $('#search').trigger('keyup');
                                //alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function(response) {
                            $(".ajaxnot").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    });
                }
            });



        });
    </script>
@endsection
