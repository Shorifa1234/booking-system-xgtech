// Static (GitHub Pages) version of the demo form: opens an email draft
// instead of posting to PHP. Only active on forms with data-static.
(function () {
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
