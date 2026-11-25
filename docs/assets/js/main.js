(function () {
  var toggle = document.querySelector('.menu-toggle');
  var nav = document.querySelector('.navlinks');
  var header = document.querySelector('header');

  // Mobile menu
  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open);
  });
  nav.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    });
  });

  // Header shadow on scroll
  window.addEventListener('scroll', function () {
    header.classList.toggle('scrolled', window.scrollY > 10);
  }, { passive: true });

  // Reveal cards as they scroll into view
  var items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('visible'); io.unobserve(en.target); }
      });
    }, { threshold: 0.15 });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('visible'); });
  }

  // Simple client-side check before submit (server validates again)
  var form = document.querySelector('.demo-form');
  if (form) {
    form.addEventListener('submit', function (ev) {
      var name = form.elements.name.value.trim();
      var email = form.elements.email.value.trim();
      if (!name || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        ev.preventDefault();
        (name ? form.elements.email : form.elements.name).focus();
        alert('Please enter your name and a valid email address.');
      }
    });
  }
})();
