document.addEventListener('DOMContentLoaded', function () {
    var body = document.body;
    var themeToggle = document.querySelector('[data-theme-toggle]');
    var navToggle = document.querySelector('[data-nav-toggle]');
    var navMenu = document.querySelector('[data-nav-menu]');
    var savedTheme = localStorage.getItem('thikana-theme');

    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
    }

    if (themeToggle) {
        themeToggle.setAttribute('aria-pressed', body.classList.contains('dark-mode') ? 'true' : 'false');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            body.classList.toggle('dark-mode');
            localStorage.setItem('thikana-theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
            themeToggle.setAttribute('aria-pressed', body.classList.contains('dark-mode') ? 'true' : 'false');
        });
    }

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            var isOpen = navMenu.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        navMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                navMenu.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    document.querySelectorAll('[data-file-hint]').forEach(function (input) {
        input.addEventListener('change', function () {
            var hintTarget = document.querySelector(input.getAttribute('data-file-hint'));
            if (!hintTarget) {
                return;
            }

            if (input.files && input.files.length > 0) {
                var file = input.files[0];
                var sizeInMb = (file.size / (1024 * 1024)).toFixed(1);
                hintTarget.textContent = file.name + ' (' + sizeInMb + ' MB)';
            } else {
                hintTarget.textContent = 'No file chosen';
            }
        });
    });

    document.querySelectorAll('form[data-validate-form]').forEach(function (form) {
        var fields = form.querySelectorAll('input, select, textarea');
        var formStatus = form.querySelector('[data-form-status]');

        function clearFieldError(field) {
            field.classList.remove('input-invalid');
            field.removeAttribute('aria-invalid');
            var next = field.nextElementSibling;
            if (next && next.classList.contains('field-error')) {
                next.remove();
            }
        }

        function showFieldError(field, message) {
            clearFieldError(field);
            field.classList.add('input-invalid');
            field.setAttribute('aria-invalid', 'true');
            var error = document.createElement('p');
            error.className = 'field-error';
            error.textContent = message;
            field.insertAdjacentElement('afterend', error);
        }

        function validateFileField(field) {
            if (!field.files || !field.files.length) {
                field.setCustomValidity('');
                return;
            }

            var file = field.files[0];
            var maxSizeMb = parseFloat(field.dataset.maxSizeMb || '0');
            var allowedExtensions = (field.dataset.extensions || '')
                .split(',')
                .map(function (item) { return item.trim().toLowerCase(); })
                .filter(Boolean);
            var extension = file.name.split('.').pop().toLowerCase();

            field.setCustomValidity('');

            if (maxSizeMb > 0 && file.size > maxSizeMb * 1024 * 1024) {
                field.setCustomValidity('Please upload a file under ' + maxSizeMb + ' MB.');
                return;
            }

            if (allowedExtensions.length && allowedExtensions.indexOf(extension) === -1) {
                field.setCustomValidity('Please upload one of these file types: ' + allowedExtensions.join(', ') + '.');
            }
        }

        fields.forEach(function (field) {
            if (field.type === 'file') {
                field.addEventListener('change', function () {
                    validateFileField(field);
                    if (field.checkValidity()) {
                        clearFieldError(field);
                    }
                });
            } else {
                field.addEventListener('input', function () {
                    if (field.checkValidity()) {
                        clearFieldError(field);
                    }
                });
                field.addEventListener('change', function () {
                    if (field.checkValidity()) {
                        clearFieldError(field);
                    }
                });
            }
        });

        form.addEventListener('submit', function (event) {
            var isValid = true;

            fields.forEach(function (field) {
                if (field.type === 'file') {
                    validateFileField(field);
                }

                if (!field.checkValidity()) {
                    isValid = false;
                    showFieldError(field, field.validationMessage);
                } else {
                    clearFieldError(field);
                }
            });

            if (!isValid) {
                event.preventDefault();
                if (formStatus) {
                    formStatus.textContent = 'Please fix the highlighted fields and try again.';
                    formStatus.classList.add('visible');
                }

                var firstInvalidField = form.querySelector('.input-invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            } else if (formStatus) {
                formStatus.textContent = '';
                formStatus.classList.remove('visible');
            }
        });
    });

    document.querySelectorAll('[data-compare-limit]').forEach(function (form) {
        var summary = form.querySelector('[data-compare-summary]');
        var submitButton = form.querySelector('[data-compare-submit]');

        function updateCompareUI() {
            var checked = Array.prototype.slice.call(form.querySelectorAll('input[name="listing_ids[]"]:checked'));
            var cards = form.querySelectorAll('[data-compare-card]');

            cards.forEach(function (card) {
                var checkbox = card.querySelector('input[name="listing_ids[]"]');
                if (checkbox) {
                    card.classList.toggle('is-selected', checkbox.checked);
                }
            });

            if (summary) {
                if (checked.length === 0) {
                    summary.textContent = 'Pick 2 or 3 listings to unlock the comparison table.';
                } else if (checked.length === 1) {
                    summary.textContent = '1 listing selected. Pick at least 1 more to compare.';
                } else {
                    summary.textContent = checked.length + ' listings selected. Scroll down to review the table.';
                }
            }

            if (submitButton) {
                submitButton.disabled = checked.length < 2;
            }
        }

        form.addEventListener('change', function () {
            var checked = form.querySelectorAll('input[name="listing_ids[]"]:checked');
            if (checked.length > 3) {
                checked[checked.length - 1].checked = false;
                alert('Please compare up to 3 listings at a time.');
            }
            updateCompareUI();
        });

        form.addEventListener('submit', function (event) {
            var checked = form.querySelectorAll('input[name="listing_ids[]"]:checked');
            if (checked.length < 2) {
                event.preventDefault();
                if (summary) {
                    summary.textContent = 'Please select at least 2 listings before comparing.';
                }
            }
        });

        updateCompareUI();
    });
});
