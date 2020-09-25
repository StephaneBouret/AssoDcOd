$(document).ready(function () {

    $("#searchBar").dropdown({
        maxSelections: 1
    });
    $("#btnSearch").on("click", function () {
        $.post(
            "search.php", {
                search: $("#searchBar").val()
            },
            function (data) {
                $("#list").html("");
                $("#list").append(data);
            });
    });

    $(function () {
        function _confirm() {
            return (confirm('Voulez-vous supprimer l\'adhérent ?'));
        }
        $('#closeMember').click(_confirm);
    });

    $(function () {
        function _confirm() {
            return (confirm('Voulez-vous supprimer l\'utilisateur ?'));
        }
        $('#deleteButton').click(_confirm);
    });

    $(function () {
        //INIT
        $('.msg-error').hide();
        var password = $("[name=password]");
        var check = $('.check-password');

        //VALIDATION AUTRES CHAMPS FORMULAIRE
        var test1, test2, test3, test4, test5;
        test1 = test2 = test3 = test4 = test5 = false;
        $("#nom, #prenom").on("blur", testTexte);

        function testTexte() {
            var champ = $(this).val();
            if (champ == "") {
                $("#msg").fadeIn();
                $(this).next('label').css('color', '#c11a1a');
                $(this).siblings('.focus-border').removeClass('input-success');
                $(this).siblings('.focus-border').addClass('input-error');
                $(this).siblings('.icon-register').css('color', '#c11a1a');
                if ($(this).attr('id') == "nom") {
                    test1 = false;
                } else if ($(this).attr('id') == "prenom") {
                    test2 = false;
                }

            } else {
                $("#msg").fadeOut();
                $(this).next('label').css('color', '#a6d342');
                $(this).siblings('.focus-border').addClass('input-success');
                $(this).siblings('.icon-register').css('color', '#6fcb2e');
                if ($(this).attr('id') == "nom") {
                    test1 = true;
                } else if ($(this).attr('id') == "prenom") {
                    test2 = true;
                }
            }
        }
        $("#email, #login, #loginForget").on("blur", function () {
            var champMail = $(this).val();
            if (!champMail.match(/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/)) {
                $("#msg").fadeIn();
                $(this).next('label').css('color', '#c11a1a');
                $(this).siblings('.focus-border').removeClass('input-success');
                $(this).siblings('.focus-border').addClass('input-error');
                $(this).siblings('.icon-register').css('color', '#c11a1a');
                test3 = false;
            } else {
                $("#msg").fadeOut();
                $(this).next('label').css('color', '#a6d342');
                $(this).siblings('.focus-border').addClass('input-success');
                $(this).siblings('.icon-register').css('color', '#6fcb2e');
                test3 = true;
            }
        });

        //show password
        $('.icon-eye-hide').on('click', function () {
            $('.icon-eye-hide').toggleClass('icon-eye');
            if (password.attr('type') === "password") {
                password.attr('type', 'text');
                check.attr('type', 'text');
            } else {
                password.attr('type', 'password');
                check.attr('type', 'password');
            }
        });

        // Gestion des input
        $(".input-effect input").focusout(function () {

            if ($(this).val() != "") {
                $(this).addClass("has-content");
            } else {
                $(this).removeClass("has-content");
            }
        });

        //GESTION DU PASSWORD
        //On check si les 2 passwords sont les memes
        $('input[name="password_confirm"]').keyup(function () {
            if (password.val() !== check.val()) {
                $(this).popover('show');
                $(this).next('label').css('color', '#c11a1a');
                $(this).siblings('.focus-border').removeClass('input-success');
                $(this).siblings('.focus-border').addClass('input-error');
                $(this).siblings('.icon-register').css('color', '#c11a1a');
                test4 = false;
            }
            if (password.val() === check.val()) {
                $(this).popover('hide');
                $(this).next('label').css('color', '#a6d342');
                $(this).siblings('.focus-border').addClass('input-success');
                $(this).siblings('.icon-register').css('color', '#6fcb2e');
                test4 = true;
            }
        });

        var progress = 0;

        function checkPassword() {
            var progress = 0;
            // set password variable
            var pswd = $('input[name="password"]').val();
            //validate the length
            if (pswd.length >= 8) {
                progress = progress + 20;
                $('#length').removeClass('invalid').addClass('valid');
            } else {
                $('#length').removeClass('valid').addClass('invalid');
            }
            //Validate lower character
            if (pswd.match(/[a-z]/)) {
                progress = progress + 20;
                $('#letter').removeClass('invalid').addClass('valid');
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }
            //Validate special character
            if (pswd.match(/[!@#$%^&*(),.?":{}|<>]/)) {
                progress = progress + 20;
                $('#special').removeClass('invalid').addClass('valid');
            } else {
                $('#special').removeClass('valid').addClass('invalid');
            }
            //validate capital letter
            if (pswd.match(/[A-Z]/)) {
                progress = progress + 20;
                $('#capital').removeClass('invalid').addClass('valid');
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }
            //validate number
            if (pswd.match(/\d/)) {
                progress = progress + 20;
                $('#number').removeClass('invalid').addClass('valid');
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }
            $('.progress-bar-password').attr('aria-valuenow', progress).css('width', progress + '%');
            if (progress === 100) {
                $('.password-explanation').removeClass('msg-error');
                $('.pswd_info').addClass('d-none');
                $('.submit').prop('disabled', false).removeClass('disabled');
                $('input#password').next('label').css('color', '#a6d342');
                $('input#password').siblings('.focus-border').addClass('input-success');
                $('input#password').siblings('.icon-register').css('color', '#6fcb2e');
            } else {
                $('.submit').prop('disabled', true).addClass('disabled');
            }
            return progress;
        }

        //On vérifie que le password remplit bien les conditions (sinon message explicatif)
        progress = $('input[name="password"]').keyup(function () {
            checkPassword()
        }).focus(function () {
            $('.pswd_info').removeClass('d-none');
            test5 = false;
        }).blur(function () {
            $('.pswd_info').addClass('d-none');
            test5 = true;
            return progress;
        });

        // Evenement au click du bouton Créer un compte
        $("#send").on("click", function (event) {
            var champEmailRegister = $("input#email").val().trim();
            var champPasswordRegister = $("input#password").val().trim();
            var champPasswordConfirmRegister = $("input#password_confirm").val().trim();

            if (testChampEmailRegister(champEmailRegister) == false) {
                nonValideSend("input#email");
            } else {
                valideSend("input#email");
            }
            if (testChampVidePassword(champPasswordRegister) == false) {
                nonValideSend("input#password");
            } else {
                valideSend("input#password");
            }
            if (testChampVidePassword(champPasswordConfirmRegister) == false) {
                nonValideSend("input#password_confirm");
            } else {
                valideSend("input#password_confirm");
            }

            if (testChampEmailRegister(champEmailRegister) == false || testChampVidePassword(champPasswordRegister) == false ||
                testChampVidePassword(champPasswordConfirmRegister) == false) {
                event.preventDefault();
            } else {
                $('#send').addClass('bt-pink-success');
                return true;
            }
        });

        // Evenement lors du remplissage du mot de passe pour la connexion
        $("#passwordLogin").on("blur", function () {
            var champPasswordLogin = $(this).val();
            if (champPasswordLogin == "") {
                $(this).next('label').css('color', '#c11a1a');
                $(this).siblings('.focus-border').removeClass('input-success');
                $(this).siblings('.focus-border').addClass('input-error');
                $(this).siblings('.icon-register').css('color', '#c11a1a');
            } else {
                $(this).next('label').css('color', '#a6d342');
                $(this).siblings('.focus-border').addClass('input-success');
                $(this).siblings('.icon-register').css('color', '#6fcb2e');
            }
        });
        // Evenement au click du bouton se connecter
        $("#sendLogin").on("click", function (event) {
            var champEmailLogin = $("input#login").val().trim();
            var champPasswordLogin = $("input#passwordLogin").val().trim();

            if (testChampEmailRegister(champEmailLogin) == false) {
                nonValideSend("input#login");
            } else {
                valideSend("input#login");
            }
            if (testChampVidePassword(champPasswordLogin) == false) {
                nonValideSend("input#passwordLogin");
            } else {
                valideSend("input#passwordLogin");
            }

            if (testChampEmailRegister(champEmailLogin) == false || testChampVidePassword(champPasswordLogin) == false) {
                event.preventDefault();
            } else {
                $('#sendLogin').addClass('bt-pink-success');
                return true;
            }
        });

        // Evenement au click du bouton vérifier email (oubli mdp)
        $("#sendForget").on("click", function (event) {
            var champEmailLoginForget = $("input#loginForget").val().trim();

            if (testChampEmailRegister(champEmailLoginForget) == false) {
                nonValideSend("input#loginForget");
            } else {
                valideSend("input#loginForget");
            }

            if (testChampEmailRegister(champEmailLoginForget) == false) {
                event.preventDefault();
            } else {
                $('#sendForget').addClass('bt-pink-success');
                return true;
            }
        });

        // Evenement au click du bouton réinitialiser le mot de passe
        $("#sendReset").on("click", function (event) {
            var champPasswordReset = $("input#password").val().trim();
            var champPasswordConfirmReset = $("input#password_confirm").val().trim();

            if (testChampVidePassword(champPasswordReset) == false) {
                nonValideSend("input#password");
            } else {
                valideSend("input#password");
            }
            if (testChampVidePassword(champPasswordConfirmReset) == false) {
                nonValideSend("input#password_confirm");
            } else {
                valideSend("input#password_confirm");
            }

            if (testChampVidePassword(champPasswordReset) == false || testChampVidePassword(champPasswordConfirmReset) == false) {
                event.preventDefault();
            } else {
                $('#sendReset').addClass('bt-pink-success');
                return true;
            }
        });

        function testChampEmailRegister(value) {
            if (!value.match(/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/)) {
                return false;
            }
        }

        function testChampVidePassword(value) {
            if (value == "") {
                return false;
            }
        }

        function nonValideSend(selector) {
            $(selector).next('label').css('color', '#c11a1a') + $(selector).siblings('.focus-border').removeClass('input-success') +
                $(selector).siblings('.focus-border').addClass('input-error') + $(selector).siblings('.icon-register').css('color', '#c11a1a');
        }

        function valideSend(selector) {
            $(selector).next('label').css('color', '#a6d342') + $(selector).siblings('.focus-border').addClass('input-success') +
                $(selector).siblings('.icon-register').css('color', '#6fcb2e');
        }
    });

    // Reset adress button
    $("#adressResetButton").on("click", function () {
        $('#adressAutoInput').fadeIn();
        $('#adressInput').fadeOut();
        $('#zipInput').fadeOut();
        $('#townInput').fadeOut();
        $("#form-address").val("");
        $("#adresse").val("");
        $("#codepostal").val("");
        $("#ville").val("");
    });

    // Saisie adresse manuelle
    $('#addressNotFound').click(function () {
        $('#adressAutoInput').fadeOut();
        $('#adressInput').fadeIn();
        $('#zipInput').fadeIn();
        $('#townInput').fadeIn();
    });

    $('#form-address').focus(function () {
        $('#adressInput').fadeIn();
        $('#zipInput').fadeIn();
        $('#townInput').fadeIn();
    })

    // Evenement au chargement de la photo (contrôle du type d'image)
    var fileInput = $(".form-control-file"),
        button = $(".input-file-trigger"),
        the_return = $(".file-return"),
        photoTemplate = $("#photoTemplate");
    fileInput.on("change", function () {
        // var file = $(this).val();
        var file = this.files[0],
            fileName = file.name,
            fileType = file.type,
            fileSize = file.size;
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/tif", "image/png"];
        var maxSize = 2000000; // fichier doit être inférieur à 2mb
        if (($.inArray(fileType, validImageTypes) < 0) || (fileSize > maxSize)) {
            button.html('Uniquement du type jpeg, png ou tif, taille max à 2Mo');
            button.addClass('input-trigger-error');
            photoTemplate.attr("src", "img/undefined.jpg");
        } else {
            button.html('Image prête à être mise en ligne');
            button.removeClass('input-trigger-error');
            button.addClass('input-trigger-success');
            photoTemplate.attr("src", "img/" + fileName);
        }
        the_return.html(fileName);
        // button.html('Image prête à être mise en ligne');
        // button.addClass('input-trigger-success');
        // photoTemplate.attr("src", "img/" + fileName);
    })

    $(".button-file").click(function () {
        $("#photo").click();
    })

    // Evenement au chargement du fichier
    var fileInput = $("#file"),
        button = $(".input-file-trigger"),
        the_return = $(".file-return");
    fileInput.on("change", function () {
        // var file = $(this).val();
        var file = this.files[0],
            fileName = file.name,
            fileSize = file.size;
        var maxSize = 2000000;
        if (fileSize > maxSize) {
            button.html('La taille est supérieure à 2Mo');
            button.addClass('input-trigger-error');
        } else {
            button.html('Fichier prêt à être mis en ligne');
            button.removeClass('input-trigger-error');
            button.addClass('input-trigger-success');
        }
        the_return.html(fileName);
        // button.html('Fichier prêt à être mis en ligne');
        // button.addClass('input-trigger-success');
    })

    $(".button-file").click(function () {
        $("#file").click();
    })

    // Test du formulaire de création de l'adhérent - test des input
    $("#formAdherent input#nom, #formAdherent input#prenom, #formAdherent input#dateEntree, #formAdherent input#dateSortie, #formAdherent input#dateRenewal, #formAdherent select#fonction, #formAdherent select#statut, #formAdherent select#cotisation, #formAdherent select#reglement, #formAdherent select#groupAdherent").on("blur", testInputTypeTextAndSelect);

    function testInputTypeTextAndSelect() {
        var champInput = $(this).val();
        if (champInput == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).removeClass("green-border");
            $(this).next('span').fadeIn();
        } else {
            $(this).addClass("green-border");
            $(this).next('span').hide();
        }
    }

    $("#formAdherent input#email").on("blur", testInputTypeMail);

    function testInputTypeMail() {
        var champMail = $(this).val();
        var regexMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!champMail.match(regexMail)) {
            $(this).css('border-color', '#c11a1a');
            $(this).removeClass("green-border");
            $(this).next('span').fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next('span').hide();
        }
    }

    $("#formAdherent input#tel").on("blur", testInputTypeTel);

    function testInputTypeTel() {
        var champTel = $(this).val();
        var regexPhone = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!champTel.match(regexPhone)) {
            $(this).css('border-color', '#c11a1a');
            $(this).removeClass("green-border");
            $(this).next('span').fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next('span').hide();
        }
    }
    // Evenement au click du bouton soumission
    $("#submitMember").on("click", function (event) {
        var champInputName = $("#formAdherent input#nom").val().trim();
        var champInputSurname = $("#formAdherent input#prenom").val().trim();
        var selectDateEntree = $("#formAdherent input#dateEntree").val().trim();
        var selectFonction = $("#formAdherent select#fonction").val().trim();
        var selectStatut = $("#formAdherent select#statut").val().trim();
        var selectCotisation = $("#formAdherent select#cotisation").val().trim();
        var selectReglement = $("#formAdherent select#reglement").val().trim();
        var champInputEmail = $("#formAdherent input#email").val().trim();
        var champInputTel = $("#formAdherent input#tel").val().trim();

        if (testChampsVide(champInputName) == false) {
            nonValide("#formAdherent input#nom");
        } else {
            valide("#formAdherent input#nom");
        }
        if (testChampsVide(champInputSurname) == false) {
            nonValide("#formAdherent input#prenom");
        } else {
            valide("#formAdherent input#prenom");
        }
        if (testChampsVide(selectDateEntree) == false) {
            nonValide("#formAdherent input#dateEntree");
        } else {
            valide("#formAdherent input#dateEntree");
        }
        if (testChampsVide(selectFonction) == false) {
            nonValide("#formAdherent select#fonction");
        } else {
            valide("#formAdherent select#fonction");
        }
        if (testChampsVide(selectStatut) == false) {
            nonValide("#formAdherent select#statut");
        } else {
            valide("#formAdherent select#statut");
        }
        if (testChampsVide(selectCotisation) == false) {
            nonValide("#formAdherent select#cotisation");
        } else {
            valide("#formAdherent select#cotisation");
        }
        if (testChampsVide(selectReglement) == false) {
            nonValide("#formAdherent select#reglement");
        } else {
            valide("#formAdherent select#reglement");
        }
        if (testIfEmailIsValid(champInputEmail) == false) {
            nonValide("#formAdherent input#email");
        } else {
            valide("#formAdherent input#email");
        }
        if (testIfTelIsValid(champInputTel) == false) {
            nonValide("#formAdherent input#tel");
        } else {
            valide("#formAdherent input#tel");
        }

        if (testChampsVide(champInputName) == false || testChampsVide(champInputSurname) == false ||
            testChampsVide(selectDateEntree) == false || testChampsVide(selectFonction) == false || testChampsVide(selectStatut) == false ||
            testChampsVide(selectCotisation) == false || testChampsVide(selectReglement) == false || testIfEmailIsValid(champInputEmail) == false ||
            testIfTelIsValid(champInputTel) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    $("#submitSwitchToUser").on("click", function (event) {
        var selectDateSortie = $("#formAdherent input#dateSortie").val().trim();
        var selectGroupAdherent = $("#formAdherent select#groupAdherent").val().trim();

        if (testChampsVide(selectDateSortie) == false) {
            nonValide("#formAdherent input#dateSortie");
        } else {
            valide("#formAdherent input#dateSortie");
        }
        if (testChampsVide(selectGroupAdherent) == false) {
            nonValide("#formAdherent select#groupAdherent");
        } else {
            valide("#formAdherent select#groupAdherent");
        }

        if (testChampsVide(selectDateSortie) == false || testChampsVide(selectGroupAdherent) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    $("#submitSwitchToMember").on("click", function (event) {
        var selectDateEntree = $("#formAdherent input#dateEntree").val().trim();
        var selectFonction = $("#formAdherent select#fonction").val().trim();
        var selectStatut = $("#formAdherent select#statut").val().trim();
        var selectCotisation = $("#formAdherent select#cotisation").val().trim();
        var selectReglement = $("#formAdherent select#reglement").val().trim();

        if (testChampsVide(selectDateEntree) == false) {
            nonValide("#formAdherent input#dateEntree");
        } else {
            valide("#formAdherent input#dateEntree");
        }
        if (testChampsVide(selectFonction) == false) {
            nonValide("#formAdherent select#fonction");
        } else {
            valide("#formAdherent select#fonction");
        }
        if (testChampsVide(selectStatut) == false) {
            nonValide("#formAdherent select#statut");
        } else {
            valide("#formAdherent select#statut");
        }
        if (testChampsVide(selectCotisation) == false) {
            nonValide("#formAdherent select#cotisation");
        } else {
            valide("#formAdherent select#cotisation");
        }
        if (testChampsVide(selectReglement) == false) {
            nonValide("#formAdherent select#reglement");
        } else {
            valide("#formAdherent select#reglement");
        }

        if (testChampsVide(selectDateEntree) == false || testChampsVide(selectFonction) == false || testChampsVide(selectStatut) == false ||
            testChampsVide(selectCotisation) == false || testChampsVide(selectReglement) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    $("#submitUser").on("click", function (event) {
        var champInputName = $("#formAdherent input#nom").val().trim();
        var champInputSurname = $("#formAdherent input#prenom").val().trim();
        var selectCotisation = $("#formAdherent select#cotisation").val().trim();
        var champInputEmail = $("#formAdherent input#email").val().trim();
        var champInputTel = $("#formAdherent input#tel").val().trim();

        if (testChampsVide(champInputName) == false) {
            nonValide("#formAdherent input#nom");
        } else {
            valide("#formAdherent input#nom");
        }
        if (testChampsVide(champInputSurname) == false) {
            nonValide("#formAdherent input#prenom");
        } else {
            valide("#formAdherent input#prenom");
        }
        if (testChampsVide(selectCotisation) == false) {
            nonValide("#formAdherent select#cotisation");
        } else {
            valide("#formAdherent select#cotisation");
        }
        if (testIfEmailIsValid(champInputEmail) == false) {
            nonValide("#formAdherent input#email");
        } else {
            valide("#formAdherent input#email");
        }
        if (testIfTelIsValid(champInputTel) == false) {
            nonValide("#formAdherent input#tel");
        } else {
            valide("#formAdherent input#tel");
        }

        if (testChampsVide(champInputName) == false || testChampsVide(champInputSurname) == false ||
            testChampsVide(selectCotisation) == false || testIfEmailIsValid(champInputEmail) == false ||
            testIfTelIsValid(champInputTel) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampsVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testIfEmailIsValid(value) {
        var regexEmailIsValid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!value.match(regexEmailIsValid)) {
            return false;
        }
    }

    function testIfTelIsValid(value) {
        var regexTelIsValid = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!value.match(regexTelIsValid)) {
            return false;
        }
    }

    function nonValide(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valide(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    $("#formAdherent select#sexe, input#adresse, input#codepostal, input#ville").change(function () {
        var value = $(this).val();
        if (value == "") {
            $(this).removeClass("green-border");
            $(this).focus();
        } else {
            $(this).addClass("green-border");
        }
    });
    $("select#sel_degrees").change(function () {
        var value = $(this).val();
        if (value == "") {
            $("#degreesSelection .select2-container--default .select2-selection--single").removeClass("green-border");
            $("#degreesSelection .select2-container--default .select2-selection--single").focus();
        } else {
            $("#degreesSelection .select2-container--default .select2-selection--single").addClass("green-border");
        }
    });
    $("select#sel_jobs").change(function () {
        var value = $(this).val();
        if (value == "") {
            $("#jobsSelection .select2-container--default .select2-selection--single").removeClass("green-border");
            $("#jobsSelection .select2-container--default .select2-selection--single").focus();
        } else {
            $("#jobsSelection .select2-container--default .select2-selection--single").addClass("green-border");
        }
    });

    // Vider la table
    $('#clearBDD').click(() => {
        var resp = confirm("Voulez-vous supprimer l'association ?");
        if (resp) {
            window.location = "index.php?controller=association&action=clearBdd";
        }
    });

    // Collapse Sidebar
    $('#hide-menu').click(function (e) {
        e.preventDefault();
        $("body").animate({
            "transition": "all"
        }, 200, "linear", function () {
            $("body").toggleClass("hidden-menu");
        });
    });
    $('#open-menu').click(function (e) {
        e.preventDefault();
        $("body").animate({
            "transition": "all"
        }, 200, "linear", function () {
            $("body").removeClass("hidden-menu");
        });
    });
    $('#siteCache').click(function (e) {
        e.preventDefault();
        $("body").animate({
            "transition": "all"
        }, 200, "linear", function () {
            $("body").toggleClass("hidden-menu");
        });
    })

    // Display panel with css
    $(document).on('click', '#principal .onglets', function () {
        $(this).parent().attr('data-active', $(this).attr('data-onglet'));
    });

    //Show detail adherent
    $('.buttonMenu .hide-on-menu').click(function () {
        $('.reveal-on-click').slideToggle('slow');
    });

    //Hide detail adherent
    $(document).on('click', '*', function (e) {
        var target = e.target;
        if ($(target).parents('.reveal-on-click').length <= 0 && !$(target).hasClass('reveal-on-hover')) {
            $('.reveal-on-click').slideUp('fast');
        }
    });

    // Initialize Select2
    $('#sel_degrees').select2();
    $("#sel_jobs").select2({

        ajax: {
            url: "index.php?controller=adherent&action=ajaxAutocompletionJobs",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        },
        placeholder: 'Chercher un poste',
        minimumInputLength: 1,
    });
    $("#sel_members").select2({

        ajax: {
            url: "index.php?controller=adherent&action=ajaxAutocompletionMembers",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        },
        placeholder: 'Rechercher un utilisateur',
        minimumInputLength: 1,
    });
    $("#sel_docs").select2({

        ajax: {
            url: "index.php?controller=document&action=ajaxAutocompletionDocs",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {

                return {
                    results: response
                };
            },
            cache: true
        },
        placeholder: 'Rechercher un document',
        minimumInputLength: 1,
    });
    $("#sel_juridical").select2({

        ajax: {
            url: "index.php?controller=association&action=ajaxAutocompletionJuridicalStatus",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {

                return {
                    results: response
                };
            },
            cache: true
        },
        placeholder: 'Rechercher un statut',
        minimumInputLength: 1,
    });
    $("#sel_boardMember").select2({

        ajax: {
            url: "index.php?controller=association&action=ajaxAutocompletionBoardMember",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
        placeholder: 'Taper "Président", "Trésorier"...',
        minimumInputLength: 1,
        templateSelection: formatRepoSelection
    });

    function formatRepoSelection(repo) {
        $("#boardMemberFirstname").val(repo.firstname);
        $("#boardMemberName").val(repo.name);
        $("#boardMemberFonction").val(repo.function);
        return repo.text;
    }
    $("#sel_MemberNameForDonation").select2({
        ajax: {
            url: "index.php?controller=dons&action=ajaxAutocompletionMemberNameForDonation",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
        placeholder: 'Taper les premières lettres du nom',
        minimumInputLength: 1,
        templateSelection: formatRepoDonator
    });

    function formatRepoDonator(repo) {
        $("#memberForDonationFirstName").val(repo.firstname);
        $("#memberForDonationName").val(repo.name);
        $("#memberForDonationEmail").val(repo.email);
        return repo.text;
    }

    $("#sel_MemberNameForGroup").select2({
        ajax: {
            url: "index.php?controller=group&action=ajaxAutocompletionMemberNameForGroup",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
        placeholder: 'Taper les premières lettres du nom',
        minimumInputLength: 1,
        templateSelection: formatRepoGroup
    });

    function formatRepoGroup(repo) {
        $("#representantPrenomGroupe").val(repo.firstname);
        $("#representantNomGroupe").val(repo.name);
        $("#representantEmailGroup").val(repo.email);
        return repo.text;
    }

    $("#MemberForGroupResetButton").on("click", function () {
        $('#searchMemberForGroup').removeAttr('hidden');
        $('#MemberLeaderGroupSelectedForChange').hide();
        $('#MemberSelectedLeaderGroupForChange').val("");
        $('#sel_MemberNameForGroup').val('').trigger('change');
        $(".row.groupUserByName").css('display', 'none');
        $("#searchIfNotFoundForGroup").removeAttr('hidden');
    });

    // Affichage des trois inputs (template formParamsFile) lors du select on change
    $("#sel_boardMember").change(function () {
        $('#boardFirstName').fadeIn();
        $('#boardName').fadeIn();
        $('#boardFunction').fadeIn();
    });

    // Reset adress button template formParamsFile
    $("#boardMemberResetButton").on("click", function () {
        $('#searchBoardMember').removeAttr('hidden');
        $('#boardReal').hide();
        $('#sel_boardMember').val('').trigger('change');
        $('#boardFirstName').fadeOut();
        $('#boardName').fadeOut();
        $('#boardFunction').fadeOut();
        // $("#sel_boardMember").val(null);
        $("#boardFirstName").val("");
        $("#boardName").val("");
        $("#boardFunction").val("");
    });

    // Test du formulaire de création et paramétrage de l'association - test des input
    $("#formParams input#AssoName, #formParams input#nomGroupe, #formParams input#representantPrenomGroupe, #formParams input#representantNomGroupe").on("blur", testAssoName);

    function testAssoName() {
        var assoName = $(this).val();
        if (assoName == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }
    $("#formParams input#email, #formParams input#emailGroupe").on("blur", testEmail);

    function testEmail() {
        var email = $(this).val();
        var regexEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(regexEmail)) {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }
    $("#formParams input#tel, #formParams input#telGroup").on("blur", testTel);

    function testTel() {
        var tel = $(this).val();
        var regexTel = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!tel.match(regexTel)) {
            $(this).css('border-color', '#c11a1a');
            $('.errorMsgTelCreateAsso').fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $('.errorMsgTelCreateAsso').hide();
        }
    }
    $("#formParams input#linkedin, #formParams input#twitter, #formParams input#facebook, #formParams input#internetSite").on("change", testUrl);

    function testUrl() {
        var url = $(this).val();
        var regexUrl = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
        if (!url.match(regexUrl)) {
            $(this).css('border-color', '#c11a1a');
            $(this).next("span").fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next("span").hide();
        }
    }
    // Evenement au click du bouton soumission
    $("#formParams input#envoyer").on("click", function (event) {
        var champInputNameAsso = $("#formParams input#AssoName").val().trim();
        var champInputTelAsso = $("#formParams input#tel").val().trim();
        var champInputEmailAsso = $("#formParams input#email").val().trim();
        var champInputAdresseAsso = $("#formParams input#adresse").val().trim();
        var champInputAdresseCP = $("#formParams input#codepostal").val().trim();
        var champInputAdresseVille = $("#formParams input#ville").val().trim();

        if (testChampAssoVide(champInputNameAsso) == false) {
            nonValideAsso("#formParams input#AssoName");
        } else {
            valideAsso("#formParams input#AssoName");
        }
        if (testChampAssoVide(champInputAdresseAsso) == false) {
            nonValideAsso("#formParams input#adresse");
        } else {
            valideAsso("#formParams input#adresse");
        }
        if (testChampAssoVide(champInputAdresseCP) == false) {
            nonValideAsso("#formParams input#codepostal");
        } else {
            valideAsso("#formParams input#codepostal");
        }
        if (testChampAssoVide(champInputAdresseVille) == false) {
            nonValideAsso("#formParams input#ville");
        } else {
            valideAsso("#formParams input#ville");
        }
        if (testIfEmailAssoIsValid(champInputEmailAsso) == false) {
            nonValideAsso("#formParams input#email");
        } else {
            valideAsso("#formParams input#email");
        }
        if (testIfTelAssoIsValid(champInputTelAsso) == false) {
            nonValideAsso("#formParams input#tel");
        } else {
            valideAsso("#formParams input#tel");
        }

        if (testChampAssoVide(champInputNameAsso) == false || testChampAssoVide(champInputAdresseAsso) == false ||
            testChampAssoVide(champInputAdresseCP) == false || testChampAssoVide(champInputAdresseVille) == false ||
            testIfEmailAssoIsValid(champInputEmailAsso) == false || testIfTelAssoIsValid(champInputTelAsso) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampAssoVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testIfEmailAssoIsValid(value) {
        var regexEmailIsValid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!value.match(regexEmailIsValid)) {
            return false;
        }
    }

    function testIfTelAssoIsValid(value) {
        var regexTelIsValid = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!value.match(regexTelIsValid)) {
            return false;
        }
    }

    function nonValideAsso(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideAsso(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    $("#formAdherent input#footerdoc, #formAdherent textarea#objetsocial").on("blur", testAssoFooter);

    function testAssoFooter() {
        var champFooter = $(this).val();
        if (champFooter == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }

    // Evenement au click du bouton soumission
    $("#submitParamDoc").on("click", function (event) {
        var champInputRNAAsso = $("input#rna").val().trim();
        var champInputFooterdocAsso = $("input#footerdoc").val().trim();
        var champTextAreaObjetsocial = $("#objetsocial").val().trim();

        if (testChampAssoParamsVide(champInputFooterdocAsso) == false) {
            nonValideParamsAsso("input#footerdoc");
        } else {
            valideParamsAsso("input#footerdoc");
        }
        if (testChampAssoParamsVide(champTextAreaObjetsocial) == false) {
            nonValideParamsAsso("#objetsocial");
        } else {
            valideParamsAsso("#objetsocial");
        }
        if (testIfRnaParamsAssoIsValid(champInputRNAAsso) == false) {
            nonValideParamsAsso("input#rna");
        } else {
            valideParamsAsso("input#rna");
        }
        if (testChampAssoParamsVide(champInputFooterdocAsso) == false || testChampAssoParamsVide(champTextAreaObjetsocial) == false ||
            testIfRnaParamsAssoIsValid(champInputRNAAsso) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampAssoParamsVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testIfRnaParamsAssoIsValid(value) {
        var regexRnaIsValid = /^W[0-9]{9}$/;
        if (!value.match(regexRnaIsValid)) {
            return false;
        }
    }

    function nonValideParamsAsso(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideParamsAsso(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    // Bouton Reset AddForm & Update - événement au click
    $("#buttonReset").on("click", testReset);

    function testReset() {
        $("#AssoName, #tel, #email").css('border-color', '#ced4da');
        $("#formAdherent #nom, #formAdherent #prenom, #formAdherent #email, #formAdherent #adresse, #formAdherent #codepostal, #formAdherent #ville, #formAdherent #sexe, #formAdherent #fonction, #formAdherent #statut, #formAdherent #dateEntree, #formAdherent #cotisation, #formAdherent #reglement, #formAdherent #sel_degrees, #formAdherent #sel_jobs").removeClass('green-border');
        $("#formAdherent #nom, #formAdherent #prenom, #formAdherent #email, #formAdherent #adresse, #formAdherent #codepostal, #formAdherent #ville, #formAdherent #sexe, #formAdherent #fonction, #formAdherent #statut, #formAdherent #dateEntree, #formAdherent #cotisation, #formAdherent #reglement, #formAdherent #sel_degrees, #formAdherent #sel_jobs").css('border-color', '#ced4da');
        $(".errorMsgFormAdherent").hide();
        $("#degreesSelection .select2-container--default .select2-selection--single").removeClass("green-border");
        $("#jobsSelection .select2-container--default .select2-selection--single").removeClass("green-border");
        $("#tel").val("");
        $(".errorMsgCreateAsso").hide();
        $('.errorMsgTelCreateAsso').hide();
    }

    // Bouton Reset editAsso - événement au click
    $("#buttonResetUrl").on("click", testResetUrl);

    function testResetUrl() {
        $("#formParams input#linkedin, #formParams input#twitter, #formParams input#facebook, #formParams input#internetSite").removeClass('green-border');
        $("#formParams input#linkedin, #formParams input#twitter, #formParams input#facebook, #formParams input#internetSite").css('border-color', '#ced4da');
        $(".errorMsgCreateAssoUrl").hide();
    }

    // Test des INPUT SIRET et RNA template formParamsFile
    var test1, test2;
    test1 = test2 = false;
    // Le RNA doit comporter une lettre "W" et 9 chiffres
    $("#rna").on("blur", testRna);

    function testRna() {
        var champRna = $(this).val();
        if (!champRna.match(/^W[0-9]{9}$/)) {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
            test1 = false;
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
            test1 = true;
        }
    }
    // Le SIRET est numérique à quatorze chiffres
    // L'algorythme de Luhn procède en trois étapes,
    // Premièrement, un chiffre sur deux, en commençant par le deuxième jusqu'à la fin, est doublé. 
    // Si ce résultat est plus grand que neuf, ses chiffres sont additionnés (ce qui est équivalent, pour n'importe quel nombre dans l'intervalle de 10 à 18, de lui soustraire 9). Ainsi, 2 devient 4 et 7 devient 5 (=1+4).
    // Deuxièmement, la somme de tous les chiffres est effectuée.
    // Finalement, le résultat est divisé par 10. Si le reste est égal à zéro, le nombre original est valide.
    $("#siret").on("blur", siretValide);

    function siretValide() {
        var siret = $(this).val();
        if ((siret.length != 14) || (isNaN(siret))) {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
            test2 = false;
        } else {
            // Donc le SIRET est un numérique à 14 chiffres
            // Les 9 premiers chiffres sont ceux du SIREN (ou RCS), les 4 suivants
            // correspondent au numéro d'établissement
            // et enfin le dernier chiffre est une clef de LUHN.
            var somme = 0;
            var tmp;
            for (var cpt = 0; cpt < siret.length; cpt++) {
                if ((cpt % 2) == 0) { // Les positions impaires : 1er, 3è, 5è, etc...
                    tmp = siret.charAt(cpt) * 2; // On le multiplie par 2
                    if (tmp > 9)
                        tmp -= 9; // Si le résultat est supérieur à 9, on lui soustrait 9
                } else
                    tmp = siret.charAt(cpt);
                somme += parseInt(tmp);
            }
            if ((somme % 10) == 0) {
                $('#siret').css('border-color', '#6fcb2e');
                $('#siret').next().hide();
                test2 = true; // Si la somme est un multiple de 10 alors le SIRET est valide
            } else {
                $('#siret').css('border-color', '#c11a1a');
                $('#siret').next().fadeIn();
                test2 = false;
            }
        }
        return test2;
    }

    // Compter nombre de mots sur textArea editParamsFile
    $('#objetsocial').on("keyup", longueur);
    var nbMax = 300;

    function longueur() {
        var txt = $(this).val();
        var longueur = txt.length;
        var caract = 300 - longueur;
        var message = "Il ne vous reste que " + caract + " caractère(s) disponible(s).";
        $("#alertTextarea").html(message);
        if (txt.length > nbMax) {
            $("#objetsocial").val(txt.substr(0, nbMax));
        }
    }

    // Verification si bouton radio est checked et affichage des div correspondantes formUser
    $("input[type=radio][name=rolesUser]").click(function () {
        var val = $(this).val();
        if (val == 2) {
            $("#rolesUserAdminComment").slideUp();
            $("#rolesUserMemberComment").slideDown();
        } else {
            $("#rolesUserMemberComment").slideUp();
            $("#rolesUserAdminComment").slideDown();
        }

    });
    if ($("input[type=radio][name=rolesUser]:checked").attr("value") == 1) {
        $("#rolesUserMemberComment").hide();
        $("#rolesUserAdminComment").show();

    } else {
        $("#rolesUserMemberComment").show();
        $("#rolesUserAdminComment").hide();

    }

    $("input[type=radio][name=qualityUser]").click(function () {
        var val = $(this).val();
        if (val == 2) {
            $("#qualityUserAdherentComment").slideUp();
            $("#qualityUserUserComment").slideDown();
        } else {
            $("#qualityUserUserComment").slideUp();
            $("#qualityUserAdherentComment").slideDown();
        }

    });
    if ($("input[type=radio][name=qualityUser]:checked").attr("value") == 1) {
        $("#qualityUserUserComment").hide();
        $("#qualityUserAdherentComment").show();

    } else {
        $("#qualityUserUserComment").show();
        $("#qualityUserAdherentComment").hide();

    }

    // Recherche des donateurs jQuery - Filters
    $("#searchDonator").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#DonatorsTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    // Recherche des membres jQuery - Filters
    $("#searchMember").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#MembersTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // DataTable - donateurs
    $('#tableDonator').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": false,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        // Language
        "language": {
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - adherents
    $('#tableMembers').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        // Language
        "language": {
            "searchPlaceholder": "Rechercher un adhérent",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - users
    var table = $('#tableUsers').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        "order": [
            [1, "asc"]
        ],
        dom: 'Blfrtip',
        // select: true,
        buttons: [
            // {
            //     text: 'Tout sélectionner',
            //     action: function () {
            //         table.rows().select();
            //     }
            // },
            // {
            //     text: 'Ne rien sélectionner',
            //     action: function () {
            //         table.rows().deselect();
            //     }
            // },
            {
                extend: 'excelHtml5',
                title: 'Liste des utilisateurs'
            },
            {
                extend: 'pdfHtml5',
                title: 'Liste des utilisateurs'
            }
            // ,
            // {
            //     text: 'Get selected data',
            //     action: function () {
            //         var count = table.rows({
            //             selected: true
            //         }).data();
            //         var userId = [];
            //         for (let i = 0; i < count.length; i++) {
            //             userId.push(count[i][0]);
            //         }
            //         console.log(userId);
            //         $.ajax({
            //             type: "POST",
            //             url: "index.php?controller=user&action=prepareToSend",
            //             data: {
            //                 userId
            //             },
            //             contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            //         });
            //     }
            // }
            // 'excel', 'pdf'
        ],
        "columnDefs": [{
            "targets": [0, 3],
            "visible": false,
            "searchable": false
        }],
        // Language
        "language": {
            "searchPlaceholder": "Rechercher un utilisateur",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": " - %d lignes sélectionnées",
                    "0": " - aucune ligne sélectionnée",
                    "1": " - 1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - old members
    var table = $('#tableOldMembers').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        "order": [
            [1, "asc"]
        ],
        dom: 'Blfrtip',
        // select: true,
        buttons: [{
                extend: 'excelHtml5',
                title: 'Liste des anciens adhérents'
            },
            {
                extend: 'pdfHtml5',
                title: 'Liste des anciens adhérents'
            }
        ],
        "columnDefs": [{
            "targets": [2, 3, 4],
            "visible": false,
            "searchable": false
        }],
        // Language
        "language": {
            "searchPlaceholder": "Rechercher un ancien adhérent",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": " - %d lignes sélectionnées",
                    "0": " - aucune ligne sélectionnée",
                    "1": " - 1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - groupe adherent
    $('#tableGroup').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        dom: 'Blfrtip',
        buttons: [
            'excel', 'pdf'
        ],
        "columnDefs": [{
            "targets": [1, 2, 3, 4],
            "visible": false,
            "searchable": false
        }],
        // Language
        "language": {
            "searchPlaceholder": "Rechercher un groupe",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - ListCampaigns
    $('#tableListCampaigns').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        // Language
        "language": {
            "searchPlaceholder": "Rechercher une campagne d'adhésion",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - Liste relance
    $('#tableListRelaunch').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        // Language
        "language": {
            "searchPlaceholder": "Rechercher une campagne d'adhésion",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // DataTable - Liste GED documents
    $('#tableFiles').DataTable({
        // Désactivez les capacités de recherche dans DataTables
        "searching": true,
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tout"]
        ],
        // Désactive le tri
        "ordering": true,
        // Language
        "language": {
            "searchPlaceholder": "Rechercher un dossier de document",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de la ligne _START_ à _END_ sur _TOTAL_ lignes",
            "sInfoEmpty": "Affichage de la ligne 0 à 0 sur 0 ligne",
            "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ lignes",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    // Filtre via les moyens de paiement
    $("#methodFilter").on("change", function () {
        var value = $('#methodFilter option:selected').text().toLowerCase();
        $("#DonatorsTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //Show div export to csv view dons
    $('.hide-on-csv').click(function () {
        $('.revealCsv-on-click').slideToggle('slow');
    });

    //Hide div export to csv view dons
    $(document).on('click', '*', function (e) {
        var target = e.target;
        if ($(target).parents('.hide-on-csv').length <= 0 && !$(target).hasClass('revealCsv-on-hover')) {
            $('.revealCsv-on-click').slideUp('fast');
        }
    });

    // Compter le nombre de lignes dans le tableau de la page Dons Start
    var rowCount1 = $('#DonatorsTable tr').length;
    $("span.tableDonatorLinesNb").html(rowCount1 + " ligne(s)");

    // Cocher plusieurs checkboxs - tableau de la page Dons Start
    $("#searchTableCheckbox").on("change", function () {
        // $(':checkbox.custom-checkbox').prop('checked', true);
        if (this.checked) {
            $(':checkbox.custom-checkbox').prop('checked', true);
            var nbCheckBoxesChecked = $('#DonatorsTable input[name="searchTable[]"].custom-checkbox:checked').length;
        } else {
            $(':checkbox.custom-checkbox').prop('checked', false);
            var nbCheckBoxesChecked = $('#DonatorsTable input[name="searchTable[]"].custom-checkbox:checked').length;
        }
        if (nbCheckBoxesChecked > 0) {
            var rowCount1 = $('#DonatorsTable tr').length;
            $("span.tableDonatorLinesNb").html(nbCheckBoxesChecked + " ligne(s) sélectionnées sur " + rowCount1);
        } else {
            var rowCount1 = $('#DonatorsTable tr').length;
            $("span.tableDonatorLinesNb").html(rowCount1 + " ligne(s)");
        }
    });
    $(".custom-checkbox").on("change", function () {
        var nbCaseCochees = $('#DonatorsTable input[name="searchTable[]"].custom-checkbox:checked').length;
        if (nbCaseCochees > 0) {
            var rowCount1 = $('#DonatorsTable tr').length;
            $("span.tableDonatorLinesNb").html(nbCaseCochees + " ligne(s) sélectionnées sur " + rowCount1);
        } else {
            var rowCount1 = $('#DonatorsTable tr').length;
            $("span.tableDonatorLinesNb").html(rowCount1 + " ligne(s)");
        }
    });

    // Compter le nombre de lignes dans le tableau de la page Adherent Start
    var rowCount = $('#MembersTable tr').length;
    $("span.tableMemberLinesNb").html(rowCount + " ligne(s)");
    // Cocher plusieurs checkboxs - tableau de la page Adherent Start
    $("#searchTableCheckbox").on("change", function () {
        // $(':checkbox.custom-checkbox').prop('checked', true);
        if (this.checked) {
            $(':checkbox.custom-checkbox').prop('checked', true);
            var nbCheckBoxesChecked = $('#MembersTable input[name="searchTable[]"].custom-checkbox:checked').length;
        } else {
            $(':checkbox.custom-checkbox').prop('checked', false);
            var nbCheckBoxesChecked = $('#MembersTable input[name="searchTable[]"].custom-checkbox:checked').length;
        }
        if (nbCheckBoxesChecked > 0) {
            var rowCount = $('#MembersTable tr').length;
            $("span.tableMemberLinesNb").html(nbCheckBoxesChecked + " ligne(s) sélectionnées sur " + rowCount);
        } else {
            var rowCount = $('#MembersTable tr').length;
            $("span.tableMemberLinesNb").html(rowCount + " ligne(s)");
        }
    });
    $(".custom-checkbox").on("change", function () {
        var nbCaseCochees = $('#MembersTable input[name="searchTable[]"].custom-checkbox:checked').length;
        if (nbCaseCochees > 0) {
            var rowCount = $('#MembersTable tr').length;
            $("span.tableMemberLinesNb").html(nbCaseCochees + " ligne(s) sélectionnées sur " + rowCount);
        } else {
            var rowCount = $('#MembersTable tr').length;
            $("span.tableMemberLinesNb").html(rowCount + " ligne(s)");
        }
    });

    // Compter le nombre de lignes dans le tableau de la page Relance
    var rowCount1 = $('#RelaunchTable tr').length;
    $("span.tableLinesNb").html(rowCount1 + " ligne(s)");

    // Cocher plusieurs checkboxs - tableau de la page Relance
    $("#searchTableCheckbox").on("change", function () {
        // $(':checkbox.custom-checkbox').prop('checked', true);
        if (this.checked) {
            $(':checkbox.custom-checkbox').prop('checked', true);
            var nbCheckBoxesChecked = $('#RelaunchTable input[name="searchTable[]"].custom-checkbox:checked').length;
        } else {
            $(':checkbox.custom-checkbox').prop('checked', false);
            var nbCheckBoxesChecked = $('#RelaunchTable input[name="searchTable[]"].custom-checkbox:checked').length;
        }
        if (nbCheckBoxesChecked > 0) {
            var rowCount1 = $('#RelaunchTable tr').length;
            $("span.tableLinesNb").html(nbCheckBoxesChecked + " ligne(s) sélectionnées sur " + rowCount1);
        } else {
            var rowCount1 = $('#RelaunchTable tr').length;
            $("span.tableLinesNb").html(rowCount1 + " ligne(s)");
        }
    });
    $(".custom-checkbox").on("change", function () {
        var nbCaseCochees = $('#RelaunchTable input[name="searchTable[]"].custom-checkbox:checked').length;
        if (nbCaseCochees > 0) {
            var rowCount1 = $('#RelaunchTable tr').length;
            $("span.tableLinesNb").html(nbCaseCochees + " ligne(s) sélectionnées sur " + rowCount1);
        } else {
            var rowCount1 = $('#RelaunchTable tr').length;
            $("span.tableLinesNb").html(rowCount1 + " ligne(s)");
        }
    });

    // Vérifier si au moins une checkbox est checked - tableau de la page Dons Start
    $('.btn.btn-grey').click(function () {
        //on vérifie que nos conditions d'envoi sont bonnes
        if (countCheckedJQuery() >= 1) {
            var donatorId = [];
            $.each($("input[name='searchTable[]']:checked"), function () {
                donatorId.push($(this).val());
            });
            // console.log(donatorId);
            // $.ajax(
            //     {
            //         type: "POST",
            //         url: "index.php?controller=dons&action=prepareToSend", 
            //         data:{
            //             donatorId
            //         },
            //         contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            //     });
        } else {
            //on empêche le questionnaire de s'envoyer
            event.preventDefault();
            //opacité
            //ouverture-fermeture popup
            $("#popup").slideToggle();
            // CLIC SUR "FERMER"
            $('#popup .buttonSet').click(function () {
                $("#popup").slideUp();
            });
        }
    });

    function countCheckedJQuery() {
        var checked = $(".custom-checkbox:checked"); //sélectionne tous les éléments de classe "custom-checkbox" qui sont sélectionnés
        var checked2 = $("input:checkbox:checked"); //pareil mais avec toutes les checkbox de la page
        return checked.length;
    }

    // Test du formulaire avant envoi du mail - test des input
    $("#subjectMail").on("blur", testsubjectMail);

    function testsubjectMail() {
        var subjectMail = $(this).val();
        if (subjectMail == "") {
            $(this).css('border-color', '#c11a1a');
            $('.errorMsgSubjectMail').fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $('.errorMsgSubjectMail').hide();
        }
    }
    $("#textForMail").on("blur", textForMail);

    function textForMail() {
        var textForMail = $(this).val();
        if (textForMail == "") {
            $(this).css('border-color', '#c11a1a');
            $('.errorMsgTextForMail').fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $('.errorMsgTextForMail').hide();
        }
    }

    // Evenement au click du bouton soumission envoi de mail
    $("#sendMailUserSelected").on("click", function (event) {
        var champInputSubjectMail = $("input#subjectMail").val().trim();
        var champInputTextForMail = $("input#textForMail").val().trim();

        if (testChampSendMailVide(champInputSubjectMail) == false) {
            nonValideSendMail("input#subjectMail");
        } else {
            valideSendMail("input#subjectMail");
        }
        if (testChampSendMailVide(champInputTextForMail) == false) {
            nonValideSendMail("input#textForMail");
        } else {
            valideSendMail("input#textForMail");
        }
        if (testChampSendMailVide(champInputSubjectMail) == false || testChampSendMailVide(champInputTextForMail) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampSendMailVide(value) {
        if (value == "") {
            return false;
        }
    }

    function nonValideSendMail(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideSendMail(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    // Validation du formulaire ajout de don
    $("#sel_MemberNameForDonation").on("change", function () {
        $(".row.groupDonatorByNameEmail").css('display', 'flex');
    });

    // Validation du formulaire ajout du représentant du groupe
    $("#sel_MemberNameForGroup").on("change", function () {
        $(".row.groupUserByName").css('display', 'flex');
    });

    $("#montant").on("blur", testMontantDon);

    function testMontantDon() {
        var montantDon = $(this).val().trim();
        if (isNaN(montantDon) || montantDon == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#99d601');
            $(this).next().hide();
        }
    }
    $('#dateDons, #reglement').on("blur", testTexte);

    function testTexte() {
        var champ = $(this).val().trim();
        if (champ == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#99d601');
            $(this).next().hide();
        }
    }

    $("#buttonAddDonator").on("click", function () {
        var addDon1 = $("#montant").val().trim();
        var addDon2 = $("#dateDons, #reglement").val().trim();
        if (isNaN(addDon1) || addDon1 == "") {
            return false;
        } else if (addDon2 == "") {
            return false;
        } else {
            return true;
        }
    });

    // Suppression du donateur
    $(function () {
        function _confirm() {
            return (confirm('Voulez-vous supprimer le donateur ?'));
        }
        $('#resetButton').click(_confirm);
    });

    // Suppression de la campagne
    $(function () {
        function _confirm() {
            return (confirm('Voulez-vous supprimer la campagne ?'));
        }
        $('#resetButtonCampaign').click(_confirm);
    });

    // Suppression du dossier de document
    $(function () {
        function _confirm() {
            return (confirm("Voulez-vous supprimer le document ?"));
        }
        $('#resetButtonDoc').click(_confirm);
    });

    // Suppression du document
    $(function () {
        function _confirm() {
            return (confirm("Voulez-vous supprimer le dossier ? \nSi c\'est le cas, merci de vérifier s\'il existe des documents dans le dossier car ils seront supprimés"));
        }
        $('#resetButtonFile').click(_confirm);
    });

    // Suppression d'une news
    $(function () {
        function _confirm() {
            return (confirm("Voulez-vous supprimer la news ?"));
        }
        $('#resetButtonNews').click(_confirm);
    });

    // Suppression du groupe d'adherent
    $(function () {
        function _confirm() {
            return (confirm("Voulez-vous supprimer le groupe ? \nSi c\'est le cas, merci de vérifier s\'il existe des utilisateurs dans le groupe car ils seront supprimés"));
        }
        $('#closeGroup').click(_confirm);
    });

    // Création campagne adhésion
    $("#buttonDiscount").on("click", function () {
        $("#wrapperDiscount").slideDown();
        $("#resetDiscount").fadeIn();
        $("#buttonDiscount").hide();
    });
    $("#resetDiscount").on("click", function () {
        $("#wrapperDiscount").slideUp();
        $("#buttonDiscount").fadeIn();
        $("#resetDiscount").hide();
        $("#libelleReduction, #valueReduction").val('').css('border-color', '#ced4da');
        $("#libelleReduction, #valueReduction").next().hide();
        $("#rate").css('border-color', '#ced4da');
    });

    $("#tarifCotisation, #tarifCotisationBrute").on("blur", floatNumber);

    function floatNumber() {
        var decimal = $(this).val();
        var regexNum = /^[0-9]+\.[0-9]{2}$/gm;
        if (decimal.match(regexNum)) {
            $(this).css('border-color', '#99d601');
            $(this).next().hide();
        } else if ($.isNumeric(decimal) && decimal >= 0) {
            decimal = decimal.replace(",", ".");
            decimal = parseFloat(decimal).toFixed(2);
            $(this).val(decimal);
            $(this).css('border-color', '#99d601');
            $(this).next().hide();
        } else {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        }
    }
    $("#libelleCotisation, #campaignStartDate, #campaignEndDate, #libelleReduction, #rate").on("blur", testFormAdhesion);

    function testFormAdhesion() {
        var champCampaignAdhesion = $(this).val();
        if (champCampaignAdhesion == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }

    $("#campaignLength").on("blur", testFormAdhesionNumberForDate);

    function testFormAdhesionNumberForDate() {
        var champCampaignLength = $(this).val();
        if (isNaN(champCampaignLength) || champCampaignLength == "" || champCampaignLength == null) {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }

    // Verification si bouton radio formulaire campagne adhésion est checked et affichage des div correspondantes
    $("input[type=radio][name=rolesCampaign]").click(function () {
        var val = $(this).val();
        if (val == "2") {
            $("#rolesCampaignDateToDate").slideUp();
            $("#rolesCampaignByMounth").slideDown();
        } else {
            $("#rolesCampaignByMounth").slideUp();
            $("#rolesCampaignDateToDate").slideDown();
        }

    });
    if ($("input[type=radio][name=rolesCampaign]:checked").attr("value") == "1") {
        $("#rolesCampaignByMounth").hide();
        $("#rolesCampaignDateToDate").show();

    } else {
        $("#rolesCampaignByMounth").show();
        $("#rolesCampaignDateToDate").hide();

    }

    // // Evenement au change du libelle "Description" Adhésion - campagne adhésion - première lettre en Uppercase
    // $("#libelleCotisation").on("change", function () {
    //     var champInputLibelleCotisation = $("input#libelleCotisation").val().trim();
    //     champInputLibelleCotisation = champInputLibelleCotisation.toLowerCase().replace(/^(.)|(\s|\-)(.)/g, function (letter) {
    //         return letter.toUpperCase();
    //     });
    //     champInputLibelleCotisation = $("input#libelleCotisation").val(champInputLibelleCotisation);
    // });

    // // Evenement au change du libelle "Description" Reduction - campagne adhésion - première lettre en Uppercase
    // $("#libelleReduction").on("change", function () {
    //     var champInputLibelleReduction = $("input#libelleReduction").val().trim();
    //     champInputLibelleReduction = champInputLibelleReduction.toLowerCase().replace(/^(.)|(\s|\-)(.)/g, function (secondLetter) {
    //         return secondLetter.toUpperCase();
    //     });
    //     champInputLibelleReduction = $("input#libelleReduction").val(champInputLibelleReduction);
    // });

    // Evenement au change du select : si percent alors réduction comprise entre 0 et 100, si € la reduction <= montant cotisation
    $("#valueReduction, #rate, #tarifCotisation").on("change", function () {
        var champInputValueReduction = $("#valueReduction").val();
        var selected_option = $('#rate option:selected').val();
        var regexNumberBetween = /^[0-9][0-9]?$|^100$/;
        if (selected_option == "percent") {
            if (champInputValueReduction.match(regexNumberBetween)) {
                $("#valueReduction").val(champInputValueReduction);
                $("#valueReduction").css('border-color', '#99d601');
                $("#valueReduction").next().hide()
            } else {
                $("#valueReduction").next().fadeIn().text("La valeur doit être un nombre entier compris entre 0 et 100");
                $("#valueReduction").css('border-color', '#c11a1a');
            }
        }
        if (selected_option == "currency") {
            var champInputTarifCotisation = parseFloat($("#tarifCotisation").val());
            var champInputValueReduction = parseFloat($("#valueReduction").val());
            if ((champInputValueReduction >= 0) && (champInputValueReduction <= champInputTarifCotisation)) {
                $("#valueReduction").val(champInputValueReduction);
                $("#valueReduction").css('border-color', '#99d601');
                $("#valueReduction").next().hide()
            } else {
                $("#valueReduction").next().fadeIn().text("La valeur doit être supérieure ou égale à 0 et inférieure ou égale au montant de la cotisation");
                $("#valueReduction").css('border-color', '#c11a1a');
            }
        }
    });

    // Evenement au click du bouton soumission - campagne adhésion
    $("#submitCampaignForm").on("click", function (event) {
        var champInputLibelleCotisation = $("input#libelleCotisation").val().trim();
        var champInputTarifCotisation = $("input#tarifCotisation").val().trim();
        var champInputCampaignStartDate = $("input#campaignStartDate").val().trim();
        var champInputCampaignEndDate = $("input#campaignEndDate").val().trim();
        var champInputCampaignLength = $("input#campaignLength").val().trim();
        var champInputLibelleReduction = $("input#libelleReduction").val().trim();
        var champInputValueReduction = $("input#valueReduction").val().trim();
        if ($("input[name=rolesCampaign]:checked").val() == "1" && $("#wrapperDiscount").is(':visible')) {
            if (testChampCampaignFormVide(champInputLibelleCotisation) == false) {
                nonValideCampaignForm("input#libelleCotisation");
            } else {
                valideCampaignForm("input#libelleCotisation");
            }
            if (testChampCampaignFormNumber(champInputTarifCotisation) == false) {
                nonValideParamsAsso("input#tarifCotisation");
            } else {
                valideParamsAsso("input#tarifCotisation");
            }
            if (testChampCampaignFormVide(champInputCampaignStartDate) == false) {
                nonValideParamsAsso("input#campaignStartDate");
            } else {
                valideParamsAsso("input#campaignStartDate");
            }
            if (testChampCampaignFormVide(champInputCampaignEndDate) == false) {
                nonValideParamsAsso("input#campaignEndDate");
            } else {
                valideParamsAsso("input#campaignEndDate");
            }
            if (testChampCampaignFormVide(champInputLibelleReduction) == false) {
                nonValideParamsAsso("input#libelleReduction");
            } else {
                valideParamsAsso("input#libelleReduction");
            }
            if (testChampCampaignFormVide(champInputValueReduction) == false) {
                nonValideParamsAsso("input#valueReduction");
            } else {
                valideParamsAsso("input#valueReduction");
            }

            if (testChampCampaignFormVide(champInputLibelleCotisation) == false || testChampCampaignFormNumber(champInputTarifCotisation) == false ||
                testChampCampaignFormVide(champInputCampaignStartDate) == false || testChampCampaignFormVide(champInputCampaignEndDate) == false ||
                testChampCampaignFormVide(champInputLibelleReduction) == false || testChampCampaignFormVide(champInputValueReduction) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if ($("input[name=rolesCampaign]:checked").val() == "1" && $("#wrapperDiscount").is(':hidden')) {
            if (testChampCampaignFormVide(champInputLibelleCotisation) == false) {
                nonValideCampaignForm("input#libelleCotisation");
            } else {
                valideCampaignForm("input#libelleCotisation");
            }
            if (testChampCampaignFormNumber(champInputTarifCotisation) == false) {
                nonValideParamsAsso("input#tarifCotisation");
            } else {
                valideParamsAsso("input#tarifCotisation");
            }
            if (testChampCampaignFormVide(champInputCampaignStartDate) == false) {
                nonValideParamsAsso("input#campaignStartDate");
            } else {
                valideParamsAsso("input#campaignStartDate");
            }
            if (testChampCampaignFormVide(champInputCampaignEndDate) == false) {
                nonValideParamsAsso("input#campaignEndDate");
            } else {
                valideParamsAsso("input#campaignEndDate");
            }

            if (testChampCampaignFormVide(champInputLibelleCotisation) == false || testChampCampaignFormNumber(champInputTarifCotisation) == false ||
                testChampCampaignFormVide(champInputCampaignStartDate) == false || testChampCampaignFormVide(champInputCampaignEndDate) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if ($("input[name=rolesCampaign]:checked").val() == "2" && $("#wrapperDiscount").is(':visible')) {
            if (testChampCampaignFormVide(champInputLibelleCotisation) == false) {
                nonValideCampaignForm("input#libelleCotisation");
            } else {
                valideCampaignForm("input#libelleCotisation");
            }
            if (testChampCampaignFormNumber(champInputTarifCotisation) == false) {
                nonValideParamsAsso("input#tarifCotisation");
            } else {
                valideParamsAsso("input#tarifCotisation");
            }
            if (testChampCampaignFormVide(champInputCampaignLength) == false) {
                nonValideParamsAsso("input#campaignLength");
            } else {
                valideParamsAsso("input#campaignLength");
            }
            if (testChampCampaignFormVide(champInputLibelleReduction) == false) {
                nonValideParamsAsso("input#libelleReduction");
            } else {
                valideParamsAsso("input#libelleReduction");
            }
            if (testChampCampaignFormVide(champInputValueReduction) == false) {
                nonValideParamsAsso("input#valueReduction");
            } else {
                valideParamsAsso("input#valueReduction");
            }

            if (testChampCampaignFormVide(champInputLibelleCotisation) == false || testChampCampaignFormNumber(champInputTarifCotisation) == false ||
                testChampCampaignFormVide(champInputCampaignLength) == false || testChampCampaignFormVide(champInputLibelleReduction) == false ||
                testChampCampaignFormVide(champInputValueReduction) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if ($("input[name=rolesCampaign]:checked").val() == "2" && $("#wrapperDiscount").is(':hidden')) {
            if (testChampCampaignFormVide(champInputLibelleCotisation) == false) {
                nonValideCampaignForm("input#libelleCotisation");
            } else {
                valideCampaignForm("input#libelleCotisation");
            }
            if (testChampCampaignFormNumber(champInputTarifCotisation) == false) {
                nonValideParamsAsso("input#tarifCotisation");
            } else {
                valideParamsAsso("input#tarifCotisation");
            }
            if (testChampCampaignFormVide(champInputCampaignLength) == false) {
                nonValideParamsAsso("input#campaignLength");
            } else {
                valideParamsAsso("input#campaignLength");
            }

            if (testChampCampaignFormVide(champInputLibelleCotisation) == false || testChampCampaignFormNumber(champInputTarifCotisation) == false ||
                testChampCampaignFormVide(champInputCampaignLength) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
    });

    function testChampCampaignFormVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testChampCampaignFormNumber(value) {
        var regexNum = /^[0-9]+\.[0-9]{2}$/;
        if (!value.match(regexNum)) {
            return false;
        }
    }

    function nonValideCampaignForm(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideCampaignForm(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    // Vérification si checkbox est cochée et affichage de l'imput date renouvellement - formulaire update adherent
    $("input[name=inputRenewal").on("change", function () {
        if (this.checked) {
            $(".inputRenewalAndDisplayDateRenewal").fadeIn();
        } else {
            $(".inputRenewalAndDisplayDateRenewal").hide();
        }
    });

    // FORMULAIRE CREATION DOSSIER ET DOCUMENT
    // Evenements formulaire création dossier et document
    $("select#nomDossier, select#authorFunction, select#typeDoc").change(function () {
        var value = $(this).val();
        if (value == "") {
            $(this).removeClass("green-border");
            $(this).focus();
        } else {
            $(this).addClass("green-border");
        }
    });

    // // Affichage de la div suivante si lien est cliqué
    // $("#clickForNewFile").click(function () {
    //     $("#formNewFile").slideToggle({
    //         duration: 200,
    //         easing: "easeOutQuad",
    //         start: function () {
    //             $(this).css('display', 'flex');
    //         }
    //     });
    //     $(".hiddenNomDossier").slideToggle();
    //     $("#formNewFile input, #formNewFile select").val('');
    //     $("#formNewFile input").css('border-color', '#ced4da');
    //     $("select#nomDossier").css('border-color', '#ced4da');
    //     $("select#nomDossier").next("span").hide();
    //     $("#formNewFile select").removeClass("green-border");
    // });

    // Affichage des input file et lien en fonction du select #typeDoc - jquery show hide div using select box
    $("select#typeDoc").change(function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            if (optionValue) {
                $(".fileForUpdate").not("." + optionValue).hide();
                $("." + optionValue).fadeIn();
            } else {
                $(".fileForUpdate").hide();
            }
        });
    }).change();

    $("#newNameFile, #authorFirstName, #authorName, #nameDoc, #descriptionForDoc, #nameNews, #dateNews, #descriptionForNews").on("blur", testFormNewFile);

    function testFormNewFile() {
        var champFormNewFile = $(this).val();
        if (champFormNewFile == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).next().fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next().hide();
        }
    }

    $("input#linkUrl").on("change", testUrl);

    function testUrl() {
        var url = $(this).val();
        var regexUrl = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
        if (!url.match(regexUrl)) {
            $(this).css('border-color', '#c11a1a');
            $(this).next("span").fadeIn();
        } else {
            $(this).css('border-color', '#6fcb2e');
            $(this).next("span").hide();
        }
    }

    // Compter nombre de mots sur textArea ajout de document
    $('#descriptionForDoc').on("keyup", longueur);
    var nbMax = 170;

    function longueur() {
        var txt = $(this).val();
        var longueur = txt.length;
        var caract = 170 - longueur;
        var message = "Il ne vous reste que " + caract + " caractère(s) disponible(s).";
        $("#alertTextarea").html(message);
        if (txt.length > nbMax) {
            $("#descriptionForDoc").val(txt.substr(0, nbMax));
        }
        $("span.errorMsgFormAdherent").hide();
        $('#descriptionForDoc').css('border-color', '#6fcb2e');
    }

    // Evenement au chargement du fichier
    var fileInput = $("#fileDoc"),
        button = $(".input-file-trigger"),
        the_return = $(".file-return");
    fileInput.on("change", function () {
        // var file = $(this).val();
        var file = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
        console.log(file);

        var ext = fileName.split('.').pop().toLowerCase();
        var maxSize = 2000000; // fichier doit être inférieur à 2mb
        if ((fileSize > maxSize) || ($.inArray(ext, ['pdf', 'doc', 'docx']) == -1)) {
            button.html('Uniquement du type pdf, doc ou docx, taille max à 2Mo');
            button.addClass('input-trigger-error');
        } else {
            button.html('Fichier prêt à être mis en ligne');
            button.removeClass('input-trigger-error');
            button.addClass('input-trigger-success');
        }
        the_return.html(fileName);
        // button.html('Fichier prêt à être mis en ligne');
        // button.addClass('input-trigger-success');
    })

    // Evenement au chargement du fichier HTML
    var fileInput = $("#fileFocus"),
        button = $(".input-file-trigger"),
        the_return = $(".file-return");
    fileInput.on("change", function () {
        // var file = $(this).val();
        var file = this.files[0],
            fileName = file.name,
            fileSize = file.size,
            fileType = file.type;
        console.log(file);

        var ext = fileName.split('.').pop().toLowerCase();
        var maxSize = 2000000; // fichier doit être inférieur à 2mb
        if ((fileSize > maxSize) || ($.inArray(ext, ['html']) == -1)) {
            button.html('Uniquement du type html, taille max à 2Mo');
            button.addClass('input-trigger-error');
        } else {
            button.html('Fichier prêt à être mis en ligne');
            button.removeClass('input-trigger-error');
            button.addClass('input-trigger-success');
        }
        the_return.html(fileName);
        // button.html('Fichier prêt à être mis en ligne');
        // button.addClass('input-trigger-success');
    })

    // Evenement au click du bouton soumission création dossier
    $("input#buttonAddFile").on("click", function (event) {
        var champNewNameFile = $("input#newNameFile").val().trim();
        var champAuthorFirstName = $("input#authorFirstName").val().trim();
        var champAuthorName = $("input#authorName").val().trim();
        var champSelectAuthorFunction = $("select#authorFunction").val().trim();

        if (testChampAddDocVide(champNewNameFile) == false) {
            nonValideAddDoc("input#newNameFile");
        } else {
            valideAddDoc("input#newNameFile");
        }
        if (testChampAddDocVide(champAuthorFirstName) == false) {
            nonValideAddDoc("input#authorFirstName");
        } else {
            valideAddDoc("input#authorFirstName");
        }
        if (testChampAddDocVide(champAuthorName) == false) {
            nonValideAddDoc("input#authorName");
        } else {
            valideAddDoc("input#authorName");
        }
        if (testChampAddDocVide(champSelectAuthorFunction) == false) {
            nonValideAddDoc("select#authorFunction");
        } else {
            valideAddDoc("select#authorFunction");
        }

        if (testChampAddDocVide(champNewNameFile) == false || testChampAddDocVide(champAuthorFirstName) == false ||
            testChampAddDocVide(champAuthorName) == false || testChampAddDocVide(champSelectAuthorFunction) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    // Evenement au click du bouton soumission création document
    $("input#buttonAddDoc").on("click", function (event) {
        var champSelectNomDossier = $("select#nomDossier").val().trim();
        var champInputNameDoc = $("input#nameDoc").val().trim();
        var champInputDateDoc = $("input#dateDoc").val().trim();
        var champSelectTypeDoc = $("select#typeDoc").val().trim();
        var champTextAreaDescriptionForDoc = $("textarea#descriptionForDoc").val().trim();
        var champLinkUrl = $("input#linkUrl").val().trim();
        var vidFileLength = $("input#fileDoc").get(0).files.length;
        var champFileHtmlLength = $("input#fileFocus").get(0).files.length;

        if (champSelectTypeDoc == "") {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champSelectTypeDoc) == false) {
                nonValideAddDoc("select#typeDoc");
            } else {
                valideAddDoc("select#typeDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champSelectTypeDoc) == false || testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if (champSelectTypeDoc != "" && $(".link.fileForUpdate").is(':visible')) {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }
            if (testIfUrlLinkIsValid(champLinkUrl) == false) {
                nonValideAddDoc("input#linkUrl");
            } else {
                valideAddDoc("input#linkUrl");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champTextAreaDescriptionForDoc) == false || testIfUrlLinkIsValid(champLinkUrl) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if (champSelectTypeDoc != "" && $(".doc.fileForUpdate").is(':visible')) {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }
            if (vidFileLength === 0) {
                nonValideFileDoc(".input-file-trigger");
            } else {
                valideFileDoc(".input-file-trigger");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champTextAreaDescriptionForDoc) == false || vidFileLength === 0) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if (champSelectTypeDoc != "" && $(".focus.fileForUpdate").is(':visible')) {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }
            if (champFileHtmlLength === 0) {
                nonValideFileDoc(".input-file-trigger");
            } else {
                valideFileDoc(".input-file-trigger");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champTextAreaDescriptionForDoc) == false || champFileHtmlLength === 0) {
                event.preventDefault();
            } else {
                return true;
            }
        }

    });

    // Evenement au click du bouton soumission modification document
    $("input#buttonUpDoc").on("click", function (event) {
        var champSelectNomDossier = $("select#nomDossier").val().trim();
        var champInputNameDoc = $("input#nameDoc").val().trim();
        var champInputDateDoc = $("input#dateDoc").val().trim();
        var champSelectTypeDoc = $("select#typeDoc").val().trim();
        var champTextAreaDescriptionForDoc = $("textarea#descriptionForDoc").val().trim();
        var champLinkUrl = $("input#linkUrl").val().trim();

        if (champSelectTypeDoc == "") {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champSelectTypeDoc) == false) {
                nonValideAddDoc("select#typeDoc");
            } else {
                valideAddDoc("select#typeDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champSelectTypeDoc) == false || testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if (champSelectTypeDoc != "" && $(".link.fileForUpdate").is(':visible')) {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }
            if (testIfUrlLinkIsValid(champLinkUrl) == false) {
                nonValideAddDoc("input#linkUrl");
            } else {
                valideAddDoc("input#linkUrl");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champTextAreaDescriptionForDoc) == false || testIfUrlLinkIsValid(champLinkUrl) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }
        if (champSelectTypeDoc != "" && $(".doc.fileForUpdate").is(':visible')) {
            if (testChampAddDocVide(champSelectNomDossier) == false) {
                nonValideAddDoc("select#nomDossier");
            } else {
                valideAddDoc("select#nomDossier");
            }
            if (testChampAddDocVide(champInputNameDoc) == false) {
                nonValideAddDoc("input#nameDoc");
            } else {
                valideAddDoc("input#nameDoc");
            }
            if (testChampAddDocVide(champInputDateDoc) == false) {
                nonValideAddDoc("input#dateDoc");
            } else {
                valideAddDoc("input#dateDoc");
            }
            if (testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                nonValideAddDoc("textarea#descriptionForDoc");
            } else {
                valideAddDoc("textarea#descriptionForDoc");
            }

            if (testChampAddDocVide(champSelectNomDossier) == false || testChampAddDocVide(champInputNameDoc) == false || testChampAddDocVide(champInputDateDoc) == false ||
                testChampAddDocVide(champTextAreaDescriptionForDoc) == false) {
                event.preventDefault();
            } else {
                return true;
            }
        }

    });

    // // Replace the <textarea id="descriptionForNews"> with a CKEditor
    // // instance, using default configuration.
    // var editor = CKEDITOR.replace( 'descriptionForNews' );
    // // var description = CKEDITOR.instances.descriptionForNews.getData().replace(/<[^>]*>/gi, '').trim(); 

    // editor.on( 'blur', function(evt) {
    //     // getData() returns CKEditor's HTML content.
    //     if ( evt.editor.getData().length == 0 ) {  
    //         $('#cke_descriptionForNews').css('border-color', '#c11a1a');
    //         $('#cke_descriptionForNews').next().fadeIn();
    //     } else {
    //         $('#cke_descriptionForNews').css('border-color', '#6fcb2e');
    //         $('#cke_descriptionForNews').next().hide();

    //     }
    // });

    // Evenement au click du bouton soumission création news
    $("input#buttonAddNews").on("click", function (event) {
        var champNameNews = $("input#nameNews").val().trim();
        var champDateNews = $("input#dateNews").val().trim();
        var champDescriptionNews = $("textarea#descriptionForNews").val().trim();

        if (testChampAddDocVide(champNameNews) == false) {
            nonValideAddDoc("input#nameNews");
        } else {
            valideAddDoc("input#nameNews");
        }
        if (testChampAddDocVide(champDateNews) == false) {
            nonValideAddDoc("input#dateNews");
        } else {
            valideAddDoc("input#dateNews");
        }
        if (testChampAddDocVide(champDescriptionNews) == false) {
            nonValideAddDoc("textarea#descriptionForNews");
        } else {
            valideAddDoc("textarea#descriptionForNews");
        }

        if (testChampAddDocVide(champNameNews) == false || testChampAddDocVide(champDateNews) == false || testChampAddDocVide(champDescriptionNews) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampAddDocVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testIfUrlLinkIsValid(value) {
        var regexUrlLinkIsValid = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
        if (!value.match(regexUrlLinkIsValid)) {
            return false;
        }
    }

    function nonValideAddDoc(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideAddDoc(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    function nonValideFileDoc(selector) {
        return $(selector).addClass('input-trigger-error') + $(selector).html('Vous devez choisir un fichier');
    }

    function valideFileDoc(selector) {
        return $(selector).removeClass('input-trigger-error') + $(selector).addClass('input-trigger-success') + $(selector).html('Fichier prêt à être mis en ligne');
    }

    function nonValideCKTextarea(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideCKTextarea(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    // Réinitialisation mot de passe pour l'adhérent
    $("#reinitPw").on("click", function () {
        $("#wrapperForm").toggle(500);
    });

    // Faire disparaître le placeholder au focus (page contact)
    $('.placeholder').focusin(function () {
        $(this).data('holder', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    });
    $('.placeholder').focusout(function () {
        $(this).attr('placeholder', $(this).data('holder'));
    });

    // Evenement au click du bouton envoyer page contact
    $("input#submitForContact").on("click", function (event) {
        var champContactFirstName = $("input#first_name").val().trim();
        var champContactLastName = $("input#last_name").val().trim();
        var champContactEmail = $("input#emailContact").val().trim();
        var champContactPhone = $("input#phoneContact").val().trim();
        var champContactMessage = $("textarea#messageContact").val().trim();

        if (testChampContactVide(champContactFirstName) == false) {
            nonValideContact("input#first_name");
        } else {
            valideContact("input#first_name");
        }
        if (testChampContactVide(champContactLastName) == false) {
            nonValideContact("input#last_name");
        } else {
            valideContact("input#last_name");
        }
        if (testContactEmailIsValue(champContactEmail) == false) {
            nonValideContact("input#emailContact");
        } else {
            valideContact("input#emailContact");
        }
        if (testContactTelIsValid(champContactPhone) == false) {
            nonValideContact("input#phoneContact");
        } else {
            valideContact("input#phoneContact");
        }
        if (testChampContactVide(champContactMessage) == false) {
            nonValideContact("textarea#messageContact");
        } else {
            valideContact("textarea#messageContact");
        }

        if (testChampContactVide(champContactFirstName) == false || testChampContactVide(champContactLastName) == false || testContactEmailIsValue(champContactEmail) == false ||
            testContactTelIsValid(champContactPhone) == false || testChampContactVide(champContactMessage) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampContactVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testContactEmailIsValue(value) {
        if (!value.match(/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/)) {
            return false;
        }
    }

    function testContactTelIsValid(value) {
        var regexTel = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!value.match(regexTel)) {
            return false;
        }
    }

    function nonValideContact(selector) {
        return $(selector).next('span').fadeIn();
    }

    function valideContact(selector) {
        return $(selector).next('span').fadeOut();
    }

    // Image d'arrière-plan aléatoire lors de l'actualisation - Page de connexion
    var bgArray = ['bg-login-0.jpg', 'bg-login-1.jpg', 'bg-login-2.jpg', 'bg-login-3.jpg', 'bg-login-9.jpg'];
    var bg = bgArray[Math.floor(Math.random() * bgArray.length)];

    // Définition du chemin pour les images
    var path = 'img/';
    $('head').append(`<style>body main .content:before { background-image: url(${path}${bg}) }</style>`);

    // Animation des images du détail adhérent au hover
    $('.icon-rocket').hover(function () {
        $(this).toggleClass('animated tada');
    });
    // Evenement au click du bouton soumission ajout d'un groupe
    $("#formParams input#buttonAddGroup").on("click", function (event) {
        var champInputNomGroupe = $("#formParams input#nomGroupe").val().trim();
        var champInputTelGroupe = $("#formParams input#telGroupe").val().trim();
        var champInputEmailGroupe = $("#formParams input#emailGroupe").val().trim();

        if (testChampGroupeVide(champInputNomGroupe) == false) {
            nonValideGroupe("#formParams input#nomGroupe");
        } else {
            valideGroupe("#formParams input#nomGroupe");
        }
        if (testIfEmailGroupeIsValid(champInputEmailGroupe) == false) {
            nonValideGroupe("#formParams input#emailGroupe");
        } else {
            valideGroupe("#formParams input#emailGroupe");
        }
        if (testIfTelGroupeIsValid(champInputTelGroupe) == false) {
            nonValideGroupe("#formParams input#telGroupe");
        } else {
            valideGroupe("#formParams input#telGroupe");
        }

        if (testChampGroupeVide(champInputNomGroupe) == false || testIfEmailGroupeIsValid(champInputEmailGroupe) == false ||
            testIfTelGroupeIsValid(champInputTelGroupe) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    function testChampGroupeVide(value) {
        if (value == "") {
            return false;
        }
    }

    function testIfEmailGroupeIsValid(value) {
        var regexEmailIsValid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!value.match(regexEmailIsValid)) {
            return false;
        }
    }

    function testIfTelGroupeIsValid(value) {
        var regexTelIsValid = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;
        if (!value.match(regexTelIsValid)) {
            return false;
        }
    }

    function nonValideGroupe(selector) {
        return $(selector).css('border-color', '#c11a1a') + $(selector).next('span').fadeIn();
    }

    function valideGroupe(selector) {
        return $(selector).css('border-color', '#99d601') + $(selector).next('span').fadeOut();
    }

    // Test du formulaire de radiation de l'adhérent - test des input
    $("#formAdherent input#dateSortieCancelled").on("blur", testInputTypeTextForCancelled);

    function testInputTypeTextForCancelled() {
        var champInput = $(this).val();
        if (champInput == "") {
            $(this).css('border-color', '#c11a1a');
            $(this).removeClass("green-border");
            $(this).next('span').fadeIn();
        } else {
            $(this).addClass("green-border");
            $(this).next('span').hide();
        }
    }

    // Evenement au click du bouton radiation
    $("#formAdherent input#cancelledMember").on("click", function (event) {
        var selectDateSortieCancelled = $("#formAdherent input#dateSortieCancelled").val().trim();

        if (testChampsVide(selectDateSortieCancelled) == false) {
            nonValide("#formAdherent input#dateSortieCancelled");
        } else {
            valide("#formAdherent input#dateSortieCancelled");
        }

        if (testChampsVide(selectDateSortieCancelled) == false) {
            event.preventDefault();
        } else {
            return true;
        }
    });

    var menuTop;
    var speed = 900;
    menuTop = parseInt( $("#ei_tpl_fullsite").css("padding-top") ) - 10;
    $("a#HSec0").on("click", function() {
        console.log("Plop");
    });
});