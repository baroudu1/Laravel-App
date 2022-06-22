$(document).ready(function ($) {
    $("#loader-wrapper").fadeOut(2000);
    $("#flexSwitchCheckChecked").click(function () {
        $("#hide-show").toggle(500, function () {});
        if ($("#ha-ss").hasClass("setable")) {
            $("#ha-ss").removeClass("setable");
        } else {
            $("#ha-ss").addClass("setable");
        }
    });
    $("#scroll1 div").width($("#select").width());
    $("#scroll1").on("scroll", function () {
        $("#scroll2").scrollLeft($(this).scrollLeft());
    });
    $("#scroll2").on("scroll", function () {
        $("#scroll1").scrollLeft($(this).scrollLeft());
    });
    $(document).on("focusout", "#search", function () {

        if ($(this).val().length != 0) {
            $(this).css("width", '130px');
            $(this).css("color", '#000');
            $(this).css("cursor", 'auto');
            $(this).css("background-color", '#fff');
            $(this).css("padding-left", '32px');
        } else {
            $(this).removeAttr('style');
        }

    });

    $("#btn-1").click(function () {
        $("#rts").css("display", "block");
    });
    $("#checkAll").click(function () {
        $(".check").prop("checked", this.checked);
    });

    $('.check').click(function () {

        if ($('.check:checked').length == $('.check').length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    });
});

function checkfile(sender) {
    $('#nari').remove('#validationServer03Feedback');
    $('#validationServer03').removeClass('is-invalid');
    $('#validationServer03').removeClass('is-valid');
    var validExts = new Array(".xlsx", ".xls", ".csv");
    var fileExt = sender.value;
    if (fileExt != '') {
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            $('#validationServer03').addClass('is-invalid')
        }
    }
    var x = document.getElementById("validationServer03");
    var y = document.getElementById("L_file");
    var txt = "";
    if ('files' in x) {
        if (x.files.length == 0) {
            txt = "Selectioner les fichier";
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if ('name' in file)
                    txt += file.name + " ";
            }
            $('#validationServer03').addClass('is-valid');
        }
    } else {
        if (x.value == "") {
            txt += "Selectioner les fichier";
        } else {
            txt += "The files property is not supported by your browser!";
            txt += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead.
        }
    }
    y.innerHTML = txt;
}
