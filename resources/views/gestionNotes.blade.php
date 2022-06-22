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
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#pill1" role="tab"
                                    aria-controls="overview" aria-selected="true">
                                    <span class="ms-1">Consultation des Notes</span>
                                </a>
                            </li>
                            <li class="nav-item ha-llink">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pill2" role="tab"
                                    aria-controls="teams" aria-selected="false">
                                    <span class="ms-1">Validation des Notes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pill3" role="tab"
                                    aria-controls="dashboard" aria-selected="false">
                                    <span class="ms-1">Fermer / Ouvrir Service des Notes</span>
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
                            <div class="tab-pane active p-3" id="pill1">
                                <form method="POST" id="ajaxform">
                                    <div class="row p-3">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
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
                                            <div class="form-group">
                                                <select class="custom-select" id="id_cy" name="id_cy" required>
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
                                                    <option selected value="">Filière</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_el" required>
                                                    <option selected value="">Elements</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_sec" required>
                                                    <option selected value="">Section</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2"></div>
                                        <div class="form-check text-left col-xl-9 col-md-8 mb-xl-0 mb-4">
                                            <input class="form-check-input" type="checkbox" value="" id="checkk"
                                                style="z-index: 10;left: 5px; top: -2px;">
                                            <label class="form-check-label custom-control-label" for="checkk"><b>Etudiants
                                                    sont
                                                    pas Notes</b></label>
                                        </div>

                                        <div class="col-xl-3 col-md-4 mb-xl-0 text-right">
                                            <button type="submit" class="col-12 btn bg-gradient-success">Consulter</button>
                                        </div>
                                    </div>
                                </form>

                                <div id="rts" class="mt-6" style="display: none;">
                                    <div class="mt-3 mb-3" style="background-color: gray; height: 3px;"></div>
                                    <div class="card-header mt-4">
                                        <h6 style="display: inline-block;">Table des Notes</h6>
                                        <form id="demo-2" style="float: right;">
                                            <input id="search" class="search2" type="search" placeholder="Search">
                                        </form>
                                    </div>
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="table-responsive p-0">
                                            <table class="table align-items-center justify-content-center mb-0 result">

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane p-4" id="pill2">
                                <div class="p-2">
                                    <div class="row">
                                        <div class="mt-2">
                                            <div class="ajaxnot" id="ajaxnot"></div>
                                            <div class="card-header pb-0 px-3">
                                                <h6 class="mb-0">Les Notes Signées</h6>
                                            </div>
                                            <div class="card-body pt-4 p-3">
                                                <ul class="list-group result1">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-5" id="pill3">
                                <form id="formme">
                                    <div class="row p-4">
                                        <div class="ajaxnot1"></div>
                                        <input type="hidden" class="annee1" id="{{ $anne1 }}">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_cy1" name="id_cy1" required>
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
                                                <select class="custom-select" id="id_fi1" required>
                                                    <option selected value="">Filière</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_el1" required>
                                                    <option selected value="">Elements</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <select class="custom-select" id="id_sec1" required>
                                                    <option selected value="">Section</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-check text-left col-xl-9 col-md-8 mb-xl-0 mb-4">
                                            <input class="form-check-input" type="checkbox" value="" id="checkk1"
                                                style="z-index: 10;left: 5px; top: -2px;">
                                            <label class="form-check-label custom-control-label" for="checkk1">
                                                <b>Pour Rattrapage</b>
                                            </label>
                                        </div>
                                        <div class="col-md-6 mb-2"></div>
                                    </div>
                                    <button type="button"
                                        class="btn bg-gradient-success col-12 col-xl-3 col-md-4 mb-xl-0 ha-blk"
                                        id="hamza-1">Débloquer</button>
                                    <button type="button" id="hamza-0"
                                        class="btn bg-gradient-danger col-12 col-xl-3 col-md-4 mb-xl-0 ha-blk"
                                        style="float: right;">Bloquer</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------ Modal view ------>
    <div class="modal fade" id="Modal_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mx-auto" role="document" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Des détails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: black;">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center justify-content-center mb-0 result2">

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
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



            $(document).on("change", "#id_cy", function() {
                let idd = $(this).val();
                if (idd >= 2) {
                    $("#id_sec").fadeOut(200);
                } else {
                    $("#id_sec").fadeIn(200);
                }
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
            $(document).on("change", "#id_cy1", function() {
                let idd = $(this).val();
                if (idd >= 2) {
                    $("#id_sec1").fadeOut(200);
                } else {
                    $("#id_sec1").fadeIn(200);
                }
                $('#id_fi1').empty();

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
            });
            $(document).on("change", "#id_el", function() {
                let id_el = $(this).val();
                let anne = $('#annee').val();
                $('#id_sec').empty();
                $('#id_sec').append(`<option selected value="">Section</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('sectionn.hamza') }}',
                    data: {
                        id_el: id_el,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            if (response[0].section == "-") {
                                $('#id_sec').fadeOut(0);
                                $('#id_sec').empty();
                            }
                            $('#id_sec').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_el1", function() {
                let id_el = $(this).val();
                let anne = $('.annee1').attr('id');
                $('#id_sec1').empty();
                $('#id_sec1').append(`<option selected value="">Section</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('sectionn.hamza') }}',
                    data: {
                        id_el: id_el,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            if (response[0].section == "-") {
                                $('#id_sec1').fadeOut(0);
                                $('#id_sec1').empty();
                            }
                            $('#id_sec1').append(
                                `<option value="${element['section']}">${element['section']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("click", ".ha-llink", function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('getrequests.hamza') }}',
                    data: {

                    },
                    success: function(response) {
                        console.log(response);
                        $(".result1").html(response);

                    }
                });
            });
            $(document).on("change", "#id_fi", function() {
                let id_fi = $(this).val();
                let anne = $('#annee').val();
                $('#id_el').empty();
                $('#id_el').append(`<option selected value="">Elements</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('element.hamza') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_el').append(
                                `<option value="${element['id_element']}">${element['nom_element']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("change", "#id_fi1", function() {
                let id_fi = $(this).val();
                let anne = $('.annee1').attr('id');
                $('#id_el1').empty();
                $('#id_el1').append(`<option selected value="">Elements</option>`);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('element.hamza') }}',
                    data: {
                        id_fi: id_fi,
                        anne: anne
                    },
                    success: function(response) {
                        console.log(response);
                        response.forEach(element => {
                            $('#id_el1').append(
                                `<option value="${element['id_element']}">${element['nom_element']}</option>`
                            );
                        });
                    }
                });
            });
            $(document).on("click", ".ha-view", function() {
                $('.ha-gif').fadeIn(200);
                var id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('get_note.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        $(".result2").html(response);
                        $('.ha-gif').fadeOut(200);
                    }
                });
            });
            $(document).on("click", ".ha-valider", function() {
                $(".ajaxnot").empty();
                var id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('modifierStatuts.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        $('.ha-llink').trigger('click');
                        $("#ajaxnot").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    },
                    error: function(response) {
                        $("#ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });
            $(document).on("click", ".ha-refuser", function() {
                $(".ajaxnot").empty();
                var id = $(this).attr('id').replace("ha-","");
                $.ajax({
                    url: '{{ route('modifierStatutsst.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        $('.ha-llink').trigger('click');
                        $("#ajaxnot").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    },
                    error: function(response) {
                        $("#ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });
            $(document).on("click", ".ha-blk", function(e) {
                $(".ajaxnot1").empty();
                var id = $(this).attr('id').replace('hamza-', '');
                let anne = $('.annee1').attr('id');
                let id_fi = $('#id_fi1').val();
                let id_el = $('#id_el1').val();
                let id_sec = $('#id_sec1').val();
                var check = 0;
                if ($('input[id="checkk1"]').is(':checked')) {
                    check = 1;
                }
                $.ajax({
                    url: '{{ route('modifierStatutee.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        check: check,
                        anne: anne,
                        id_el: id_el,
                        id_fi: id_fi,
                        id_sec: id_sec,
                    },
                    success: function(response) {
                        $('.ha-llink').trigger('click');
                        $(".ajaxnot1").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                        $("#formme")[0].reset();
                    },
                    error: function(response) {
                        $(".ajaxnot1").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });
            $(document).on("change", "select", function() {
                $("#rts").fadeOut(0);
            });
            $(document).on("change", 'input[id="checkk"]', function() {
                $("#rts").fadeOut(0);
            });
            $(document).on("submit", "#ajaxform", function(e) {
                $("#rts").fadeIn(1000);
                e.preventDefault();
                loadData("");
            });
            $("#search").keyup(function() {
                var search = $(this).val();
                if (search != "") {
                    loadData(search);
                } else {
                    loadData("");
                }
            });

            function loadData(coco) {
                var annee = $('#annee').val();
                var id_el = $('#id_el').val();
                var id_sec = $('#id_sec').val();
                var check = 0;
                if ($('input[id="checkk"]').is(':checked')) {
                    check = 1;
                }
                $.ajax({
                    url: '{{ route('get_notes.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coco: coco,
                        annee: annee,
                        id_el: id_el,
                        check: check,
                        id_sec: id_sec,
                    },
                    success: function(response) {
                        $(".result").html(response);
                    }
                });
            }
        });
    </script>
@endsection
