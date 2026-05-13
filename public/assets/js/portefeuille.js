document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('recharge-form');
    const balanceElement = document.getElementById('wallet-balance');
    const alertsContainer = document.getElementById('wallet-alerts');

    if (!form || !balanceElement || !alertsContainer) {
        return;
    }

    const renderAlert = function (type, message) {
        alertsContainer.innerHTML = [
            '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">',
            message,
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('');
    };

    const refreshCsrf = function (csrfName, csrfHash) {
        if (!csrfName || !csrfHash) {
            return;
        }

        const csrfInput = form.querySelector('input[name="' + csrfName + '"]');
        if (csrfInput) {
            csrfInput.value = csrfHash;
            csrfInput.defaultValue = csrfHash;
            csrfInput.setAttribute('value', csrfHash);
        }
    };

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton ? submitButton.textContent : '';

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Validation...';
        }

        try {
            const response = await fetch(form.getAttribute('data-ajax-url') || form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const payload = await response.json();
            refreshCsrf(payload.csrfName, payload.csrfHash);

            if (!response.ok || !payload.success) {
                renderAlert('danger', payload.message || 'Une erreur est survenue.');
                return;
            }

            balanceElement.textContent = Number(payload.solde || 0).toFixed(2) + ' €';
            renderAlert('success', payload.message || 'Code valide ! Votre solde a été mis à jour.');

            const codeInput = form.querySelector('#code');
            if (codeInput) {
                codeInput.value = '';
            }
        } catch (error) {
            renderAlert('danger', 'Impossible de traiter la demande pour le moment.');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        }
    });
});
