(function () {
  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Time of day follows the scroll ---------- */
  var moments = Array.prototype.slice.call(document.querySelectorAll('.moment'));
  var clockTime = document.querySelector('.clock-time');
  var clockIcon = document.querySelector('.clock-icon');
  var spineFill = document.querySelector('.spine-fill');
  var timeline = document.querySelector('.timeline');

  // Sky colour at each hour (minutes from midnight -> hex)
  var sky = [
    [300,  '#e6eef8'], [420, '#f2f7fc'], [720, '#ffffff'], [900, '#eef5ff'],
    [1020, '#cfe2ff'], [1110, '#1769e0'], [1200, '#10243b'], [1380, '#0b1b2d']
  ];

  function toMin(t) { var p = t.split(':'); return +p[0] * 60 + +p[1]; }
  function hex(h) { return [1, 3, 5].map(function (i) { return parseInt(h.substr(i, 2), 16); }); }
  function mix(a, b, t) { return a.map(function (v, i) { return Math.round(v + (b[i] - v) * t); }); }
  function lum(c) {
    var s = c.map(function (v) { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); });
    return 0.2126 * s[0] + 0.7152 * s[1] + 0.0722 * s[2];
  }
  function skyAt(min) {
    if (min <= sky[0][0]) return hex(sky[0][1]);
    for (var i = 1; i < sky.length; i++) {
      if (min <= sky[i][0]) {
        var t = (min - sky[i - 1][0]) / (sky[i][0] - sky[i - 1][0]);
        return mix(hex(sky[i - 1][1]), hex(sky[i][1]), t);
      }
    }
    return hex(sky[sky.length - 1][1]);
  }

  // Scroll position -> clock minutes, anchored on each moment
  function anchors() {
    var list = [[0, 330]]; // top of page = 05:30
    moments.forEach(function (m) {
      var y = m.getBoundingClientRect().top + window.scrollY - window.innerHeight * 0.45;
      list.push([Math.max(1, y), toMin(m.dataset.time)]);
    });
    return list;
  }
  var A = anchors();
  window.addEventListener('resize', function () { A = anchors(); update(); });

  function minutesAt(y) {
    if (y <= A[0][0]) return A[0][1];
    for (var i = 1; i < A.length; i++) {
      if (y <= A[i][0]) {
        var t = (y - A[i - 1][0]) / (A[i][0] - A[i - 1][0]);
        return A[i - 1][1] + (A[i][1] - A[i - 1][1]) * t;
      }
    }
    return A[A.length - 1][1];
  }

  function pad(n) { return (n < 10 ? '0' : '') + n; }

  function update() {
    var y = window.scrollY;
    var min = minutesAt(y);
    var rounded = Math.round(min / 5) * 5;
    clockTime.textContent = pad(Math.floor(rounded / 60) % 24) + ':' + pad(rounded % 60);
    clockIcon.textContent = min < 1080 ? '☀' : (min < 1230 ? '◐' : '☾');

    var c = skyAt(min);
    var dark = lum(c) < 0.4;
    root.style.setProperty('--bg', 'rgb(' + c.join(',') + ')');
    root.style.setProperty('--fg', dark ? '#f2f7fc' : '#10243b');
    root.style.setProperty('--fg2', dark ? '#b8c9db' : '#617187');
    root.style.setProperty('--accent', dark ? '#7fdcf0' : '#1769e0');
    root.style.setProperty('--edge', dark ? 'rgba(255,255,255,.16)' : 'rgba(16,36,59,.12)');

    // Spine progress
    var r = timeline.getBoundingClientRect();
    var p = (window.innerHeight * 0.5 - r.top) / r.height;
    spineFill.style.height = Math.max(0, Math.min(1, p)) * 100 + '%';
  }

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) { requestAnimationFrame(function () { update(); ticking = false; }); ticking = true; }
  }, { passive: true });
  update();

  // Reveal moments
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) e.target.classList.add('in'); });
    }, { threshold: 0.25 });
    moments.forEach(function (m) { io.observe(m); });
  } else {
    moments.forEach(function (m) { m.classList.add('in'); });
  }

  /* ---------- Try-it booking widget ---------- */
  var daysEl = document.getElementById('days');
  var slotsEl = document.getElementById('slots');
  var confirmBtn = document.getElementById('confirm');
  var panes = document.querySelectorAll('.w-pane');
  var dots = document.querySelectorAll('.step-dots i');
  var times = ['09:00', '09:45', '10:30', '11:15', '13:00', '13:45', '14:30', '15:15'];
  var DAY = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  var MON = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var state = { svc: null, day: 0, time: null, dates: [] };

  function setDots(n) { dots.forEach(function (d, i) { d.classList.toggle('on', i < n); }); }

  for (var i = 0; i < 7; i++) {
    var d = new Date(); d.setDate(d.getDate() + i + 1);
    state.dates.push(d);
    var b = document.createElement('button');
    b.className = 'day-btn' + (i === 0 ? ' on' : '');
    b.type = 'button';
    b.dataset.i = i;
    b.innerHTML = DAY[d.getDay()] + '<b>' + d.getDate() + '</b>';
    daysEl.appendChild(b);
  }

  function renderSlots() {
    slotsEl.innerHTML = '';
    state.time = null;
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Choose a time';
    var seed = state.day * 7 + state.svc.dataset.svc.length;
    times.forEach(function (t, k) {
      var s = document.createElement('button');
      s.type = 'button';
      s.className = 'slot';
      s.textContent = t;
      s.style.animationDelay = (k * 25) + 'ms';
      if ((seed * (k + 3)) % 5 === 0) s.disabled = true; // "already booked"
      slotsEl.appendChild(s);
    });
    setDots(1);
  }

  document.querySelectorAll('.svc').forEach(function (s) {
    s.addEventListener('click', function () {
      document.querySelectorAll('.svc').forEach(function (x) { x.classList.remove('on'); });
      s.classList.add('on'); state.svc = s; renderSlots();
    });
  });
  state.svc = document.querySelector('.svc.on');

  daysEl.addEventListener('click', function (e) {
    var b = e.target.closest('.day-btn'); if (!b) return;
    daysEl.querySelectorAll('.day-btn').forEach(function (x) { x.classList.remove('on'); });
    b.classList.add('on'); state.day = +b.dataset.i; renderSlots();
  });

  slotsEl.addEventListener('click', function (e) {
    var s = e.target.closest('.slot'); if (!s || s.disabled) return;
    slotsEl.querySelectorAll('.slot').forEach(function (x) { x.classList.remove('on'); });
    s.classList.add('on'); state.time = s.textContent;
    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Book ' + state.svc.dataset.svc + ' at ' + state.time + ' →';
    setDots(2);
  });

  confirmBtn.addEventListener('click', function () {
    var d = state.dates[state.day];
    document.getElementById('t-svc').textContent = state.svc.dataset.svc;
    document.getElementById('t-when').textContent = DAY[d.getDay()] + ' ' + d.getDate() + ' ' + MON[d.getMonth()] + ' · ' + state.time;
    document.getElementById('t-dur').textContent = state.svc.dataset.dur;
    document.getElementById('t-price').textContent = state.svc.dataset.price;
    panes[0].hidden = true; panes[1].hidden = false; setDots(3);
  });

  document.getElementById('again').addEventListener('click', function () {
    panes[1].hidden = true; panes[0].hidden = false; renderSlots();
  });

  renderSlots();

  /* ---------- Live board: rows roll in like a departures board ---------- */
  var rowsEl = document.getElementById('board-rows');
  var data = JSON.parse(document.getElementById('board-data').textContent);
  var next = rowsEl.children.length;

  function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  if (!reduce) {
    setInterval(function () {
      if (document.hidden) return;
      var r = data[next % data.length]; next++;
      var row = document.createElement('div');
      row.className = 'b-row flip';
      row.setAttribute('role', 'row');
      row.innerHTML = '<span class="b-time">' + esc(r[0]) + '</span><span>' + esc(r[1]) +
        '</span><span class="b-who">' + esc(r[2]) + '</span><span class="b-st">' + esc(r[3]) + '</span>';
      rowsEl.removeChild(rowsEl.firstElementChild);
      rowsEl.appendChild(row);
    }, 2600);
  }

  /* ---------- Static (GitHub Pages) form: open an email draft ---------- */
  var sform = document.querySelector('form[data-static]');
  if (sform) {
    var dateIn = sform.elements.date;
    if (dateIn) dateIn.min = new Date().toISOString().slice(0, 10);
    sform.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var f = sform.elements;
      var ok = sform.querySelector('[data-static-ok]');
      var bad = sform.querySelector('[data-static-bad]');
      ok.hidden = true; bad.hidden = true;
      var name = f.name.value.trim(), email = f.email.value.trim();
      if (!name || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        bad.textContent = 'Please enter your name and a valid email address.';
        bad.hidden = false;
        (name ? f.email : f.name).focus();
        return;
      }
      var lines = [
        'Name: ' + name,
        'Business: ' + f.business.value.trim(),
        'Email: ' + email,
        'Phone: ' + f.phone.value.trim(),
        'Bookings for: ' + f.service.value,
        'Preferred demo date: ' + f.date.value,
        '',
        f.message.value.trim()
      ];
      window.location.href = 'mailto:' + sform.dataset.email +
        '?subject=' + encodeURIComponent('Free demo request: ' + name) +
        '&body=' + encodeURIComponent(lines.join('\n'));
      ok.textContent = 'Thank you, ' + name + '! Your email app has opened with your request. Press send and we will be in touch.';
      ok.hidden = false;
    });
  }
})();
