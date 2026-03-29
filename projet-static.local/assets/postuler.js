document.addEventListener('DOMContentLoaded', function () {
    var nomField = document.getElementById('nom');
    nomField.value = nomField.value.toUpperCase();
    nomField.addEventListener('blur', function () {
        this.value = this.value.toUpperCase();
    });

    // Vérification du CV à la sélection
    document.getElementById('cv').addEventListener('change', function () {
        var formats = ['.pdf', '.doc', '.docx', '.odt', '.rtf', '.jpg', '.jpeg', '.png'];
        var errCv = document.getElementById('err-cv');
        var file = this.files[0];

        if (!file) { errCv.style.display = 'none'; return; }

        var ext = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));

        if (formats.indexOf(ext) === -1) {
            errCv.textContent = 'Format non autorisé : ' + ext;
            errCv.style.display = 'block';
            this.value = '';
        } else if (file.size > 2 * 1024 * 1024) {
            errCv.textContent = 'Le fichier dépasse 2 Mo.';
            errCv.style.display = 'block';
            this.value = '';
        } else {
            errCv.style.display = 'none';
        }
    });

    // Validation à l'envoi
    document.getElementById('formPostuler').addEventListener('submit', function (e) {
        var valid = true;

        var champs = [
            { id: 'nom',     errId: 'err-nom',     msg: 'Le champ Nom est obligatoire.' },
            { id: 'prenom',  errId: 'err-prenom',  msg: 'Le champ Prénom est obligatoire.' },
            { id: 'message', errId: 'err-message', msg: 'Le champ Message est obligatoire.' }
        ];

        champs.forEach(function (champ) {
            var val = document.getElementById(champ.id).value.trim();
            var err = document.getElementById(champ.errId);
            if (val === '') {
                err.textContent = champ.msg;
                err.style.display = 'block';
                valid = false;
            } else {
                err.style.display = 'none';
            }
        });

        // Email
        var email = document.getElementById('email').value.trim();
        var errEmail = document.getElementById('err-email');
        if (email === '' || email.indexOf('@') === -1) {
            errEmail.style.display = 'block';
            valid = false;
        } else {
            errEmail.style.display = 'none';
        }

        if (!valid) e.preventDefault();
    });

});