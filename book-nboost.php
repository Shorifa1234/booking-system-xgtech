<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';

$success = flash('success');
$errors  = flash('errors') ?? [];
$old     = flash('old') ?? [];
$dbError = flash('db_error');
$static  = defined('STATIC_BUILD');

// Facts below come from xgtech.co.uk/booking_sys.html and pricing.html
$price      = 45;               // starting price, GBP per month
$promoCode  = 'XGTECH 20';
$featureBase = 'https://xgtech.co.uk/booking_sys-f';

$allFeatures = [
    1 => 'Appointment Scheduling', 2 => 'One-on-One Meetings', 3 => 'Embed Website',
    4 => 'Google Calendar Integrations', 5 => 'Integrated Payments', 6 => 'Appointment Reports',
    7 => 'Booking Notifications', 8 => 'QR Code Payments', 9 => 'Zoom Integrations',
    10 => 'Time Slot Mapping', 11 => 'Calendar', 12 => 'Service Selling', 13 => '24/7 Support',
];

$planIncludes = [
    'Appointments', 'Date scheduling', 'Time scheduling', 'Booking', 'Booking approval',
    'Customer portal', 'Reporting', 'Website integration', 'Online payment',
    'Email notifications', 'Cloud hosting',
];

$sectors = [
    ['🎉', 'Event Management'], ['🏢', 'Facilities Management'], ['🧽', 'Cleaning / Hygiene'], ['🐜', 'Pest Control'],
    ['🏊', 'Swimming / Dance Classes'], ['🎓', 'Instructor'], ['💆', 'Therapist / PT'], ['＋', 'Any appointment business'],
];

