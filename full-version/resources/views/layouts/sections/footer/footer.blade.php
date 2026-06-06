@php
$containerFooter = isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-center py-4">
            <div class="text-body small text-muted">
                &#169; <script>document.write(new Date().getFullYear());</script>
                <span class="fw-bold text-primary ms-1">Fix-It Dashboard</span>. جميع الحقوق محفوظة
            </div>
        </div>
    </div>
</footer>
<!-- / Footer -->
