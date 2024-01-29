$(document).ready(function () {
    $('#connexions-form').submit(function (event) {
        var fields = $('#connexions-form input');
        fields.each(function () {
            var fieldName = $(this).attr('name');
            var value = $(this).val().trim();
            // Vérification générale de champ vide
            if (!value) {
                showError($(this), 'Ce champ est requis.');
                event.preventDefault();
            } else {
                if (fieldName === 'email' && !isValidEmail(value)) {
                    showError($(this), 'Veuillez saisir une adresse email valide.');
                    event.preventDefault();
                }else if (fieldName === 'password' && !isValidPassword(value)) {
                    showError($(this), 'Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule et un caractère spécial.');
                    event.preventDefault();
                }else {
                    hideError($(this));
                }
            }
        });
    });
    function showError(input, message) {
        var errorElement = input.next('.error');

        if (!errorElement.length) {
            errorElement = $('<p>').addClass('error');
            input.after(errorElement);
        }

        errorElement.text(message);
    }
    function isValidEmail(value) {
        // Utilisez une expression régulière appropriée pour la validation de l'email
        // Cette expression est très simple et peut ne pas couvrir tous les cas possibles
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }
    function isValidPassword(value) {
        // Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{6,}$/;
    
        return passwordRegex.test(value);
    }

    function hideError(input) {
        var errorElement = input.next('.error');
        if (errorElement.length) {
            errorElement.remove();
        }
    }
});









$(document).ready(function() {
    // Configuration du modal pour afficher les détails du post-it
    $('#modal-postit').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 400,
        height: 'auto',
        buttons: {
            'Modifier': function() {
                // Rediriger vers la page de modification du post-it
                $('#modal-modification')
                    .data('url', $(this).data('modifier-url'))
                    .dialog('open');
            },
            'Supprimer': function() {
                // Afficher la boîte de dialogue de confirmation de suppression
                $('#dialog-confirm')
                    .data('url', $(this).data('supprimer-url'))
                    .dialog('open');
            },
            'Fermer': function() {
                $(this).dialog('close');
            }
        }
    });

    // Ouvrir le modal pour afficher les détails du post-it
    $('.postit-title').click(function(event) {
        event.preventDefault();
        var titre = $(this).text();
       // Récupérer le contenu complet du post-it
       var contentFull = $(this).siblings('.postit-content-full').text();
        var supprimerUrl = $(this).data('supprimer-url');
        var modifierUrl = $(this).data('modifier-url');

        // Mettre à jour le contenu du modal avec les données du post-it
        $('#modal-postit-titre').text(titre);
        $('#modal-postit-contenu').text(contentFull);
        $('#modal-postit').data('supprimer-url', supprimerUrl);
        $('#modal-postit').data('modifier-url', modifierUrl);

        // Ouvrir le modal
        $('#modal-postit').dialog('open');
    });

    // Initialisation du modal de modification
$('#modal-modification').dialog({
    autoOpen: false,
    modal: true,
    resizable: false,
    width: 400,
    height: 'auto'
});

// Ouvrir le modal de modification lors du clic sur le bouton "Modifier" dans le modal d'affichage du post-it
$('#modal-postit').on('click', '#btn-modifier', function() {
    var titres = $(this).value; // Correction ici
    // Récupérer le contenu complet du post-it
    var contentFulls = $(this).siblings('.postit-content-full').value; // Correction ici
    // Pré-remplir les champs du formulaire de modification
    $('#titre-modification').val(titres);
    $('#contenu-modification').val(contentFulls);

    // Ouvrir le modal de modification
    $('#modal-modification').dialog('open');
});


// Gérer la soumission du formulaire de modification
$('#form-modification').submit(function(event) {
    event.preventDefault();

    // Récupérer les valeurs des champs
    var nouveauTitre = $('#titre-modification').val(); // Correction ici
    var nouveauContenu = $('#contenu-modification').val(); // Correction ici

    // Envoyer les données de modification au serveur via AJAX
    $.ajax({
        url: 'modifier_postit.php', // Le script PHP qui gère la modification
        method: 'POST',
        data: {
            nouveauTitre: nouveauTitre,
            nouveauContenu: nouveauContenu
        },
        success: function(response) {
            // Traiter la réponse du serveur
            alert('Post-it modifié avec succès !');
            $('#modal-modification').dialog('close');
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs
            alert('Une erreur s\'est produite lors de la modification du post-it.');
            console.error(error);
        }
    });
});

    

    // Configuration de la boîte de dialogue de confirmation de suppression
    $('#dialog-confirm').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        height: 'auto',
        width: 400,
        buttons: {
            'Annuler': function() {
                $(this).dialog('close');
            },
            'Supprimer': function() {
                window.location.href = $(this).data('url');
            }
        }
    });

    // Ouvrir la boîte de dialogue de confirmation de suppression
    $('body').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        $('#dialog-confirm').data('url', $(this).attr('href')).dialog('open');
    });
});
