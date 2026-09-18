// Mobile menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('.nav');

if (menuToggle && nav) {
  const setMenuOpen = (open) => {
    nav.classList.toggle('open', open);
    menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('nav-open', open);
  };

  menuToggle.addEventListener('click', () => {
    setMenuOpen(!nav.classList.contains('open'));
  });

  nav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => setMenuOpen(false));
  });

  document.addEventListener('click', (e) => {
    if (!nav.classList.contains('open')) return;
    const target = e.target;
    if (target instanceof Node && !nav.contains(target) && !menuToggle.contains(target)) {
      setMenuOpen(false);
    }
  });
}

// Scroll reveal
const revealElements = document.querySelectorAll('.reveal');
if (revealElements.length) {
  const revealObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.06, rootMargin: '0px 0px -16px 0px' }
  );

  revealElements.forEach((el) => revealObserver.observe(el));
}

// Product category filters
const filterBtns = document.querySelectorAll('.filter-btn');
const productCards = document.querySelectorAll('.product-card[data-category]');

const categorySlugMap = {
  'oturma-odasi': 'Oturma Odası',
  'yatak-odasi': 'Yatak Odası',
  'yemek-odasi': 'Yemek Odası',
};

let applyFilter = null;

if (filterBtns.length && productCards.length) {
  applyFilter = (category) => {
    filterBtns.forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.filter === category);
    });

    productCards.forEach((card) => {
      const match = category === 'all' || card.dataset.category === category;
      card.classList.toggle('is-hidden', !match);
    });
  };

  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => applyFilter(btn.dataset.filter));
  });

  document.querySelectorAll('.category-card[data-category], .service-card[data-category]').forEach((card) => {
    card.addEventListener('click', (e) => {
      e.preventDefault();
      const category = card.dataset.category;
      applyFilter(category);
      scrollToHash('#products');
    });
  });
}

const parseCategoryFromHash = (hash) => {
  if (!hash) return null;
  const match = hash.match(/^#products\/([^/?#]+)$/);
  if (!match) return null;
  return categorySlugMap[match[1]] || null;
};

const getScrollHash = (hash) => {
  if (!hash) return hash;
  return hash.includes('/') ? hash.split('/')[0] : hash;
};

const applyFilterFromHash = (hash) => {
  if (!applyFilter || !hash) return;

  const category = parseCategoryFromHash(hash);
  if (category) {
    applyFilter(category);
    return;
  }

  if (getScrollHash(hash) === '#products') {
    applyFilter('all');
  }
};

// Soft in-page scroll
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const headerEl = document.querySelector('.header');
const headerOffset = () => (headerEl ? headerEl.offsetHeight + 16 : 96);

let scrollAnimationId = null;

const easeOutQuart = (t) => 1 - (1 - t) ** 4;

const cancelScrollAnimation = () => {
  if (scrollAnimationId !== null) {
    cancelAnimationFrame(scrollAnimationId);
    scrollAnimationId = null;
  }
};

const smoothScrollTo = (targetTop) => {
  cancelScrollAnimation();

  const start = window.scrollY;
  const distance = targetTop - start;
  if (Math.abs(distance) < 4) return;

  const duration = Math.min(880, Math.max(520, Math.abs(distance) * 0.55));
  const startTime = performance.now();

  const step = (now) => {
    const progress = Math.min((now - startTime) / duration, 1);
    window.scrollTo(0, start + distance * easeOutQuart(progress));

    if (progress < 1) {
      scrollAnimationId = requestAnimationFrame(step);
    } else {
      scrollAnimationId = null;
    }
  };

  scrollAnimationId = requestAnimationFrame(step);
};

const scrollToElement = (element, animate = true) => {
  if (!element) return;

  const top = element.getBoundingClientRect().top + window.scrollY - headerOffset();

  if (!animate || prefersReducedMotion) {
    cancelScrollAnimation();
    window.scrollTo(0, top);
    return;
  }

  smoothScrollTo(top);
};

const scrollToHash = (hash, animate = true) => {
  if (!hash || hash === '#') return;
  scrollToElement(document.querySelector(getScrollHash(hash)), animate);
};

const isHomePage = () => {
  const page = window.location.pathname.split('/').pop();
  return !page || page === 'index.php';
};

const isLinkToHome = (pathname) => {
  const page = pathname.split('/').pop();
  return !page || page === 'index.php';
};

document.querySelectorAll('a[href*="#"]').forEach((link) => {
  link.addEventListener('click', (e) => {
    const href = link.getAttribute('href');
    if (!href || href === '#') return;

    const url = new URL(href, window.location.href);
    if (!url.hash) return;

    const scrollHash = getScrollHash(url.hash);

    const sameDocument = href.startsWith('#') || (isHomePage() && isLinkToHome(url.pathname));

    if (sameDocument) {
      e.preventDefault();
      history.pushState(null, '', url.hash);
      applyFilterFromHash(url.hash);
      scrollToHash(scrollHash);
      return;
    }

    // Ürün detay vb. sayfalardan ana sayfa bölümlerine geçiş
    if (isLinkToHome(url.pathname)) {
      e.preventDefault();
      window.location.assign(`index.php${url.hash}`);
    }
  });
});

const handleInitialHash = () => {
  const hash = window.location.hash;
  if (!hash) return;

  applyFilterFromHash(hash);
  requestAnimationFrame(() => scrollToHash(hash, false));
};

window.addEventListener('load', handleInitialHash);
window.addEventListener('hashchange', () => {
  applyFilterFromHash(window.location.hash);
  scrollToHash(window.location.hash, false);
});

// Product image lightbox
const productImageTrigger = document.getElementById('product-image-trigger');
const productImageLightbox = document.getElementById('product-image-lightbox');

if (productImageTrigger && productImageLightbox) {
  const lightboxClose = productImageLightbox.querySelector('.image-lightbox__close');

  const openLightbox = () => {
    productImageLightbox.hidden = false;
    requestAnimationFrame(() => {
      productImageLightbox.classList.add('is-open');
    });
    document.body.classList.add('lightbox-open');
    lightboxClose?.focus();
  };

  const closeLightbox = () => {
    productImageLightbox.classList.remove('is-open');
    document.body.classList.remove('lightbox-open');
    productImageTrigger.focus();
    window.setTimeout(() => {
      if (!productImageLightbox.classList.contains('is-open')) {
        productImageLightbox.hidden = true;
      }
    }, 250);
  };

  productImageTrigger.addEventListener('click', openLightbox);
  lightboxClose?.addEventListener('click', closeLightbox);

  productImageLightbox.addEventListener('click', (e) => {
    if (e.target === productImageLightbox) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && productImageLightbox.classList.contains('is-open')) {
      closeLightbox();
    }
  });
}

// Hero slide crossfade (5s interval, 1.5s fade)
const heroSlides = document.querySelectorAll('.hero__slide');
if (heroSlides.length > 1 && !prefersReducedMotion) {
  let heroIndex = 0;

  setInterval(() => {
    heroSlides[heroIndex].classList.remove('is-active');
    heroIndex = (heroIndex + 1) % heroSlides.length;
    heroSlides[heroIndex].classList.add('is-active');
  }, 5000);
}
