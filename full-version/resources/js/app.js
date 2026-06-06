import './bootstrap';
import './ajax-modal';

// ── Bootstrap 5 Modal helpers ────────────────────────────────────────────────
// Global helpers: window.bsShow(el), window.bsHide(el)
// Also patches $.fn.modal lazily when called
window.bsShow = function (elOrId) {
    const el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    const instance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    instance.show();
};
window.bsHide = function (elOrId) {
    const el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    const instance = bootstrap.Modal.getInstance(el);
    if (instance) instance.hide();
};

// Lazy jQuery bridge — patches $.fn.modal on first use
function patchJqueryModal() {
    if (typeof $ === 'undefined' || typeof bootstrap === 'undefined') return;
    if ($.fn._modalPatched) return;
    $.fn.modal = function (action) {
        this.each(function () {
            if (action === 'show') window.bsShow(this);
            else if (action === 'hide') window.bsHide(this);
            else if (action === 'toggle') {
                const inst = bootstrap.Modal.getInstance(this) || new bootstrap.Modal(this);
                inst.toggle();
            }
        });
        return this;
    };
    $.fn._modalPatched = true;
}
document.addEventListener('DOMContentLoaded', patchJqueryModal);
window.addEventListener('load', patchJqueryModal);


// Standardized Image Preview
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('image-preview-input')) {
        const file = e.target.files[0];
        const previewId = e.target.getAttribute('data-preview');
        const previewContainer = document.getElementById(previewId);

        if (file && previewContainer) {
            const reader = new FileReader();
            reader.onload = function (event) {
                previewContainer.innerHTML = `<img src="${event.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">`;
            };
            reader.readAsDataURL(file);
        }
    }
});

/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);
