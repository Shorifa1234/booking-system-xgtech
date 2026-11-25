(function () {
  var nav = document.getElementById('nav');
  var links = document.getElementById('navLinks');
  var burger = document.getElementById('burger');
  var sticky = document.getElementById('stickyCta');
  var contact = document.getElementById('contact');

  // Mobile menu
  burger.addEventListener('click', function () {
    var open = links.classList.toggle('open');
    burger.setAttribute('aria-expanded', open);
  });
  links.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      links.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
    });
  });

  // Solid nav after the hero, sticky CTA on mobile (hidden near the form)
  function onScroll() {
    var y = window.scrollY;
    nav.classList.toggle('solid', y > 40);
    var formTop = contact.getBoundingClientRect().top;
    sticky.classList.toggle('show', y > 600 && formTop > window.innerHeight);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Reveal on scroll
  var els = document.querySelectorAll('.tile, .how-steps li, .sector, .swap-col, .plan, .calc, .faq-list details');
  if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    els.forEach(function (el) { el.classList.add('rv'); });
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    els.forEach(function (el) { io.observe(el); });
  }

  // No-show cost calculator
  var PRICE = 45;
  var apts = document.getElementById('apts');
  var val = document.getElementById('val');
  var rate = document.getElementById('rate');
  function money(n) { return '£' + Math.round(n).toLocaleString('en-GB'); }
  function calc() {
    var a = +apts.value, v = +val.value, r = +rate.value;
    document.getElementById('o-apts').textContent = a;
    document.getElementById('o-val').textContent = money(v);
    document.getElementById('o-rate').textContent = r + '%';
    var monthly = a * (52 / 12) * (r / 100) * v;
    document.getElementById('loss').textContent = money(monthly);
    var needed = Math.max(1, Math.ceil(PRICE / v));
    document.getElementById('calc-note').innerHTML = 'Recovering just <b>' + needed + '</b> missed appointment' +
      (needed === 1 ? '' : 's') + ' a month covers the starting price.';
  }
  [apts, val, rate].forEach(function (el) { el.addEventListener('input', calc); });
  calc();
})();
