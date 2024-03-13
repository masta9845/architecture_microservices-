/**
 * ce script permet à l'utilisateur de rechercher des utilisateurs par leur nom dans une liste dynamique 
 * (#listutilisateurs) à mesure qu'il saisit du texte dans un champ de recherche (#searchUser).
 * Si le nom de l'utilisateur correspond à la saisie, il reste visible, sinon il est masqué.
 * *
 */
function goBack() {
    window.history.back();
}

$(document).ready(function () {
    $("#searchUser").on("input", function () {
        var searchValue = $(this).val().toLowerCase();
        $("#listutilisateurs li").each(function () {
            var userFullName = $(this).text().toLowerCase();
            $(this).toggle(userFullName.indexOf(searchValue) > -1);
        });
    });

    // Fonction de validation du formulaire
    $('#form-postit').submit(function (event) {
        var fields = $('#form-postit input[type="text"], #form-postit textarea').not('#searchUser'); // Sélectionne tous les champs du formulaire a l'exception du champs de recherche

        fields.each(function () {
            var fieldName = $(this).attr('name');
            var value = $(this).val().trim();

            // Vérification générale de champ vide
            if (!value) {
                showError($(this), 'Ce champ est requis.');
                event.preventDefault(); 
            } else {
                if (fieldName === 'titre' && value.length > 150) {
                    showError($(this), 'Le titre ne doit pas dépasser 150 caractères.');
                    event.preventDefault();
                } else {
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
        input.addClass('error-input');//champs-rouge
    }

    function hideError(input) {
        var errorElement = input.next('.error');
        if (errorElement.length) {
            errorElement.remove();
        }
        input.removeClass('error-input');//champs-roue
    }



});