$faqs = [
    ['How much does Book NBoosT cost?', "Plans start from £{$price} per month, with cloud hosting included. Pay annually and use promo code {$promoCode} to save 20%."],
    ['Can clients pay when they book?', 'Yes. Take full payment or a deposit at booking through gateways such as Stripe and PayPal, or let clients pay at the appointment, including by QR code.'],
    ['Does it reduce no-shows?', 'Clients get automatic email reminders before their appointment, so fewer people forget or cancel at the last minute.'],
    ['Can I add it to my website or social media?', 'Yes. Book NBoosT can be embedded in your existing website and linked from your social media pages, so clients book wherever they find you.'],
    ['Will it work with my team?', 'Yes. Give staff different roles and access levels, assign bookings to staff members, track clock-ins and handle class substitutions from one dashboard.'],
    ['Does it connect to my calendar and Zoom?', 'Yes. Book NBoosT integrates with Google Calendar and Zoom, so online meetings and existing calendars stay in sync.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book NBoosT: Appointment Booking Software from £<?= $price ?>/month | <?= e($site['name']) ?></title>
  <meta name="description" content="Book NBoosT lets clients book, pay and get reminders online. Appointment scheduling, payments, QR codes, Google Calendar and Zoom from £<?= $price ?>/month.">
  <meta property="og:title" content="Book NBoosT: let clients book themselves">
  <meta property="og:description" content="Online appointment booking, payments and reminders from £<?= $price ?>/month. Book a free demo.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/nboost.css?v=<?= filemtime(__DIR__ . '/assets/css/nboost.css') ?>">
  <link rel="icon" type="image/png" href="assets/img/book-nboost-icon.png">
  <meta property="og:image" content="https://shorifa1234.github.io/booking-system-xgtech/assets/img/book-nboost-logo.png">
</head>
<body>

<header class="nav" id="nav">
  <div class="wrap nav-in">
    <a class="brand" href="#top"><img src="assets/img/book-nboost-logo.png" alt="Book NBoost" width="324" height="112"></a>
    <nav class="nav-links" id="navLinks">
      <a href="#features">Features</a>
      <a href="#cost">No-show cost</a>
      <a href="#pricing">Pricing</a>
      <a href="#faq">FAQ</a>
      <a href="#contact" class="btn btn-sm">Get a free demo</a>
    </nav>
    <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false"><span></span><span></span></button>
  </div>
</header>

<main id="top">

  <!-- HERO -->
  <section class="hero">
    <div class="wrap hero-in">
      <div class="hero-copy">
        <a class="price-chip" href="#pricing"><span>Offer</span> Plans from £<?= $price ?>/month · Save 20% yearly →</a>
        <h1><span class="grad">Stop chasing appointments.</span> <em>Let clients book themselves.</em></h1>
        <p class="sub">Book NBoosT is appointment scheduling software that takes bookings, payments and reminders off your hands, so there are fewer phone calls, fewer back-and-forth emails and fewer no-shows.</p>
        <div class="hero-btns">
          <a href="#contact" class="btn btn-lg">Book a free demo →</a>
          <a href="#pricing" class="btn btn-lg btn-glass">See pricing</a>
        </div>
        <ul class="hero-ticks">
          <li>Clients book 24/7</li><li>Email reminders</li><li>Paid before they arrive</li>
        </ul>
      </div>

      <div class="hero-visual" aria-hidden="true">
        <div class="phone">
          <div class="notch"></div>
          <div class="ph-head"><small>Book with</small><b>Blue Lane Swim School</b></div>
          <div class="ph-step">1 · Service</div>
          <div class="ph-svc on"><span>Private Swim Lesson</span><span>45 min · £25</span></div>
          <div class="ph-svc"><span>Kids Group Class</span><span>30 min · £12</span></div>
          <div class="ph-step">2 · Time</div>
          <div class="ph-slots"><i>10:00</i><i class="x">10:45</i><i class="on">11:30</i><i>12:15</i><i class="x">13:00</i><i>13:45</i></div>
          <div class="ph-pay"><span>Deposit today</span><b>£10.00</b></div>
          <div class="ph-btn">Pay & confirm</div>
        </div>
        <div class="float f1"><span class="ic ic-green">✓</span><div><b>Booking confirmed</b><small>Swim Lesson · Thu 11:30</small></div></div>
        <div class="float f2"><span class="ic ic-blue">✉</span><div><b>Email reminder sent</b><small>24 hours before</small></div></div>
        <div class="float f3"><span class="ic ic-cyan">▦</span><div><b>QR payment received</b><small>£15.00 · balance paid</small></div></div>
      </div>
    </div>
  </section>

  <!-- PROBLEM / SOLUTION -->
  <section class="swap">
    <div class="wrap">
      <h2 class="center">Your diary shouldn’t <em>run your day.</em></h2>
      <div class="swap-grid">
        <div class="swap-col before">
          <h3>Without Book NBoosT</h3>
          <ul>
            <li>Phone ringing while you’re with a client</li>
            <li>Back-and-forth emails to find a time</li>
            <li>Clients forget, and the slot sits empty</li>
            <li>Chasing payments after the appointment</li>
            <li>Separate tools for calendar, payments and staff</li>
          </ul>
        </div>
        <div class="swap-arrow">→</div>
        <div class="swap-col after">
          <h3>With Book NBoosT</h3>
          <ul>
            <li>Clients book themselves, day or night</li>
            <li>Live availability, so there are no double bookings</li>
            <li>Automatic email reminders</li>
            <li>Deposits or full payment taken at booking</li>
            <li>One dashboard for bookings, payments and staff</li>
            <li>Easy rescheduling, so clients can move their own booking</li>
            <li>Built-in communication with clients, all in one place</li>
            <li>Lead capturing that turns enquiries into bookings</li>
            <li>Separate customer, staff and admin portals</li>
            <li>Integration with Facebook, LinkedIn, Instagram and more</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES BENTO -->
  <section class="features" id="features">
    <div class="wrap">
      <div class="head">
        <span class="eyebrow">Everything in one place</span>
        <h2>Built to fill your diary <em>and keep it full.</em></h2>
      </div>

      <div class="bento">
        <article class="tile t-wide t-dark">
          <span class="t-tag">Appointment scheduling</span>
          <h3>Online booking from any device, synced in real time</h3>
          <p>Clients choose a service, staff member and time slot. Your calendar updates instantly, so a slot can never be booked twice. Set durations, buffer times, working hours and recurring appointments.</p>
          <div class="mini-cal">
            <?php foreach (['Mon','Tue','Wed','Thu','Fri'] as $i => $d): ?>
              <div class="mc-col"><small><?= $d ?></small>
                <?php for ($k = 0; $k < 4; $k++): $busy = (($i + 1) * ($k + 2)) % 3 !== 0; ?>
                  <i class="<?= $busy ? 'busy' : '' ?>"></i>
                <?php endfor; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </article>

        <article class="tile">
          <span class="ic-big">✉</span>
          <h3>Automatic reminders</h3>
          <p>Email reminders go out before every appointment to cut no-shows and last-minute cancellations.</p>
        </article>

        <article class="tile">
          <span class="ic-big">£</span>
          <h3>Integrated payments</h3>
          <p>Take deposits or full payment at booking through gateways like Stripe and PayPal. Transaction history is saved and exportable.</p>
        </article>

        <article class="tile t-cyan">
          <span class="ic-big">▦</span>
          <h3>QR code payments</h3>
          <p>Each client gets a unique QR code that opens a secure payment link on their phone. Payments are recorded automatically.</p>
        </article>

        <article class="tile">
          <span class="ic-big">⟷</span>
          <h3>Google Calendar & Zoom</h3>
          <p>Keep your existing calendar in sync and run one-on-one meetings online with Zoom.</p>
        </article>

        <article class="tile">
          <span class="ic-big">⌂</span>
          <h3>Your website & socials</h3>
          <p>Embed booking in your existing website and share it on social media, so clients book wherever they find you.</p>
        </article>

        <article class="tile t-wide">
          <span class="t-tag">Team management</span>
          <h3>Run your whole team from one dashboard</h3>
          <p>Give staff different roles and access levels, assign bookings, track clock-ins, and handle instructor substitutions for classes automatically.</p>
          <div class="team">
            <span><i style="--c:#1769e0">A</i>Amira · Admin</span>
            <span><i style="--c:#35c5dc">B</i>Ben · Staff</span>
            <span><i style="--c:#19a879">C</i>Chloe · Instructor</span>
          </div>
        </article>

        <article class="tile">
          <span class="ic-big">↗</span>
          <h3>Appointment reports</h3>
          <p>See bookings, revenue and busy times, and make decisions from real numbers.</p>
        </article>
      </div>

      <div class="all-feat">
        <p>All <?= count($allFeatures) ?> features</p>
        <div class="chips">
          <?php foreach ($allFeatures as $n => $f): ?>
            <a href="<?= e($featureBase . $n . '.html') ?>" target="_blank" rel="noopener"><?= e($f) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="how">
    <div class="wrap">
      <div class="head">
        <span class="eyebrow">Up and running quickly</span>
        <h2>Three steps to <em>a self-filling diary.</em></h2>
      </div>
      <ol class="how-steps">
        <li><b>01</b><h3>Book a free demo</h3><p>We show you Book NBoosT working with your own services and answer your questions.</p></li>
        <li><b>02</b><h3>We set it up for you</h3><p>Your services, prices, staff and working hours, customised to how your business runs.</p></li>
        <li><b>03</b><h3>Share your booking link</h3><p>Add it to your website and social media. Clients book, pay and get reminders on their own.</p></li>
      </ol>
    </div>
  </section>

  <!-- NO-SHOW CALCULATOR -->
  <section class="cost" id="cost">
    <div class="wrap cost-in">
      <div class="cost-copy">
        <span class="eyebrow eyebrow-light">Quick calculator</span>
        <h2>What are no-shows <em>costing you?</em></h2>
        <p>Move the sliders to match your business. Every empty slot is income you planned for and didn’t get.</p>
        <p class="fine">This is an estimate from your own numbers, not a promise of results.</p>
      </div>
      <div class="calc" id="calc">
        <label>Appointments per week <output id="o-apts">40</output>
          <input type="range" id="apts" min="5" max="300" step="5" value="40">
        </label>
        <label>Average appointment value <output id="o-val">£30</output>
          <input type="range" id="val" min="5" max="300" step="5" value="30">
        </label>
        <label>No-shows & late cancellations <output id="o-rate">10%</output>
          <input type="range" id="rate" min="1" max="30" step="1" value="10">
        </label>
        <div class="calc-out">
          <div><small>Lost each month</small><b id="loss">£520</b></div>
          <div><small>Book NBoosT from</small><b>£<?= $price ?><span>/mo</span></b></div>
        </div>
        <p class="calc-note" id="calc-note">Recovering just <b>2</b> missed appointments a month covers the starting price.</p>
      </div>
    </div>
  </section>

  <!-- SECTORS -->
  <section class="sectors">
    <div class="wrap">
      <div class="head">
        <span class="eyebrow">Who it’s for</span>
        <h2>If clients book your time, <em>it fits.</em></h2>
      </div>
      <div class="sector-grid">
        <?php foreach ($sectors as [$icon, $name]): ?>
          <div class="sector"><span><?= e($icon) ?></span><?= e($name) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PRICING -->
  <section class="pricing" id="pricing">
    <div class="wrap pricing-in">
      <div class="price-copy">
        <span class="eyebrow">Simple pricing</span>
        <h2>One tool instead of <em>three or four.</em></h2>
        <p>Booking, payments, reminders and reports in one platform, with cloud hosting included. No separate calendar app, payment tool and reminder service to pay for.</p>
        <div class="promo">
          <span>Save 20%</span>
          <p>Choose an annual plan and use promo code <code><?= e($promoCode) ?></code></p>
        </div>
      </div>

      <div class="plan">
        <div class="plan-top">
          <small>Booking System</small>
          <div class="plan-price"><span class="from">from</span>£<?= $price ?><span class="per">/month</span></div>
          <p>Starting price. Final price depends on your setup. Ask us for a quote.</p>
        </div>
        <ul class="plan-list">
          <?php foreach ($planIncludes as $p): ?><li><?= e($p) ?></li><?php endforeach; ?>
        </ul>
        <a href="#contact" class="btn btn-lg btn-block">Book a free demo →</a>
        <a href="<?= e($site['contact_url']) ?>" class="plan-link">or request a quotation</a>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="faq" id="faq">
    <div class="wrap faq-in">
      <div class="head left">
        <span class="eyebrow">FAQ</span>
        <h2>Questions, <em>answered.</em></h2>
        <p>Something else? Call <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a> (Mon–Fri) or email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>.</p>
      </div>
      <div class="faq-list">
        <?php foreach ($faqs as $i => [$q, $a]): ?>
          <details<?= $i === 0 ? ' open' : '' ?>><summary><?= e($q) ?></summary><p><?= e($a) ?></p></details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- FINAL CTA + FORM -->
  <section class="final" id="contact">
    <div class="wrap final-in">
      <div class="final-copy">
        <span class="eyebrow eyebrow-light">Free demo · No obligation</span>
        <h2>See Book NBoosT working <em>for your business.</em></h2>
        <p>Tell us a little about what you do. We’ll show you how clients would book, pay and get reminders, set up with your own services.</p>
        <ul class="final-ticks">
          <li>Personalised to your services</li>
          <li>Plans from £<?= $price ?>/month</li>
          <li>20% off annual plans with <?= e($promoCode) ?></li>
        </ul>
      </div>

      <form class="form" method="post" action="submit.php" novalidate<?= $static ? ' data-static data-email="' . e($site['email']) . '"' : '' ?>>
        <h3>Book your free demo</h3>
        <?php if ($success): ?><div class="alert ok"><?= e($success) ?></div><?php endif; ?>
        <?php if ($dbError): ?><div class="alert bad"><?= e($dbError) ?></div>
        <?php elseif ($errors): ?><div class="alert bad">Please check the highlighted fields.</div><?php endif; ?>
        <div class="alert ok" data-static-ok hidden></div>
        <div class="alert bad" data-static-bad hidden></div>

        <?php if (!$static): ?><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><?php endif; ?>
        <input type="hidden" name="source" value="book-nboost.php">
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="row">
          <label>Name*
            <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required>
            <?php if (isset($errors['name'])): ?><small class="err"><?= e($errors['name']) ?></small><?php endif; ?>
          </label>
          <label>Business
            <input type="text" name="business" value="<?= e($old['business'] ?? '') ?>">
          </label>
        </div>
        <div class="row">
          <label>Email*
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?><small class="err"><?= e($errors['email']) ?></small><?php endif; ?>
          </label>
          <label>Phone
            <input type="tel" name="phone" value="<?= e($old['phone'] ?? '') ?>">
            <?php if (isset($errors['phone'])): ?><small class="err"><?= e($errors['phone']) ?></small><?php endif; ?>
          </label>
        </div>
        <div class="row">
          <label>You take bookings for
            <select name="service">
              <?php foreach ($services as $s): ?>
                <option value="<?= e($s) ?>"<?= (($old['service'] ?? '') === $s) ? ' selected' : '' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>Preferred demo date
            <input type="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= e($old['date'] ?? '') ?>">
            <?php if (isset($errors['date'])): ?><small class="err"><?= e($errors['date']) ?></small><?php endif; ?>
          </label>
        </div>
        <label>Message
          <textarea name="message" rows="3" placeholder="Optional"><?= e($old['message'] ?? '') ?></textarea>
        </label>
        <button type="submit" class="btn btn-lg btn-block">Get my free demo →</button>
        <p class="form-fine">We’ll only use your details to arrange your demo.</p>
      </form>
    </div>
  </section>
</main>

<footer class="foot">
  <div class="wrap foot-in">
    <div>
      <a class="brand brand-foot" href="#top"><img src="assets/img/book-nboost-logo.png" alt="Book NBoost" width="324" height="112" loading="lazy"></a>
      <p>Appointment booking software by <a href="<?= e($site['url']) ?>"><?= e($site['name']) ?></a>.</p>
    </div>
    <div class="foot-links">
      <a href="<?= e($site['product_url']) ?>">Product page</a>
      <a href="<?= e($site['pricing_url']) ?>">All pricing</a>
      <a href="<?= e($site['contact_url']) ?>">Contact</a>
      <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a>
      <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
    </div>
  </div>
  <div class="wrap copy">© <?= date('Y') ?> <?= e($site['name']) ?> Ltd. All rights reserved.</div>
</footer>

<!-- Sticky mobile CTA -->
<a href="#contact" class="sticky-cta" id="stickyCta">Book a free demo · from £<?= $price ?>/mo →</a>

<script src="assets/js/nboost.js?v=<?= filemtime(__DIR__ . '/assets/js/nboost.js') ?>"></script>
<?php if ($static): ?><script src="assets/js/static-form.js?v=<?= filemtime(__DIR__ . '/assets/js/static-form.js') ?>"></script><?php endif; ?>
</body>
</html>
