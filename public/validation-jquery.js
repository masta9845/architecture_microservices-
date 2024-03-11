/**
 *DEBUT Validation des champs du formulaire d'inscription
 */
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
                /**  if ((fieldName === 'nom' || fieldName === 'prenom') && !isValidName(value)) {
                     showError($(this), 'Le ' + fieldName + ' doit contenir au moins 5 lettres et ne doit contenir que des lettres.');
                     event.preventDefault();
                 } else */
                if (fieldName === 'email' && !isValidEmail(value)) {
                    showError($(this), 'Veuillez saisir une adresse email valide.');
                    event.preventDefault();
                } else if (fieldName === 'date_naissance' && !isValidDateOfBirth(value)) {
                    showError($(this), 'Veuillez saisir une date de naissance valide au format AAAA/MM/JJ.');
                    event.preventDefault();
                } else if (fieldName === 'date_naissance' && !isValidDate(value)) {
                    showError($(this), "Vous devez être âgé de 18 ans ou plus.");
                    event.preventDefault();
                } else if (fieldName === 'password' && !isValidPassword(value)) {
                    showError($(this), 'Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule et un caractère spécial.');
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
        input.addClass('error-input');//champs-rouge
    }

    function hideError(input) {
        var errorElement = input.next('.error');
        if (errorElement.length) {
            errorElement.remove();
        }
        input.removeClass('error-input');//champs-roue
    }


    function isValidEmail(value) {
        // Utilisez une expression régulière appropriée pour la validation de l'email
        // Cette expression est très simple et peut ne pas couvrir tous les cas possibles
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    function isValidDateOfBirth(value) {
        // Format attendu : AAAA/MM/JJ
        var dateRegex = /^\d{4}\/\d{2}\/\d{2}$/;

        return dateRegex.test(value);
    }

    /**
     * Gestion de la date de naissance pour voir si l'utilisateur doit avoir 18 ans
     * @param {*} dateString 
     * @returns 
     */
    function isValidDate(dateString) {
        // Convertir la date en objet Date
        var birthDate = new Date(dateString);

        // Récupérer la date actuelle
        var currentDate = new Date();

        // Calculer la différence d'années
        var age = currentDate.getFullYear() - birthDate.getFullYear();

        // Vérifier si l'anniversaire a déjà eu lieu cette année
        if (currentDate.getMonth() < birthDate.getMonth() || (currentDate.getMonth() === birthDate.getMonth() && currentDate.getDate() < birthDate.getDate())) {
            age--;
        }

        // Vérifier si l'âge est supérieur ou égal à 18 ans
        return age >= 18;
    }
    /**
     * gestion de l'expression reguliere du mot de passe on exige a ce que ca contient au moins 8 caracteres, une lettre majuscule, minuscule et un caractere special
     * @param {*} value 
     * @returns 
     */
    function isValidPassword(value) {
        // Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{6,}$/;

        return passwordRegex.test(value);
    }
});
/**
 * Fin validation des champs du formulaire d'inscription
 */

/**
 * DEBUT de la validation des champs du formulaire de Connexion
 */

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
        input.removeClass('error-input');//champs-roue
    }
});

