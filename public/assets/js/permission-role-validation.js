document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.permission-validation-form').forEach(function (form) {
        var permissionError = form.querySelector('[data-permission-error]');

        function validatePermissions() {
            if (!form.hasAttribute('data-require-permission')) {
                return true;
            }

            var checked = form.querySelector('input[type="checkbox"]:checked');
            if (permissionError) {
                permissionError.classList.toggle('d-none', Boolean(checked));
            }

            return Boolean(checked);
        }

        form.querySelectorAll('input, select').forEach(function (field) {
            field.addEventListener('input', function () {
                field.classList.remove('is-invalid');
                validatePermissions();
            });
            field.addEventListener('change', validatePermissions);
        });

        form.addEventListener('submit', function (event) {
            var permissionsValid = validatePermissions();

            if (!form.checkValidity() || !permissionsValid) {
                event.preventDefault();
                event.stopPropagation();

                var firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }

            form.classList.add('was-validated');
        });
    });
});
