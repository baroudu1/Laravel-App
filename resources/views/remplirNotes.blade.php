@extends('auth.app')

@section('content')
    @include('inc.nav')
    <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->
    <div id="loader-wrapper" class="ha-gif" style="display: none;opacity:0.5">
        <div class="loader">
            <img src="../img/aaa.gif">
        </div>
    </div>
    <div class="container-fluid py-4 col-12 ">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-text text-white ">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card p-4 m-0">

            <form method="POST" id="slm_siba_cv">
                <div class="row">
                    <div class="col-md-5 mb-2 mx-auto">
                        <div class="form-group">
                            <select class="custom-select" id="id_el" required>
                                <option selected value="">Elements/Modules</option>
                                @foreach ($element as $c)
                                    <option value="{{ $c->id_element }}">
                                        {{ $c->nom_element }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mx-auto">
                        <div class="form-group">
                            <select class="custom-select" id="id_sec" required>
                                <option selected value="">Section</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="submit" class="col-12 btn bg-gradient-info">suivant</button>
                    </div>
                </div>
            </form>
            <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->
            <div id="rts" class="mt-4" style="display: none;">
                <div class="ajaxnot" id="slm"></div>
                <div class="mt-3 mb-4" style="background-color: gray; height: 3px;"></div>
                <div class="row">

                    <form action="{{ route('exportNotes.hamza') }}" method="POST" class="col-1 custum_responsive_btn">
                        @csrf
                        <input type="hidden" name="id_element" id="id_el1">
                        <input type="hidden" name="section" id="id_sec1">
                        <button type="submit" class="btn btn-primary btn-circle">
                            <i class="fas fa-file-download fa-lg"></i>
                        </button>
                    </form>
                    <div class="col-1 custum_responsive_btn ha-bbbtn">
                        <button type="button" class="btn btn-success btn-circle ha-naa" data-bs-toggle="modal"
                            data-bs-target="#exampleModalMessage1">
                            <i class="far fa-check fa-lg "></i>
                        </button>
                    </div>
                    <div class="col">
                        <form id="demo-2">
                            <input id="search" class="search2" type="search" placeholder="Search">
                        </form>
                    </div>
                    <div class=" col-xl-3 col-md-3 mb-xl-0 text-right ha-bbbtn">
                        <button type="submit" id="btn-1" class=" col-12 btn bg-gradient-success ">Enregestrer &nbsp; &nbsp;
                            <i class="fal fa-save fa-lg"></i></button>
                    </div>
                    <div class="card-header pb-0">
                        <h6>Table des Notes</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0 result">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--////////////////////////modale table /////////////////////-->


    <!-- ::::::::::::::::::::: table ::::::::::::::::::::::::::::::::: -->
    <!-- Modal -->
    <div class="modal fade ha-mod-a" id="exampleModalMessage1" id="delet-user" role="dialog"
        aria-labelledby="exampleModalMessage1Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content ">
                <div class="modal-header mx-auto ha-help">
                    <h5 class="modal-title" id="exampleModalLabelTitle">ÊTES-VOUS SÛR ??</h5>

                </div>

                <div class="modal-footer mx-auto">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal"
                        id="close_me">Fermer</button>
                    <button type="submit" class="btn bg-gradient-success" id="blala">Confirmer</button>
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
            $('.ha-bbbtn').fadeOut(0);
            $(document).on("change", "#id_el", function() {
                $('#slm').empty();
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


            function loadData(coco) {
                let id_el = $('#id_el').val();
                let id_sec = $('#id_sec').val();
                $(".ha-gif").fadeIn(200);
                $.ajax({
                    url: '{{ route('fetchNotes.hamza') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coco: coco,
                        id_el: id_el,
                        id_sec: id_sec,
                    },
                    success: function(response) {

                        $(".result").html(response);
                        var access = $(".ha_access").attr('id');
                        if (access == 1 || access == 4) {
                            $('.ha-bbbtn').fadeIn(0);
                        } else {
                            $('.ha-bbbtn').fadeOut(0);
                        }
                        $(".ha-gif").fadeOut(200);
                    }
                });
            }
            $(document).on("change", "select", function() {
                $("#rts").css("display", "none");
            });

            $(document).on("submit", "#slm_siba_cv", function(e) {
                e.preventDefault();
                $("#rts").fadeIn(2000);
                loadData("");
                $('#id_el1').val($('#id_el').val());
                $('#id_sec1').val($('#id_sec').val());
            });
            $("#search").keyup(function() {
                var search = $(this).val();
                if (search != "") {
                    loadData(search);
                } else {
                    loadData("");
                }
            });

            $('#btn-1').on('click', function(e) {
                $('#slm').empty();
                var access = $(".ha_access").attr('id');
                if (access == 1 || access == 4) {

                    var content = [];
                    var close = document.getElementsByClassName("close_element");
                    var lenghtt = document.getElementsByClassName("close_element").length;
                    var test = 1;
                    var not = "";
                    for (var i = 0; i < lenghtt; i++) {
                        var data = [];
                        $("#tp-" + close[i].id).removeClass('is-invalid');
                        $("#ctr-" + close[i].id).removeClass('is-invalid');
                        $("#minip-" + close[i].id).removeClass('is-invalid');
                        $("#exn-" + close[i].id).removeClass('is-invalid');
                        $("#exrat-" + close[i].id).removeClass('is-invalid');
                        data.push(close[i].id);
                        if ($("#tp-" + close[i].id).val() === undefined) {
                            data.push(null);
                        } else {
                            data.push($("#tp-" + close[i].id).val());
                        }
                        if ($("#ctr-" + close[i].id).val() === undefined) {
                            data.push(null);
                        } else {
                            data.push($("#ctr-" + close[i].id).val());
                        }
                        if ($("#minip-" + close[i].id).val() === undefined) {
                            data.push(null);
                        } else {
                            data.push($("#minip-" + close[i].id).val());
                        }
                        if ($("#exn-" + close[i].id).val() === undefined) {
                            data.push(null);
                        } else {
                            data.push($("#exn-" + close[i].id).val());
                        }
                        if ($("#exrat-" + close[i].id).val() === undefined) {
                            data.push(null);
                        } else {
                            data.push($("#exrat-" + close[i].id).val());
                        }
                        content.push(data);
                        console.log(content);
                        for (var k = 1; k < 6; k++) {
                            if (!isNaN(content[i][k])) {
                                if (content[i][k] == "") {
                                    content[i][k] = null;
                                } else {
                                    content[i][k] = parseFloat(content[i][k]);
                                    if (isNaN(content[i][k])) {
                                        content[i][k] = null;
                                    }
                                    if (content[i][k] > 20 || content[i][k] < 0) {
                                        test = 0;
                                        not = 'Les nombres doivent etre entre 0 et 20';
                                        if (k == 1) {
                                            $("#tp-" + close[i].id).addClass('is-invalid');
                                        }
                                        if (k == 2) {
                                            $("#ctr-" + close[i].id).addClass('is-invalid');
                                        }
                                        if (k == 3) {
                                            $("#minip-" + close[i].id).addClass('is-invalid');
                                        }
                                        if (k == 4) {
                                            $("#exn-" + close[i].id).addClass('is-invalid');
                                        }
                                        if (k == 5) {
                                            $("#exrat-" + close[i].id).addClass('is-invalid');
                                        }
                                    }
                                }
                            } else if (content[i][k] == "abs" || content[i][k] == "ABS" || content[i][k] ==
                                "Abs" || content[i][k] == "ABs") {
                                content[i][k] = -1;
                            } else {
                                test = 0;
                                not = 'Accepter seulement nombres (ou ABS)';
                                if (k == 1) {
                                    $("#tp-" + close[i].id).addClass('is-invalid');
                                }
                                if (k == 2) {
                                    $("#ctr-" + close[i].id).addClass('is-invalid');
                                }
                                if (k == 3) {
                                    $("#minip-" + close[i].id).addClass('is-invalid');
                                }
                                if (k == 4) {
                                    $("#exn-" + close[i].id).addClass('is-invalid');
                                }
                                if (k == 5) {
                                    $("#exrat-" + close[i].id).addClass('is-invalid');
                                }
                            }
                        }
                    }
                    console.log(content);
                    if (!test) {
                        $(".ajaxnot").prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text">' +
                            not +
                            ' </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                    } else {
                        $.ajax({
                            url: '{{ route('updateNotes.hamza') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                lenghtt: lenghtt,
                                content: content,
                            },
                            success: function(data) {
                                $('#search').trigger('keyup');

                                $(".ajaxnot").prepend(
                                    '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> Les Donnes sont Enregistrer</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );
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
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"><strong></strong> You are Not Allowed to do this Opiration :/</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                    );
                }
            });
            $('.ha-naa').click(function() {
                $('#search').val("");
                $('#btn-1').trigger('click');

            });
            $('#blala').click(function() {
                let id_el = $('#id_el').val();
                let id_sec = $('#id_sec').val();
                $('#close_me').trigger('click');
                var test = 1;
                var access = $(".ha_access").attr('id');
                if (access == 4) {
                    $('.ha-dis input[type=text]').each(function() {
                        if ($(this).val() == '') {
                            test = 0;
                            $(this).addClass('is-invalid');
                        }
                    });
                } else {
                    $('.ha-wlwo input[type=text]').each(function() {
                        if ($(this).val() == '') {
                            test = 0;
                            $(this).addClass('is-invalid');
                        }
                    });
                }
                if (test) {
                    //alert($("#slm").html().search('alert-danger'));
                    if ($("#slm").html().search('alert-danger') == -1) {
                        $.ajax({
                            url: '{{ route('modifierStatut.hamza') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id_el: id_el,
                                id_sec: id_sec,
                            },
                            success: function(data) {
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
                    $("#slm").empty();
                    $(".ajaxnot").prepend(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><span class="alert-text"> Remplir les Notes D\'abord </span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                    );
                }

            });


        });
    </script>
@endsection
