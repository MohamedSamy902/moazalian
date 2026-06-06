<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js', 'resources/assets/vendor/libs/@algolia/autocomplete-js.js'])

@if ($configData['hasCustomizer'])
  @vite('resources/assets/vendor/libs/pickr/pickr.js')
@endif

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/libs/hammer/hammer.js', 'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->

<!-- Modal Helpers: bsShow/bsHide available globally after this point -->
<script>
// window.bootstrap is set by bootstrap.js above
window.bsShow = function (elOrId) {
    var el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show();
};
window.bsHide = function (elOrId) {
    var el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    var inst = bootstrap.Modal.getInstance(el);
    if (inst) inst.hide();
};
</script>


<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])
<!-- END: Theme JS-->

<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<!-- app JS -->
@vite(['resources/js/app.js'])
<!-- END: app JS-->
