@extends('auth.app')

@section('content')
    @include('inc.nav')
    <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
        <div class="loader">
            <img src="../img/aaa.gif">
        </div>
    </div>
    <div style="overflow-x: hidden">

        <div class="row p-4">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white ">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-12 mx-auto mt-4 row ">
                <div class="col-1 custum_responsive_btn_2">
                    <button type="button" class="btn btn-success btn-circle" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="far fa-file-excel fa-lg "></i>
                    </button>
                </div>

                <form action="{{ route('exportFiliere.hamza') }}" method="GET" class="col-1 custum_responsive_btn">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-circle">
                        <i class="fas fa-file-download fa-lg"></i>
                    </button>
                </form>
                <div class="col-1 custum_responsive_btn_2 ">
                    <button type="button" class="btn btn-success btn-circle" id="ha-success" data-bs-toggle="modal"
                        data-bs-target="#exampleModalMessage">
                        <i class="fas fa-plus fa-lg"></i>
                    </button>
                </div>

                <div class="col-1 custum_responsive_btn_2">
                    <button type="button" class="btn bg-gradient-danger btn-circle" data-bs-toggle="modal"
                        data-bs-target="#exampleModalMessage1">
                        <i class="fas fa-minus fa-lg"></i>
                    </button>
                </div>

                <div class="col custum_responsive_btn_search">
                    <div id="demo-2">

                        <input id="search" class="search2" type="search" placeholder="Search">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Table de Filières</h6>
                            </div>
                            <div class="card-body px-0 pt-0">
                                <div class="table-responsive p-0">

                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary opacity-7"
                                                    style="max-width:120px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="checkAll">
                                                        <label class="form-check-label" for="checkAll"><b>Tous</b></label>
                                                    </div>
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Cycle</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Filière</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Seuil</th>
                                                <th class="text-secondary opacity-7"></th>
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
                                            aria-describedby="validationServer03Feedback" onchange="checkfile(this)"
                                            type="file" name="excel"
                                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            required />

                                        <label class="custom-file-label" id="L_file" for="customFile">Selectioner les
                                            fichiers</label>
                                        <div id="validationServer03Feedback" class="invalid-feedback mx-3">Incorrect
                                            format.
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

                <!------ Modal edit or add ------>
                <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered  mx-auto" role="document" style="max-width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header ha_mama">
                                <h5 class="modal-title" id="ha-title"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" style="color: black;">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="ajaxnot"></div>
                                <form class="row" id="ajaxform" method="POST">

                                    <div class="col-md-6 row mx-auto">
                                        <div class="form-group col-md-4 text-center text-uppercase">
                                            <label for="recipient-name" class="col-form-label">Nom de Cycle</label>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <input class="form-control" placeholder="Nom de Cycle" id="nom_cy" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 row mx-auto">
                                        <div class="form-group col-md-4 text-center text-uppercase "
                                            style="margin-top: -13px">
                                            <label for="recipient-name" class="col-form-label">Seuil de Validation</label>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <input type="number" min="0" max="100" placeholder="Seuil de Validation"
                                                value="10" class="form-control" id="seuil_v" required>
                                        </div>
                                    </div>
                                    <div id="itm-ajj" class="row"></div>
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mt-3 mx-auto">
                                        <div class="card card-plain border mx-auto" style="height: 50%; width: 70%;">
                                            <div class="card-body d-flex flex-column justify-content-center text-center">
                                                <a href="javascript:;" id="ha-addd">
                                                    <i class="fa fa-plus text-secondary mb-3"></i>
                                                    <span class="text-secondary">Filière</span>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn bg-gradient-info"
                                            id="enre_donner">ENREGISTRER</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!------ end Modal edit or add ------>





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
            <!------ end Modal view ------>
            <!------ ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: ------>

        </div>
    @endsection

    @section('scriptt')
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var o;
                $("#ha-addd").click(function() {
                    o++;
                    $("#itm-ajj").append(
                        '<div class="col-md-7 mx-auto row mb-3 ha-check" id="">' +
                        '<input class="form-control" style="width:94%" placeholder="Nom de Filiere" id="nom_fi' +
                        o +
                        '" required>' +
                        '<a href="javascript:;" id="btnSup' + o +
                        '" style="width:1%" class="mt-2 mx-auto close_element">' +
                        '<i class="fas fa-minus-circle text-danger fa-lg"></i>' +
                        '</a>' +
                        '</div>'
                    );
                    var close = document.getElementsByClassName("close_element");
                    var i;
                    for (i = 0; i < close.length; i++) {
                        close[i].onclick = function() {
                            var div = this.parentElement;
                            div.remove();
                            o--;
                        }
                    }
                });
                $(document).on("submit", "#ajaxform", function(e) {
                    e.preventDefault();
                    $('.ajaxnot').empty();
                    let nom_cy = $('#nom_cy').val();
                    let seuil_v = $('#seuil_v').val();
                    var close = document.getElementsByClassName("close_element");
                    var lenghtt = document.getElementsByClassName("close_element").length;
                    var content = [];
                    for (var i = 0; i < lenghtt; i++) {
                        var nom_fi = $("#nom_fi" + close[i].id.replace('btnSup', '')).val();
                        content.push(nom_fi);
                    }
                    var test = 1;
                    var kkk = 0;
                    var not = "";
                    if (o == 0) {
                        kkk = 1;
                        test = 0;
                    }
                    if (kkk) {
                        not = "vous devez remplir une Filiere";
                    }
                    console.log($('#enre_donner').html())
                    if (test == 1) {
                        if ($('#ha-title').html() == 'Ajouter Cycle') {
                            $.ajax({
                                url: '{{ route('insertFiliere.hamza') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    nom_cy: nom_cy,
                                    seuil_v: seuil_v,
                                    nbr_ele: o,
                                    content: content
                                },
                                success: function(data) {
                                    if (data['errors']) {
                                        alert(data['errors']);
                                    }
                                    $('#search').trigger('keyup');
                                    $(".ajaxnot").prepend(
                                        '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                    );
                                    $('#ajaxform')[0].reset();
                                    $('#itm-ajj').empty();
                                },
                                error: function(response) {
                                    $(".ajaxnot").prepend(
                                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                    );
                                }
                            });
                        } else { //modifier rani ndman
                            var id_cycle = $('.ha_mama').attr('id');
                            var allVals = [];

                            $(".ha-check").each(function() {
                                allVals.push($(this).attr('id'));
                            });
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '{{ route('updateCycle.hamza') }}',
                                type: "POST",

                                data: {
                                    id_elements1: allVals,
                                    id_cycle: id_cycle,
                                    nom_cy: nom_cy,
                                    seuil_v: seuil_v,
                                    nbr_ele: o,
                                    content: content
                                },
                                success: function(response) {
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
                        }
                    } else {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>&nbsp;&nbsp;' +
                            not +
                            ' </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    }
                });
                $('#ha-success').click(function() {
                    $('#ha-title').html('Ajouter Cycle');
                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                    o = 0;
                });
                $(document).on("click", '.ha-edit-cycle', function() {

                    $('#ha-addd').trigger('click');
                    var id_cycle = $(this).attr('id').replace("edit-", "");
                    $('.ha_mama').attr('id', id_cycle);
                    console.log(id_cycle)

                    $('#ha-title').html('Modifier Cycle');
                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('info_Cycle.hamza') }}',
                        type: "POST",

                        data: {
                            id_cycle: id_cycle
                        },
                        success: function(response) {
                            o = response.num;
                            $('#nom_cy').val(response.nom_cy);
                            $('#seuil_v').val(response.seuil_v);
                            $('#itm-ajj').prepend(response.output);
                            var close = document.getElementsByClassName("close_element");
                            var i;
                            for (i = 0; i < close.length; i++) {
                                close[i].onclick = function() {
                                    var div = this.parentElement;
                                    div.remove();
                                    o--;
                                }
                            }
                        }
                    });
                });
                $('#blala').on('click', function(e) {
                    var allVals = [];
                    $(".check:checked").each(function() {
                        allVals.push($(this).attr('data-id'));
                    });
                    if (allVals.length <= 0) {
                        //alert("Please select row.");
                    } else {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: '{{ route('SupprimerCycle.hamza') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: join_selected_values,
                            },
                            success: function(data) {
                                if (data['success']) {
                                    $('#search').trigger('keyup');
                                    //alert(data['success']);
                                } else if (data['error']) {
                                    //alert(data['error']);
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

                $(document).on("click", "#ha-success", function() {
                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                });
                loadData("");

                function loadData(coco) {
                    $.ajax({
                        url: '{{ route('getCycle.hamza') }}',
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
                        url: '{{ route('importFiliere') }}',
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
