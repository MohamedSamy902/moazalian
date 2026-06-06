  <footer>
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="footer-logo">{{ __('Moaz Alian') }}</div>
          <div class="footer-desc">{{ __('Egyptian media figure, researcher, and debater specializing in comparative religion and Islamic-Christian dialogue since 2004.') }}</div>
        </div>
        <div class="col-md-2 col-6">
          <div
            style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Content') }}</div>
          <a href="videos.html" class="footer-link">{{ __('Videos') }}</a>
          <a href="debates.html" class="footer-link">{{ __('Debates') }}</a>
          <a href="articles.html" class="footer-link">{{ __('Articles') }}</a>
        </div>
        <div class="col-md-2 col-6">
          <div
            style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Library') }}</div>
          <a href="references.html" class="footer-link">{{ __('References') }}</a>
          <a href="books.html" class="footer-link">{{ __('PDF Books') }}</a>
          <a href="about.html" class="footer-link">{{ __('About Moaz') }}</a>
          <a href="contact.html#support" class="footer-link">{{ __('Support') }}</a>
        </div>
        <div class="col-md-4">
          <div
            style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Subscribe to Newsletter') }}</div>
          <p class="footer-desc" style="margin-bottom:.9rem">{{ __('Receive the latest lectures and articles in your inbox') }}</p>
          <div class="d-flex gap-2" id="newsletter-form">
            <input type="email" id="newsletter-email" class="form-ctrl" placeholder="{{ __('Your Email') }}" style="flex:1"
              aria-label="{{ __('Email Address') }}" />
            <button class="btn btn-gold px-3" id="newsletter-btn" aria-label="{{ __('Send') }}" onclick="submitNewsletter()"><i
                class="bi bi-send"></i></button>
          </div>
          <div id="newsletter-msg" style="margin-top:.5rem;font-size:.8rem;display:none;"></div>
          <div class="footer-socials mt-3" style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <a href="https://www.youtube.com/c/moazalian" target="_blank" class="footer-soc-btn" aria-label="يوتيوب"><i
                class="bi bi-youtube"></i></a>
            <a href="https://www.facebook.com/moazalian" target="_blank" class="footer-soc-btn" aria-label="فيسبوك"><i
                class="bi bi-facebook"></i></a>
            <a href="https://t.me/moazalian" target="_blank" class="footer-soc-btn" aria-label="تيليغرام"><i
                class="bi bi-telegram"></i></a>
            <a href="https://twitter.com/moazalian" target="_blank" class="footer-soc-btn" aria-label="تويتر"><i
                class="bi bi-twitter-x"></i></a>
            <a href="https://www.tiktok.com/@moazalian" target="_blank" class="footer-soc-btn" aria-label="تيك توك"><i
                class="bi bi-tiktok"></i></a>
          </div>
        </div>
      </div>
      <hr class="footer-divider" />
      <div class="footer-bottom">
        {{ __('All Rights Reserved ©') }} {{ date('Y') }} {{ __('Moaz Alian — Official Site for Comparative Religion Content') }}
      </div>
    </div>
  </footer>
