<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/data.php';

$success = flash('success');
$errors  = flash('errors') ?? [];
$old     = flash('old') ?? [];
$dbError = flash('db_error');

// STATIC_BUILD is set by build.php when exporting the GitHub Pages version.
$static = defined('STATIC_BUILD');

// Live board rows: time, what, who, status
$board = [
    ['09:00', 'Haircut & Style',     'Salon',          'Confirmed'],
    ['09:30', 'Physio Assessment',   'Clinic',         'Checked in'],
    ['10:00', 'First Aid Level 3',   'Training Centre','Paid'],
    ['10:15', 'Driving Lesson',      'Driving School', 'Confirmed'],
    ['10:45', 'Deep Tissue Massage', 'Spa',            'Reminder sent'],
    ['11:00', 'Boiler Service',      'Maintenance',    'On the way'],
    ['11:30', 'Strategy Session',    'Consultant',     'Paid'],
    ['12:00', 'HIIT Class · 14/16',  'Gym',            'Filling up'],
    ['12:30', 'Escape Room · 6 ppl', 'Events',         'Deposit paid'],
    ['13:00', 'Brow Lamination',     'Beauty',         'Confirmed'],
];

// The day, hour by hour
$moments = [
    ['06:00', 'Before you wake up', 'Three new bookings came in overnight.', 'Customers book when it suits them, at 1am or on a Sunday. Your calendar fills without a single phone call.', 'notifs'],
    ['09:00', 'Reminders already sent', 'Nobody forgets. Nobody no-shows.', 'Confirmations and reminders go out on their own by email, so today’s customers turn up on time.', 'mail'],
    ['11:30', 'Paid before they arrive', 'Deposits and full payments, collected online.', 'Choose per service: full amount, a deposit or pay on the day. Card, online and cash records sit in one place.', 'receipt'],
    ['14:00', 'Everyone knows where to be', 'Staff, rooms and resources, on one timeline.', 'Assign people and rooms to each booking. Clashes are blocked before they happen.', 'gantt'],
    ['18:00', 'See how the day went', 'Bookings, revenue and busy hours at a glance.', 'Reports that show what’s working, so you can plan staff and offers from real numbers.', 'chart'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Online booking that works around the clock. Bookings, reminders, payments and scheduling in one platform by <?= e($site['name']) ?>.">
  <title>Bookings Around the Clock | <?= e($site['name']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/day.css">
</head>
<body>

<header class="bar">
  <a class="logo" href="#top">XG<span>TECH</span></a>
  <div class="clock" aria-hidden="true"><span class="clock-icon">☀</span><span class="clock-time">06:00</span></div>
  <nav class="links">
    <a href="#day">A day with us</a>
    <a href="#board">Live board</a>
    <a href="<?= e($site['pricing_url']) ?>">Pricing</a>
    <a href="#contact" class="pill-btn">Get a free demo</a>
  </nav>
</header>

<main id="top">
  <!-- HERO -->
  <section class="hero">
    <div class="hero-copy">
      <p class="tag"><span class="dot"></span> Online booking system</p>
      <h1>Bookings that run<br><em>around the clock.</em></h1>
      <p class="lead">Customers book and pay online, reminders send themselves, and your whole team works from one schedule, even while you sleep.</p>
      <div class="hero-cta">
        <a href="#contact" class="pill-btn big">Get a free demo →</a>
        <a href="<?= e($site['product_url']) ?>" class="ghost">See all features</a>
      </div>
    </div>

    <!-- Interactive booking widget -->
    <div class="widget" id="widget" aria-label="Try a booking">
      <div class="widget-head">
        <span>Try it: book a slot</span>
        <span class="step-dots"><i class="on"></i><i></i><i></i></span>
      </div>

      <div class="w-pane" data-pane="pick">
        <div class="services" role="tablist">
          <button class="svc on" data-svc="Haircut" data-dur="45 min" data-price="£35">Haircut</button>
          <button class="svc" data-svc="Consultation" data-dur="30 min" data-price="Free">Consultation</button>
          <button class="svc" data-svc="Training" data-dur="2 hrs" data-price="£90">Training</button>
        </div>
        <div class="days" id="days"></div>
        <div class="slots" id="slots"></div>
        <button class="confirm" id="confirm" disabled>Choose a time</button>
      </div>

      <div class="w-pane ticket-pane" data-pane="done" hidden>
        <div class="mini-ticket">
          <div class="mt-top">
            <small>Booking confirmed</small>
            <strong id="t-svc">Haircut</strong>
            <span id="t-when">Tue 3 Dec · 10:30</span>
          </div>
          <div class="mt-perf"></div>
          <div class="mt-bottom">
            <span><small>Duration</small><b id="t-dur">45 min</b></span>
            <span><small>Price</small><b id="t-price">£35</b></span>
            <span><small>Reminder</small><b>Scheduled ✓</b></span>
          </div>
        </div>
        <p class="w-note">That’s what your customers see. Took you about 5 seconds.</p>
        <button class="again" id="again">↺ Book another</button>
      </div>
    </div>
  </section>

  <!-- DEPARTURES-STYLE LIVE BOARD -->
  <section class="board-wrap" id="board">
    <div class="board-title">
      <h2>Today’s bookings, <em>across every kind of business</em></h2>
      <p>Salons, clinics, gyms, trainers, tradespeople. If customers book your time, it fits.</p>
    </div>
    <div class="board" role="table" aria-label="Example bookings">
      <div class="b-row b-head" role="row"><span>Time</span><span>Booking</span><span>Business</span><span>Status</span></div>
      <div id="board-rows">
        <?php foreach (array_slice($board, 0, 6) as [$t, $what, $who, $st]): ?>
          <div class="b-row" role="row"><span class="b-time"><?= e($t) ?></span><span><?= e($what) ?></span><span class="b-who"><?= e($who) ?></span><span class="b-st"><?= e($st) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
    <script type="application/json" id="board-data"><?= json_encode($board, JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
  </section>

  <!-- A DAY, HOUR BY HOUR -->
  <section class="day" id="day">
    <div class="day-intro">
      <p class="tag"><span class="dot"></span> A day with XGTECH</p>
      <h2>What your business looks like <em>when it books itself.</em></h2>
    </div>

    <div class="timeline">
      <div class="spine"><div class="spine-fill"></div></div>

      <?php foreach ($moments as $i => [$time, $kicker, $title, $text, $visual]): ?>
      <article class="moment" data-time="<?= e($time) ?>">
        <div class="m-time"><?= e($time) ?></div>
        <div class="m-node"></div>
        <div class="m-body">
          <div class="m-copy">
            <small><?= e($kicker) ?></small>
            <h3><?= e($title) ?></h3>
            <p><?= e($text) ?></p>
          </div>
          <div class="m-visual v-<?= e($visual) ?>" aria-hidden="true">
            <?php if ($visual === 'notifs'): ?>
              <div class="notif"><b>New booking</b><span>Sarah K. · Haircut · Tue 10:30</span><time>01:12</time></div>
              <div class="notif"><b>New booking</b><span>Omar R. · Consultation · Wed 14:00</span><time>03:47</time></div>
              <div class="notif"><b>New booking</b><span>Priya D. · Training x3 · Fri 09:00</span><time>05:20</time></div>
            <?php elseif ($visual === 'mail'): ?>
              <div class="mail">
                <div class="mail-bar"><span>To: sarah@example.com</span><span class="sent">Sent ✓</span></div>
                <strong>See you tomorrow at 10:30 👋</strong>
                <p>Your Haircut & Style is booked for Tue 10:30. Need to move it? Reschedule in one tap.</p>
                <span class="mail-btn">Reschedule</span>
              </div>
              <div class="counter"><b>64</b> reminders sent today</div>
            <?php elseif ($visual === 'receipt'): ?>
              <div class="receipt">
                <div class="r-line"><span>Brow Lamination</span><span>£45.00</span></div>
                <div class="r-line"><span>Deposit taken</span><span>£15.00</span></div>
                <div class="r-line muted"><span>Due on the day</span><span>£30.00</span></div>
                <div class="r-total"><span>Card •••• 4242</span><span class="paid">PAID</span></div>
              </div>
            <?php elseif ($visual === 'gantt'): ?>
              <div class="gantt">
                <div class="g-hours"><span>9</span><span>11</span><span>13</span><span>15</span><span>17</span></div>
                <div class="g-row"><label>Amira</label><div class="g-track"><i style="left:0;width:22%"></i><i style="left:30%;width:18%"></i><i class="c" style="left:62%;width:28%"></i></div></div>
                <div class="g-row"><label>Ben</label><div class="g-track"><i class="c" style="left:8%;width:34%"></i><i style="left:55%;width:20%"></i></div></div>
                <div class="g-row"><label>Room 2</label><div class="g-track"><i style="left:15%;width:40%"></i><i class="c" style="left:70%;width:22%"></i></div></div>
                <div class="g-row"><label>Van 1</label><div class="g-track"><i style="left:0;width:48%"></i><i style="left:58%;width:30%"></i></div></div>
              </div>
            <?php elseif ($visual === 'chart'): ?>
              <div class="chart">
                <div class="c-stats"><span><b>31</b>bookings</span><span><b>£1,940</b>revenue</span><span><b>0</b>no-shows</span></div>
                <div class="bars">
                  <?php foreach ([['M',48],['T',62],['W',55],['T',80],['F',95],['S',100],['S',40]] as [$d, $h]): ?>
                    <div class="cbar"><i style="--h:<?= (int) $h ?>%"></i><span><?= e($d) ?></span></div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>

      <!-- Night + form -->
      <article class="moment night-moment" data-time="23:00" id="contact">
        <div class="m-time">23:00</div>
        <div class="m-node moon"></div>
        <div class="m-body m-body-full">
          <div class="m-copy">
            <small>You switch off</small>
            <h3>You sleep. <em>It keeps booking.</em></h3>
            <p>Want to see it running for your business? Reserve a free, personalised demo. It takes 30 seconds.</p>
          </div>

          <form class="ticket" method="post" action="submit.php" novalidate<?= $static ? ' data-static data-email="' . e($site['email']) . '"' : '' ?>>
            <div class="ticket-main">
              <?php if ($success): ?><div class="alert ok"><?= e($success) ?></div><?php endif; ?>
              <?php if ($dbError): ?><div class="alert bad"><?= e($dbError) ?></div>
              <?php elseif ($errors): ?><div class="alert bad">Please check the highlighted fields.</div><?php endif; ?>

              <div class="alert ok" data-static-ok hidden></div>
              <div class="alert bad" data-static-bad hidden></div>
              <?php if (!$static): ?><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><?php endif; ?>
              <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

              <div class="t-grid">
                <label class="f">Name
                  <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" placeholder="Your full name" required>
                  <?php if (isset($errors['name'])): ?><small class="err"><?= e($errors['name']) ?></small><?php endif; ?>
                </label>
                <label class="f">Business
                  <input type="text" name="business" value="<?= e($old['business'] ?? '') ?>" placeholder="Business name">
                </label>
                <label class="f">Email
                  <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="you@business.com" required>
                  <?php if (isset($errors['email'])): ?><small class="err"><?= e($errors['email']) ?></small><?php endif; ?>
                </label>
                <label class="f">Phone
                  <input type="tel" name="phone" value="<?= e($old['phone'] ?? '') ?>" placeholder="Optional">
                  <?php if (isset($errors['phone'])): ?><small class="err"><?= e($errors['phone']) ?></small><?php endif; ?>
                </label>
                <label class="f">You take bookings for
                  <select name="service">
                    <?php foreach ($services as $s): ?>
                      <option value="<?= e($s) ?>"<?= (($old['service'] ?? '') === $s) ? ' selected' : '' ?>><?= e($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </label>
                <label class="f">Preferred demo date
                  <input type="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= e($old['date'] ?? '') ?>">
                  <?php if (isset($errors['date'])): ?><small class="err"><?= e($errors['date']) ?></small><?php endif; ?>
                </label>
                <label class="f wide">Anything we should know?
                  <textarea name="message" rows="2" placeholder="Optional"><?= e($old['message'] ?? '') ?></textarea>
                </label>
              </div>
            </div>
            <div class="ticket-stub">
              <small>Admit one</small>
              <strong>Free<br>Demo</strong>
              <span class="stub-code">XG-<?= e(date('dmy')) ?></span>
              <button type="submit" class="stub-btn">Reserve →</button>
            </div>
          </form>

          <p class="alt-contact">Prefer to talk? Call <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a> or email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></p>
        </div>
      </article>
    </div>
  </section>
</main>

<footer class="foot">
  <a class="logo" href="#top">XG<span>TECH</span></a>
  <span>© <?= date('Y') ?> <?= e($site['name']) ?> Ltd · Online booking, scheduling &amp; payments</span>
  <span class="foot-links">
    <a href="<?= e($site['product_url']) ?>">Booking system</a>
    <a href="<?= e($site['pricing_url']) ?>">Pricing</a>
    <a href="<?= e($site['contact_url']) ?>">Contact</a>
    <a href="<?= e($site['url']) ?>"><?= e(parse_url($site['url'], PHP_URL_HOST)) ?></a>
  </span>
</footer>

<script src="assets/js/day.js"></script>
</body>
</html>
