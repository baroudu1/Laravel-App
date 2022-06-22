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

                <form action="{{ route('exportModule.hamza') }}" method="GET" class="col-1 custum_responsive_btn">
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
                                <h6>Table des Modules</h6>
                            </div>
                            <div class="card-body px-0 pt-0">
                                <div class="table-responsive p-0">
                                    <div id="scroll1" style="overflow-x:auto;">
                                        <div style="height: 1px;"></div>
                                    </div>
                                    <div id="scroll2" style="overflow-x:auto;">
                                        <table class="table mb-0" id="select" style="width: 1550px">
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
                                                        Modules</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Elements</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Filière</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Semestre</th>
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
                </div>
                <!-- Modal file input -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Importer Fichier EXCEL</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span class="text-dark" aria-hidden="true">X</span>
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
                                        <div id="validationServer03Feedback" class="invalid-feedback mx-3">Format Incorrect.
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
                    <div class="modal-dialog modal-dialog-centered mx-auto" role="document" style="max-width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header ha_mama">
                                <h5 class="modal-title" id="ha-title"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-dark">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="ajaxnot"></div>
                                <form class="row" id="ajaxform" method="POST">
                                    <div class="form-group col-md-4">
                                        <select class="custom-select" id="id_cy" required>
                                            <option selected value="">Cycle</option>
                                            @foreach ($cycle as $c)
                                                <option value="{{ $c->id_cycle }}">{{ $c->nom_cycle }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select class="custom-select" id="id_fi">
                                            <option selected value="">Filière</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select class="custom-select" id="id_se" required>
                                            <option selected value="">Semestre</option>

                                        </select>
                                    </div>
                                    <div class="col-md-9 row mx-auto">
                                        <div class="form-group col-md-4 text-center text-uppercase">
                                            <label for="recipient-name" class="col-form-label">Nom de Module</label>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <input class="form-control" placeholder="Nom de Module" id="nom_module"
                                                required>

                                        </div>
                                    </div>
                                    <div id="itm-ajj" class="row"></div>
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mt-3 mx-auto">
                                        <div class="card card-plain border mx-auto" style="height: 50%; width: 70%;">
                                            <div class="card-body d-flex flex-column justify-content-center text-center">
                                                <a href="javascript:;" id="ha-addd">
                                                    <i class="fa fa-plus text-secondary mb-3"></i>
                                                    <span class="text-secondary"> Element </span>
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




            <!------ Modal view ------>
            <div class="modal fade" id="Modal_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mx-auto" role="document" style="max-width: 80%">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Des Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="color: black;">X</span>
                            </button>
                        </div>
                        <div class="modal-body" id="resultinfo">
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
            <!------ end Modal view ------>



            <!------ ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: ------>

        </div>
    @endsection

    @section('scriptt')
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $(document).on("change", "#id_cy", function() {
                    let idd = $(this).val();
                    $('#id_fi').empty();
                    $('#id_se').empty();

                    $('#id_fi').append(`<option selected value="">Filière</option>`);
                    $('#id_se').append(`<option selected value="">Semestre</option>`);
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
                var o;
                $("#ha-addd").click(function() {
                    o++;
                    $("#itm-ajj").append(
                        '<div class="row mx-auto ha-check" id="">' +
                        '<div class="input-group mt-3 mx-auto" style="width:94%;">' +
                        '<input  class="form-control " id="element_name' + o +
                        '" placeholder="Nom d element"  aria-describedby="button-addon1" required>' +
                        '<input type="number" min="0" max="100" class="form-control" id="co_m' + o +
                        '" placeholder="&nbsp;&nbsp;% dans module"  aria-describedby="button-addon1">' +
                        '<input type="number" min="0" max="100" class="form-control" id="co_cc' +
                        o +
                        '" placeholder="&nbsp;&nbsp;% CC"  aria-describedby="button-addon1">' +
                        '<input type="number" min="0" max="100" class="form-control" id="co_tp' +
                        o +
                        '" placeholder="&nbsp;&nbsp;% TP"  aria-describedby="button-addon1">' +
                        '<input type="number" min="0" max="100"  class="form-control" id="co_examen' +
                        o +
                        '" placeholder="&nbsp;&nbsp;% EXAM"  aria-describedby="button-addon1">' +
                        '<input type="number" min="0" max="100"  class="form-control" id="co_mini' +
                        o +
                        '" placeholder="&nbsp;&nbsp;% Mini Projet/PFE"  aria-describedby="button-addon1">' +
                        '</div>' +
                        '<a href="javascript:;" id="btnSup' + o +
                        '" style="width:1%" class="mt-4 mx-auto close_element">' +
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
                    var nom_module = $('#nom_module').val();
                    var id_cy = $('#id_cy').val();
                    var id_fi = $('#id_fi').val();
                    var id_se = $('#id_se').val();

                    var content = [];
                    var close = document.getElementsByClassName("close_element");
                    var lenghtt = document.getElementsByClassName("close_element").length;

                    for (var i = 0; i < lenghtt; i++) {
                        var data = [];
                        var element_name = $("#element_name" + close[i].id.replace('btnSup', '')).val();
                        var co_m = $("#co_m" + close[i].id.replace('btnSup', '')).val();
                        var co_examen = $("#co_examen" + close[i].id.replace('btnSup', '')).val();
                        var co_cc = $("#co_cc" + close[i].id.replace('btnSup', '')).val();
                        var co_tp = $("#co_tp" + close[i].id.replace('btnSup', '')).val();
                        var co_mini = $("#co_mini" + close[i].id.replace('btnSup', '')).val();
                        data.push(element_name);
                        data.push(co_m);
                        data.push(co_cc);
                        data.push(co_tp);
                        data.push(co_examen);
                        data.push(co_mini);
                        content.push(data);
                    }

                    var test = 1;
                    var kkk = 0;
                    var not = "";
                    var se = 0;
                    if (o == 0) {
                        kkk = 1;
                    }
                    for (var i = 0; i < o; i++) {
                        if (content[i][0] == "") {
                            test = 0;
                            not = "vous devez remplir le nom de l'element";
                        }
                        if (test) {
                            var s = 0;

                            for (var k = 1; k < 6; k++) {
                                content[i][k] = (content[i][k] != "") ? parseFloat(content[i][k]) : 0;
                                if (isNaN(content[i][k])) {
                                    test = 0;
                                    not = "les coeficient doit etre un nombre";
                                } else if (content[i][k] < 0 || content[i][k] > 100) {
                                    test = 0;
                                    not = "les coeficient doit etre entre 0 et 100";
                                } else {
                                    if (k == 1) {
                                        se += content[i][k];
                                    } else
                                        s += content[i][k];

                                }
                            }
                            console.log("O        :   " + o)
                            console.log("s        :   " + s)
                            if (test != 0 && s != 100) {
                                test = 0;
                                not = "la somme des coeficients doit etre egale à 100";
                            }
                        }
                    }
                    console.log("se        :   " + se)
                    console.log("not        :   " + kkk)
                    if (test != 0 && se != 100) {
                        test = 0;
                        if (kkk) {
                            not = "vous devez remplir le nom de l'element";
                        } else {
                            not = "la somme des coeficients doit etre egale à 100";
                        }
                    }

                    console.log($('#enre_donner').html())
                    if (test == 1) {

                        if ($('#ha-title').html() == 'Ajouter Module') {
                            $.ajax({
                                url: '{{ route('insertModule.hamza') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    nom_module: nom_module,
                                    id_fi: id_fi,
                                    id_se: id_se,
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
                                    o = 0;
                                },
                                error: function(response) {
                                    $(".ajaxnot").prepend(
                                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong>The given data was invalid. </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                    );
                                }
                            });
                        } else { //modifier rani ndman
                            var id_module = $('.ha_mama').attr('id');
                            var allVals = [];

                            $(".ha-check").each(function() {
                                allVals.push($(this).attr('id'));
                            });
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '{{ route('updateModule.hamza') }}',
                                type: "POST",

                                data: {
                                    id_elements1: allVals,
                                    id_module: id_module,
                                    nom_module: nom_module,
                                    id_fi: id_fi,
                                    id_se: id_se,
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
                    $('#ha-title').html('Ajouter Module');
                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                    o = 0;
                });
                $(document).on("click", '.ha-edit-module', function() {

                    $('#ha-addd').trigger('click');
                    var id_module = $(this).attr('id').replace("edit-", "");
                    $('.ha_mama').attr('id', id_module);
                    console.log(id_module)

                    $('#ha-title').html('Modifier Module');
                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('info_Module.hamza') }}',
                        type: "POST",

                        data: {
                            id_module: id_module
                        },
                        success: function(response) {
                            o = response.num;
                            $('#nom_module').val(response.nom_module);

                            $('#id_cy option[value=' + response.id_cy + ']').attr(
                                'selected',
                                'selected');
                            $('#id_cy').trigger('change');
                            $('#id_cy').trigger('click');
                            $('#id_fi option:selected').html(response.nom_fi);

                            $('#id_se option[value=' + response.id_se + ']').attr(
                                'selected',
                                'selected');
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
                        alert("Please select row.");
                    } else {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: '{{ route('SupprimerModule.hamza') }}',
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
                $(document).on("click", ".ha-view-module", function() {

                    let id = $(this).prop('id').replace("view-", "");
                    loadData_by_element(id);
                });
                $(document).on("click", "#ha-success", function() {

                    $('#ajaxform')[0].reset();
                    $('#itm-ajj').empty();
                    $('.ajaxnot').empty();
                });

                function loadData_by_element(id_module) {
                    $.ajax({
                        url: '{{ route('getInfoModule.hamza') }}',
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id_module: id_module,
                        },
                        success: function(response) {
                            $("#resultinfo").html(response);
                        },
                    });
                }

                loadData("");

                function loadData(coco) {
                    $.ajax({
                        url: '{{ route('getModule.hamza') }}',
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
                        url: '{{ route('importModule') }}',
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
