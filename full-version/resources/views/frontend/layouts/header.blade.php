  <!-- Reading Progress Bar -->
  <div id="reading-progress-bar"
    style="position:fixed;top:0;right:0;left:0;height:3px;background:linear-gradient(to left,var(--gold),var(--gold-xl));width:0%;z-index:9999;transition:width .1s linear;pointer-events:none;">
  </div>

  <header class="fixed-top" style="z-index: 1060; width: 100%;">
    <div id="live-banner" class="live-banner" hidden>
    </div>
    <nav class="navbar navbar-expand-lg" id="nav">
      <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
          {{ __('Moaz Alian') }}
          <small>{{ __('Researcher in Comparative Religion') }}</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nm"
          aria-label="{{ __('Toggle Menu') }}">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nm">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">{{ __('About Moaz') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
            <li class="nav-item dropdown dropdown-hover">
              <a class="nav-link dropdown-toggle" href="#" id="libraryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __('Library') }}</a>
              <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="libraryDropdown" style="background: var(--card); border: 1px solid var(--border) !important; border-radius: 12px; text-align: right;">
                <li><a class="dropdown-item py-2" href="references.html" style="color: var(--text); font-size: 0.9rem;">{{ __('References') }}</a></li>
                <li><a class="dropdown-item py-2" href="books.html" style="color: var(--text); font-size: 0.9rem;">{{ __('Books') }}</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('articles.index') }}" style="color: var(--text); font-size: 0.9rem;">{{ __('Articles') }}</a></li>
                <li><a class="dropdown-item py-2" href="videos.html" style="color: var(--text); font-size: 0.9rem;">{{ __('Videos') }}</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="dawah.html">{{ __('Dawah to Islam') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.html">{{ __('Contact') }}</a></li>

            <!-- Language Switcher -->
            
          </ul>

          <div id="auth-links-container" class="ms-lg-auto me-lg-3 my-2 my-lg-0"></div>
          <a href="contact.html#support" class="btn btn-gold btn-sm d-flex align-items-center gap-2 ms-lg-2">
            <i class="bi bi-heart-fill"></i>{{ __('Support Channel') }}
          </a>
          
          @php
            $currentLocale = app()->getLocale();
            $otherLocale = $currentLocale === 'ar' ? 'en' : 'ar';
            $switchUrl = LaravelLocalization::getLocalizedURL($otherLocale, null, [], true);
          @endphp
          <a href="{{ $switchUrl }}" id="lang-toggle" class="theme-toggle ms-2 fw-bold text-gold d-flex align-items-center justify-content-center" style="font-size: 0.8rem; text-decoration: none;">
            {{ strtoupper($otherLocale) }}
          </a>

          <button id="theme-toggle" class="theme-toggle ms-2" aria-label="تبديل المظهر">
            <i class="bi bi-moon-fill dark-icon"></i>
            <i class="bi bi-sun-fill light-icon"></i>
          </button>
        </div>
      </div>
    </nav>
  </header>
