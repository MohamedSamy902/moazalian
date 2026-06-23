  <footer>
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="footer-logo">{{ __('Moaz Alian') }}</div>
          <div class="footer-desc">{{ __('Egyptian media figure, researcher, and debater specializing in comparative religion and Islamic-Christian dialogue since 2004.') }}</div>
        </div>
        <div class="col-md-2 col-6">
          <div style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Content') }}</div>
          <a href="{{ route('videos.index') }}" class="footer-link">{{ __('Videos') }}</a>
          {{-- [DISABLED] <a href="#" class="footer-link">{{ __('Debates') }}</a> --}}
          <a href="{{ route('articles.index') }}" class="footer-link">{{ __('Articles') }}</a>
          <a href="{{ route('courses.index') }}" class="footer-link">{{ __('Courses') }}</a>
        </div>
        <div class="col-md-2 col-6">
          <div style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Library') }}</div>
          <a href="{{ route('references') }}" class="footer-link">{{ __('References') }}</a>
          <a href="{{ route('books.index') }}" class="footer-link">{{ __('PDF Books') }}</a>
          <a href="{{ route('about') }}" class="footer-link">{{ __('About Moaz') }}</a>
          <a href="{{ route('contact') }}#support" class="footer-link">{{ __('Support') }}</a>
        </div>
        <div class="col-md-4">
          <div style="font-size:.78rem;color:var(--gold);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:.8rem">
            {{ __('Subscribe to Newsletter') }}</div>
          <p class="footer-desc" style="margin-bottom:.9rem">{{ __('Receive the latest lectures and articles in your inbox') }}</p>
          <form id="newsletter-form" onsubmit="submitNewsletter(event)">
            @csrf
            <div class="d-flex gap-2">
              <input type="email" name="email" id="newsletter-email" class="form-ctrl" placeholder="{{ __('Your Email') }}" style="flex:1"
                aria-label="{{ __('Email Address') }}" required />
              <button type="submit" class="btn btn-gold px-3" id="newsletter-btn" aria-label="{{ __('Send') }}">
                <i class="bi bi-send"></i>
              </button>
            </div>
          </form>
          <div id="newsletter-msg" style="margin-top:.5rem;font-size:.8rem;display:none;"></div>

          {{-- Social links from Settings/Sections --}}
          <div class="footer-socials mt-3" style="display:flex;gap:.6rem;flex-wrap:wrap;">
            @php
              $footerSections = $sections ?? collect();
              $yt  = optional($footerSections->get('social_youtube'))->value  ?: 'https://www.youtube.com/c/moazalian';
              $fb  = optional($footerSections->get('social_facebook'))->value ?: 'https://www.facebook.com/moazalian';
              $tg  = optional($footerSections->get('social_telegram'))->value ?: 'https://t.me/moazalian';
              $tw  = optional($footerSections->get('social_twitter'))->value  ?: 'https://twitter.com/moazalian';
            @endphp
            <a href="{{ $yt }}" target="_blank" class="footer-soc-btn" aria-label="يوتيوب"><i class="bi bi-youtube"></i></a>
            <a href="{{ $fb }}" target="_blank" class="footer-soc-btn" aria-label="فيسبوك"><i class="bi bi-facebook"></i></a>
            <a href="{{ $tg }}" target="_blank" class="footer-soc-btn" aria-label="تيليغرام"><i class="bi bi-telegram"></i></a>
            <a href="{{ $tw }}" target="_blank" class="footer-soc-btn" aria-label="تويتر"><i class="bi bi-twitter-x"></i></a>
          </div>
        </div>
      </div>
      <hr class="footer-divider" />
      <div class="footer-bottom">
        {{ __('All Rights Reserved ©') }} {{ date('Y') }} {{ __('Moaz Alian — Official Site for Comparative Religion Content') }}
      </div>
    </div>
  </footer>
