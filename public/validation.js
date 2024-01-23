$(document).ready(function () {
    $('#inscription-form').submit(function (event) {
        var fields = $('#inscription-form input');

        fields.each(function () {
            var fieldName = $(this).attr('name');
            var value = $(this).val().trim();

            // Vérification générale de champ vide
            if (!value) {
                showError($(this), 'Ce champ est requis.');
                event.preventDefault();
            } else {
                // Vérification spécifique pour le nom et prénom
                if ((fieldName === 'nom' || fieldName === 'prenom') && !isValidName(value)) {
                    showError($(this), 'Le ' + fieldName + ' doit contenir au moins 5 lettres et ne doit contenir que des lettres.');
                    event.preventDefault();
                } else if (fieldName === 'email' && !isValidEmail(value)) {
                    showError($(this), 'Veuillez saisir une adresse email valide.');
                    event.preventDefault();
                } else if (fieldName === 'password-confirm' && value !== $('#password').val()) {
                    showError($(this), 'La confirmation du mot de passe ne correspond pas au mot de passe saisi.');
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
    }

    function hideError(input) {
        var errorElement = input.next('.error');
        if (errorElement.length) {
            errorElement.remove();
        }
    }

    function isValidName(value) {
        return /^[a-zA-Z]{5,}$/.test(value);
    }

    function isValidEmail(value) {
        // Utilisez une expression régulière appropriée pour la validation de l'email
        // Cette expression est très simple et peut ne pas couvrir tous les cas possibles
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }
});
