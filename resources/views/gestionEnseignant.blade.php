@extends('auth.app')

@section('content')
    @include('inc.nav')
    <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
        <div class="loader">
            <img src="../img/aaa.gif">
        </div>
    </div>
    <div class="mt-4 p-3">
        <input type="hidden" id="ver" value="0">
        <div class="row">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white ">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-1 custum_responsive_btn">
                <button type="button" class="btn btn-success btn-circle" id="btn-12" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class="far fa-file-excel fa-lg "></i>
                </button>
            </div>

            <form action="{{ route('exportEnseignat.hamza') }}" method="GET" class="col-1 custum_responsive_btn">
                @csrf
                <button type="submit" class="btn btn-primary btn-circle">
                    <i class="fas fa-file-download fa-lg"></i>
                </button>
            </form>

            <div class="col-1 custum_responsive_btn">
                <button id="btn-11" type="button" class="btn btn-success btn-circle" data-bs-toggle="modal"
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
                        <div class="row">
                            <h6 class="col-lg-9 col-md-6">Table des Enseignant</h6>

                            <div class="col-lg-3 col-md-6">
                                <select class="custom-select" id="ense_coor">
                                    <option selected value="">Tous Enseignant</option>
                                    <option value="1">Les Coordonnateurs</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-4">
                            <table class="table align-items-center justify-content-center mb-0 result">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--////////////////////////modale table /////////////////////-->

    <!-- Button trigger modal -->


    <!-- Modal file input -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Importer EXCEL</h5>
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





    <!-- Modal -->
    <div class="modal fade" id="exampleModalMessage" role="dialog" aria-labelledby="exampleModalMessageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header ha-edit">
                    <h5 class="modal-title" id="exampleModalLabelTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="ajaxform">
                        @csrf
                        <div class="ajaxnot"></div>
                        <div>
                            <label class="col-form-label">CIN:</label>
                            <input class="form-control" type="text" id="CIN" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <label class="col-form-label">Nom :</label>
                                    <input class="form-control" id="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="col-form-label">Prenom :</label>
                                    <input class="form-control" id="prenom" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class=" mb-2">
                                    <label class="col-form-label">Email :</label>
                                    <input class="form-control" id="email" type="email"
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                                </div>
                            </div>
                        </div>
                        <div>
                            <select class="custom-select" id="id_departement" required>
                                <option selected value="">Département</option>
                                @foreach ($departement as $d)
                                    <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-form-label col-6">Coordonnateur ?</div>
                            <div class="form-check col-3 mt-2">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="customRadio1"
                                    value="true">
                                <label class="custom-control-label" for="customRadio1">OUI</label>
                            </div>
                            <div class="form-check col-3 mt-2">
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="customRadio2"
                                    value="false" checked>
                                <label class="custom-control-label" for="customRadio2">NON</label>
                            </div>
                        </div>
                        <div class="ajoutememe"></div>
                        <div id="hide_me" style="display: none">
                            <div class="row">
                                <select class="col-6 custom-select" id="id_cy">
                                    <option selected value="">Cycle</option>
                                    @foreach ($cycle as $c)
                                        <option value="{{ $c->id_cycle }}">{{ $c->nom_cycle }}</option>
                                    @endforeach
                                </select>
                                <select class="col-6 custom-select" id="id_fi">
                                    <option selected value="">Filière</option>

                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="ha-hide1" class="btn bg-gradient-secondary"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn bg-gradient-info" id="btn_click_on_me"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade ha-mod-a" id="exampleModalMessage1" id="delet-user" role="dialog"
        aria-labelledby="exampleModalMessage1Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content ">
                <div class="modal-header mx-auto ha-help">
                    <h5 class="modal-title" id="exampleModalLabelTitle">ÊTES-VOUS SÛR ??</h5>

                </div>

                <div class="modal-footer mx-auto">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn bg-gradient-danger" data-bs-dismiss="modal"
                        id="blala">Supprimer</button>
                </div>

            </div>
        </div>
    </div>
    <input type="hidden" id="ha-show2" data-bs-toggle="modal" data-bs-target="#exampleModalMessage2">
    <div class="modal fade " id="exampleModalMessage2" role="dialog" aria-labelledby="exampleModalMessage2Title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content ">
                <div class="modal-header mx-auto text-center">
                    <h5 class="modal-title" id="ha-na"></h5>

                </div>

                <div class="modal-footer mx-auto">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn bg-gradient-warning" data-bs-dismiss="modal"
                        id="coco">Confirmer</button>
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

            $('#ense_coor').change(function() {
                if ($('#ense_coor').val() == 1) {
                    $('.ha-mam').fadeIn(0);
                    $('#hah').fadeIn(0);
                    $('.ha-mam1').fadeOut(0);
                } else {
                    $('.ha-mam').fadeOut(0);
                    $('.ha-mam1').fadeIn(0);
                    $('#hah').fadeOut(0);
                }
            });

            $('#customRadio1').click(function() {
                $('#hide_me').css("display", 'block');
                $('#customRadio2').prop('checked', false);
            });
            $('#customRadio2').click(function() {
                $('#hide_me').css("display", 'none');
                $('#customRadio1').prop('checked', false);
            });
            $('#btn-12').click(function() {
                $('.ajaxnot').empty();
            });
            $('#btn-11').click(function() {
                $('.ajaxnot').empty();
                $('#hide_me').css("display", 'none');
                $('#ajaxform')[0].reset();
                $("#btn_click_on_me").html("Ajouter");
                $("#exampleModalLabelTitle").html('Ajouter Enseignant');
                $('#customRadio2').prop('checked', true);
                $('#customRadio1').prop('checked', false);

            });
            $(document).on("click", ".show-user", function() {

                $('.ajaxnot').empty();
                $("#btn_click_on_me").html("Modifier");
                $("#exampleModalLabelTitle").html('Modifier Enseignant');

                var k = $(this).attr('id').replace("update-", "");

                $('.ha-edit').attr('id', k);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route('showelement') }}',
                    data: {
                        CIN: k
                    },
                    success: function(response) {
                        console.log(response.enseignant[0].CIN);
                        $('#CIN').val(response.enseignant[0].CIN);
                        $('#nom').val(response.enseignant[0].nom);
                        $('#prenom').val(response.enseignant[0].prenom);
                        $('#email').val(response.enseignant[0].email);


                        $('#id_departement option[value=' + response.enseignant[0]
                            .id_departement + ']').attr('selected', 'selected');
                        var b = 0;
                        if (response.coordinateur.length > 0) {
                            b = response.coordinateur[0].id_filiere;
                            $('#customRadio1').prop('checked', true);
                            $('#customRadio2').prop('checked', false);
                            $('#hide_me').css("display", 'block');
                            $('#id_cy option[value=' + response.coordinateur[0].id_cycle + ']')
                                .attr('selected', 'selected');
                            $('#id_cy').trigger('change');
                            console.log(response.coordinateur[0].id_filiere)
                            $('#id_fi option:selected').html(response.coordinateur[0]
                                .nom_filiere);
                            $('#id_fi option[value=' + response.coordinateur[0].id_filiere +
                                ']').attr('selected', 'selected');
                        } else {
                            $('#customRadio2').prop('checked', true);
                            $('#customRadio1').prop('checked', false);
                            $('#hide_me').css("display", 'none');
                        }
                        $('.ajoutememe').attr('id', b);
                    }
                });
            });
            $(document).on("submit", "#ajaxform", function(e) {
                e.preventDefault();
                $('.ajaxnot').empty();
                var coordinateur;
                if ($('#customRadio1').prop("checked") == true) {
                    coordinateur = $('#id_fi').val();
                } else {
                    coordinateur = 0;
                }
                var ver = $("#ver").val();
                var CIN = $("#CIN").val();
                var nom = $("#nom").val();
                var prenom = $("#prenom").val();
                var email = $("#email").val();
                var id_departement = $("#id_departement").val();

                if ($('#btn_click_on_me').html() == "Ajouter") {

                    var url = '{{ route('insertEnseignant.hamza') }}';

                    $.ajax({
                        url: url,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            ver: ver,
                            CIN: CIN,
                            nom: nom,
                            prenom: prenom,
                            email: email,
                            id_departement: id_departement,
                            coordinateur: coordinateur
                        },
                        success: function(response) {
                            if (response.hasuccess) {
                                $('#ha-show2').trigger('click');
                                $('#ha-hide1').trigger('click');

                                $("#ha-na").text('remplaceriez-vous ' + nom + ' ' + prenom +
                                    ' au lieu de ' + response.nom + ' ' + response.prenom +
                                    ' ??');
                            } else {
                                $('#btn-11').trigger('click');
                                $(".ajaxnot").prepend(
                                    '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong> </strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );
                                //loadData("");
                                $('#search').trigger('keyup');
                                $('#ajaxform')[0].reset();
                                $('#hide_me').css("display", 'none');
                            }
                            $("#ver").val(0);
                        },
                        error: function(response) {
                            $(".ajaxnot").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    });
                } else {
                    var CIN1 = $('.ha-edit').attr('id');
                    // alert(CIN1);
                    var url = '{{ route('UpdateEnseignant.hamza') }}';
                    kkk = $('.ajoutememe').attr('id')
                    $.ajax({
                        url: url,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            ver: ver,
                            CIN1: CIN1,
                            CIN: CIN,
                            nom: nom,
                            prenom: prenom,
                            email: email,
                            id_departement: id_departement,
                            coordinateur: coordinateur,
                            old_coordinateur: kkk
                        },
                        success: function(response) {
                            if (response.hasuccess) {
                                $('#ha-show2').trigger('click');
                                $('#ha-hide1').trigger('click');
                                $("#ha-na").text('Voulez-vous mettre ' + nom + ' ' + prenom +
                                    ' coordonnateur au lieu de ' + response.nom + ' ' + response.prenom +
                                    ' ??');
                            } else {
                                $('#update-'+CIN1).trigger('click');
                                $(".ajaxnot").prepend(
                                    '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong> </strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );
                                //loadData("");
                                $('#search').trigger('keyup');
                            }
                            $("#ver").val(0);
                        },
                        error: function(response) {
                            $(".ajaxnot").prepend(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        }
                    })
                    /////////////////
                }
            });
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


            $(document).on("click", ".supprimer_btn", function() {
                var k = $(this).attr('id').replace("delete-", "");
                $('.ha-help').attr('id', k);

            });
            $(document).on("submit", "#forme", function(e) {
                $('.ha-gif').fadeIn(200);
                e.preventDefault();
                $(".ajaxnot").empty();
                $('#validationServer03').removeClass(
                    'is-invalid');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    type: 'POST',
                    url: '{{ route('importEnseigant') }}',
                    data: new FormData(this),
                    contentType: false,
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    success: function(response) {
                        $('.ha-gif').fadeOut(200);

                        $('#close_me1').trigger('click');
                        $(".ajaxnot1").prepend(
                            '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong> </strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                        $('#L_file').html("Selectioner les fichier");
                        $('#validationServer03').removeClass(
                            'is-valid');
                        $('#ajaxform')[0].reset();
                        //loadData("");
                        $('#search').trigger('keyup');
                    },
                    error: function(response) {
                        $('.ha-gif').fadeOut(200);
                        $('#validationServer03').addClass(
                            'is-invalid');
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
            });
            $('#blala').click(function() {

                let iddd = $('.ha-help').attr('id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ route('suppEnseignant.hamza') }}',
                    data: {
                        id: iddd,
                    },
                    success: function(response) {
                        console.log(response);
                        //loadData("");
                        $('#search').trigger('keyup');
                    }
                });
            });
            $('#coco').click(function() {
                $("#ver").val(1);
                $('#ajaxform').trigger('submit');
            });
            loadData("");

            function loadData(coco) {
                $.ajax({
                    url: '{{ route('getEnseignants') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coco: coco,
                    },
                    success: function(response) {
                        $(".result").html(response);
                        if ($('#ense_coor').val() == 1) {
                            $('.ha-mam').fadeIn(0);
                            $('#hah').fadeIn(0);
                            $('.ha-mam1').fadeOut(0);
                        } else {
                            $('.ha-mam').fadeOut(0);
                            $('.ha-mam1').fadeIn(0);
                            $('#hah').fadeOut(0);
                        }

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

        });
    </script>
@endsection
