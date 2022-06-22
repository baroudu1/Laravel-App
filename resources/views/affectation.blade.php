@extends('auth.app')

@section('content')
    @include('inc.nav')
    <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->
    <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
        <div class="loader">
            <img src="../img/aaa.gif">
        </div>
    </div>
    <div class="container-fluid p-4">

        <div class="row">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white ">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <input type="hidden" id="id_cy" value="{{ $poste }}">
            <div class="col-1 custum_responsive_btn">
                <button type="button" class="btn btn-success btn-circle" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class="far fa-file-excel fa-lg "></i>
                </button>
            </div>
            <form method="GET" action="{{ route('exportAffectation') }}" class="col-1 custum_responsive_btn">
                @csrf
                <button type="submit" class="btn btn-primary btn-circle">
                    <i class="fas fa-file-download fa-lg"></i>
                </button>
            </form>
            <div class="col-1 custum_responsive_btn">
                <button type="button" class="btn btn-success btn-circle ha-me" data-bs-toggle="modal"
                    data-bs-target="#exampleModalMessage">
                    <i class="fas fa-plus fa-lg"></i>
                </button>
            </div>
            <div class="col custum_responsive_btn_search">
                <form id="demo-2">
                    <input id="search" class="search2" type="search" placeholder="Search">
                </form>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Table des Affictation</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Element</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            CIN</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nom</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Prenom</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Departement
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Section</th>
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
    <!--////////////////////////modale table /////////////////////-->
    <!-- Modal -->
    <div class="modal fade ha-mod-a" id="exampleModalMessage1" id="delet-user" role="dialog"
        aria-labelledby="exampleModalMessage1Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" id="formdelete" class="modal-content ">
                <div class="modal-header mx-auto ha-help">
                    <h5 class="modal-title" id="exampleModalLabelTitle">ÊTES-VOUS SÛR ??</h5>

                </div>

                <div class="modal-footer mx-auto">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal"
                        id="close_me">Fermer</button>
                    <button type="submit" class="btn bg-gradient-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
    <!------ end Modal view ------>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header ha-helpp" id="0">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="text-dark" aria-hidden="true" class="text-dark">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="ajaxform">
                        <div class="ajaxnot"></div>
                        <div class="form-group">
                            <input type="hidden" id="anne" value="{{ $anne }}">
                            <input type="hidden" id="id_fi" value="{{ $poste }}">
                            <label for="id_el" class="col-form-label">Element:</label>
                            <input list="elements" class="form-control" placeholder="Nom d'element" id="id_el"
                                autocomplete="off" required>
                            <datalist id="elements">
                                @foreach ($element as $c)
                                    <option data-value="{{ $c->id_element }}">{{ $c->nom_element }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <select class="custom-select" id="id_dep" required>
                                <option selected value="">Departement</option>
                                @foreach ($departement as $c)
                                    <option value="{{ $c->id_departement }}">{{ $c->nom_departement }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="custom-select" id="id_en">
                                <option selected value="">Enseignant</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="custom-select" id="id_sec">
                                <option selected value="">Section</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn bg-gradient-success">ENREGISTRER</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal file input -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Importer Fichier EXCEL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">X</span>
                    </button>
                </div>
                <form method="post" id="forme">
                    <div id="formnot1"></div>
                    <div class="modal-body">
                        <div class="ajaxnot"></div>
                        <div class="custom-file" id="nari">
                            <input class="custom-file-input" multiple id="validationServer03"
                                aria-describedby="validationServer03Feedback" onchange="checkfile(this)" type="file"
                                name="excel"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required />

                            <label class="custom-file-label" id="L_file" for="customFile">Selectioner les
                                fichiers</label>
                            <div id="validationServer03Feedback" class="invalid-feedback mx-3">Incorrect format.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal"
                            id="close_me1">Fermer</button>
                        <button type="submit" class="btn bg-gradient-success">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->
@endsection
@section('scriptt')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            if ($("#id_cy").val() > 2) {
                $("#id_sec").fadeOut(200);
            } else {
                $("#id_sec").fadeIn(200);
            }

            $(document).on("change", "#id_el", function() {
                $('#id_sec').empty();
                $('#id_sec').append(`<option selected value="">Section</option>`);
                var anne = $('#anne').val();
                var id_fi = $('#id_fi').val();
                var vv = $('#id_el').val();
                var id_el = $('#elements option:contains(' + vv + ')').data('value');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('getSection1.hamza') }}',
                    data: {
                        id_el: id_el,
                        id_fi: id_fi,
                        anne: anne,
                    },
                    success: function(response) {
                        console.log(response);

                        response.forEach(element => {

                            $('#id_sec').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_dep", function() {
                let idd = $(this).val();
                $('#id_en').empty();
                $('#id_en').append(`<option selected value="">Enseignant</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('GetEnseignant_by_Dep.hamza') }}',
                    data: {
                        id: idd
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_en').append(
                                `<option value="${element['CIN']}">${element['nom']} ${element['prenom']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("click", ".ha-sup", function() {
                let kk = $(this).attr('id').replace("supp-", "");
                $(".ha-helpp").attr('id', kk);
            });
            $(document).on("submit", "#formdelete", function(e) {
                $('.ajaxnot').empty();

                e.preventDefault();
                let kk = $(".ha-helpp").attr('id');
                $.ajax({
                    url: '{{ route('SuppAffectation.hamza') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: kk,
                    },
                    success: function(data) {
                        //$('#search').trigger('keyup');
                        $('#search').trigger('keyup');
                        $('#close_me').trigger('click');
                    },
                    error: function(response) {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });
            $(document).on("click", ".ha-me", function() {
                $('.ajaxnot').empty();
                var k = $(this).prop('id').replace("edit-", "");
                if (k == "") {
                    $('#exampleModalLabel').html("Affectation");
                    $('.ha-help').attr('id', 0);
                    $('#ajaxform')[0].reset();
                    $('#id_sec option[value=""]').attr("selected", true);
                    $('#id_dep option[value=""]').attr("selected", true);
                } else {
                    $('#exampleModalLabel').html("Modifier");
                    $('.ha-help').attr('id', k);

                    $.ajax({
                        url: '{{ route('GetAffectationInfo.hamza') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: k,
                        },
                        success: function(response) {
                            $('#id_el').val(response.rst[0].nom_element);
                            $('#id_dep option[value="' + response.rst[0].id_departement + '"]')
                                .attr("selected", "selected");
                            $('#id_dep').trigger('change');
                            $('#id_el').trigger('change');
                            $('#id_en option[value="' + response.rst[0].nom + ' ' + response
                                .rst[0].prenom + '"]').attr("selected", "selected");
                            $('#id_en option:selected').html(response.rst[0].nom + ' ' +
                                response.rst[0].prenom);
                            $('#id_sec option:selected').html(response.rst[0].section);


                        },
                        error: function(response) {
                            $(".ajaxnot").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    });
                }
            });

            $(document).on("submit", "#ajaxform", function(e) {
                e.preventDefault();
                $('.ajaxnot').empty();
                let id = $('.ha-help').attr('id');
                var vv = $('#id_el').val();
                var id_el = $('#elements option:contains(' + vv + ')').data('value');
                console.log(id_el);
                var CIN = $('#id_en').val();
                var id_sec = $('#id_sec').val();

                if (id_sec == '') {
                    if ($("#id_cy").val() > 2) {
                        id_sec = "-";
                    } else {
                        id_sec = "";
                    }
                }
                $.ajax({
                    url: '{{ route('AjouterAffectation.hamza') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        id_el: id_el,
                        CIN: CIN,
                        id_sec: id_sec,
                    },
                    success: function(response) {
                        if (id == 0) {
                            $('#ajaxform')[0].reset();
                        }
                        $(".ajaxnot").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                        $('#search').trigger('keyup');
                    },
                    error: function(response) {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });

            });

            loadData("");

            function loadData(coco) {
                $.ajax({
                    url: '{{ route('GetAffectation.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coco: coco,
                    },
                    success: function(response) {
                        $(".result").html(response);
                    }
                });
            }
            $("#search").keyup(function() {
                var search = $(this).val();
                if (search != "") {
                    loadData(search);
                } else {
                    loadData("");
                }
            });
            $(document).on("submit", "#forme", function(e) {
                $('.ha-gif').fadeIn(200);
                e.preventDefault();
                $(".ajaxnot").empty();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    type: 'POST',
                    url: '{{ route('importAffectation') }}',
                    data: new FormData(this),
                    contentType: false,
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    success: function(response) {
                        $('.ha-gif').fadeOut(200);

                        $('#close_me1').trigger('click');
                        $(".ajaxnot1").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                        $('#L_file').html("Selectioner les fichier");
                        $('#validationServer03').removeClass(
                            'is-valid');
                        $('#ajaxform')[0].reset();
                        $('#search').trigger('keyup');

                    },
                    error: function(response) {
                        $('.ha-gif').fadeOut(200);

                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });

        });
    </script>
@endsection
