/*
 * Universal Modal AJAX Handler
 * ----------------------------
 * Handles opening modals dynamically and submitting forms via AJAX for a fast SPA experience.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Open Modal via AJAX
    document.body.addEventListener('click', function (e) {
        let trigger = e.target.closest('[data-ajax-modal]');
        if (!trigger) return;

        e.preventDefault();
        let url = trigger.getAttribute('href') || trigger.getAttribute('data-url');
        let modalId = trigger.getAttribute('data-target-modal') || '#universalModal';
        
        // Setup Modal Container if not exists
        let modalElement = document.querySelector(modalId);
        if (!modalElement) {
            document.body.insertAdjacentHTML('beforeend', `
                <div class="modal fade" id="${modalId.replace('#', '')}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" id="${modalId.replace('#', '')}Content">
                            <div class="text-center p-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            modalElement = document.querySelector(modalId);
        }

        let modal = new bootstrap.Modal(modalElement);
        modal.show();

        // Fetch Content
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            $(modalElement).find('.modal-content').html(html);
        })
        .catch(error => {
            console.error('Error loading modal content:', error);
            modalElement.querySelector('.modal-content').innerHTML = `
                <div class="modal-body text-center">
                    <i class="ti ti-alert-circle text-danger fs-1 mb-3"></i>
                    <h4>حدث خطأ!</h4>
                    <p>تعذر تحميل البيانات. يرجى المحاولة لاحقاً.</p>
                </div>
            `;
        });
    });

    // 2. Handle Form Submission within Modal
    document.body.addEventListener('submit', function (e) {
        let form = e.target;
        if (!form.closest('.modal')) return;
        if (form.hasAttribute('data-no-ajax')) return;

        e.preventDefault();
        
        let submitBtn = form.querySelector('[type="submit"]');
        let originalBtnText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الحفظ...';
        }

        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            let data = await response.json().catch(() => ({}));
            if (!response.ok) {
                if (response.status === 422) {
                    // Validation Errors
                    // Display them in the form
                    Object.keys(data.errors || {}).forEach(key => {
                        let input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            let errorDiv = input.nextElementSibling;
                            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                input.insertAdjacentHTML('afterend', `<div class="invalid-feedback">${data.errors[key][0]}</div>`);
                            } else {
                                errorDiv.innerText = data.errors[key][0];
                            }
                        }
                    });
                } else {
                    Swal.fire('خطأ', data.message || 'حدث خطأ غير متوقع!', 'error');
                }
                throw new Error('Validation or server error');
            }
            return data;
        })
        .then(data => {
            // Success
            let modalElement = form.closest('.modal');
            let modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();

            Swal.fire({
                title: 'تم بنجاح!',
                text: data.message || 'تم حفظ البيانات بنجاح.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Reload DataTables if exist
            if (typeof window.LaravelDataTables !== 'undefined') {
                Object.keys(window.LaravelDataTables).forEach(id => {
                    window.LaravelDataTables[id].ajax.reload(null, false);
                });
            } else {
                // Otherwise reload page
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Submission error:', error);
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    });
});
