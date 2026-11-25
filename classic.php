<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';

$success = flash('success');
$errors  = flash('errors') ?? [];
$old     = flash('old') ?? [];
$dbError = flash('db_error');

// Mini calendar for the hero mock-up: next 5 days starting today.
$days = [];
for ($i = 0; $i < 5; $i++) {
    $days[] = new DateTime("+$i day");
}
$slots = [
    ['09:00', 'Consultation', true], ['10:00', 'Training', false], ['11:00', 'Available', true],
    ['12:00', 'Service', false], ['13:00', 'Available', true],
];

include __DIR__ . '/includes/header.php';
?>
  <main>
    <!-- HERO -->
    <section class="hero" id="home">
      <div class="container hero-grid">
        <div>
          <span class="eyebrow">Online booking system</span>
          <h1>Take Bookings 24/7. <span class="accent">Manage Everything</span> in One Place.</h1>
          <p>Let customers book and pay online while you manage schedules, staff, payments and reminders from one simple dashboard.</p>
          <div class="hero-actions">
            <a class="btn" href="#contact">Book a Free Demo <span aria-hidden="true">→</span></a>
            <a class="btn btn-outline" href="#features">Explore Features</a>
          </div>
          <div class="hero-points"><span>No double bookings</span><span>Online payments</span><span>Automatic reminders</span></div>
        </div>

        <div class="dashboard-wrap" aria-label="Illustration of the booking dashboard">
          <div class="dashboard">
            <div class="dash-top">
              <div class="dash-brand">XGTECH <span class="blue">/</span> Bookings</div>
              <div class="dash-user">JD</div>
            </div>
            <div class="dash-title"><strong>Today’s overview</strong><small><?= e(date('D, j M Y')) ?></small></div>
            <div class="stats">
              <div class="stat"><small>Bookings today</small><b>24</b><em>↑ 12% this week</em></div>
              <div class="stat"><small>Revenue</small><b>£1,280</b><em>↑ 8% this week</em></div>
              <div class="stat"><small>No-shows</small><b>0</b><em>Reminders on</em></div>
            </div>
            <div class="calendar">
              <div class="cal-head"><span>This week</span><span class="blue">View calendar →</span></div>
              <div class="day-row">
                <?php foreach ($days as $i => $d): ?>
                  <div class="day<?= $i === 0 ? ' today' : '' ?>"><small><?= e($d->format('D')) ?></small><b><?= e($d->format('j')) ?></b></div>
                <?php endforeach; ?>
              </div>
              <div class="cal-grid">
                <?php foreach ($slots as [$time, $label, $free]): ?>
                  <div class="cal-cell<?= $free ? '' : ' active' ?>"><b><?= e($time) ?></b><?php if ($free): ?><?= e($label) ?><?php else: ?><span class="booking-tag"><?= e($label) ?></span><?php endif; ?></div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="dash-foot"><span>● Confirmed bookings</span><span>Updated just now</span></div>
          </div>
          <div class="floating-note">✓ Booking confirmed<span>Reminder scheduled for tomorrow</span></div>
        </div>
      </div>
    </section>

    <!-- TRUST STRIP -->
    <div class="trust">
      <div class="container">
        <p>Everything your booking business needs</p>
        <div class="trust-list"><span>Bookings</span><span>Scheduling</span><span>Payments</span><span>Reminders</span><span>Customers</span><span>Reports</span></div>
      </div>
    </div>

    <!-- FEATURES -->
    <section id="features">
      <div class="container">
        <div class="section-head">
          <span class="kicker">One platform. Complete control.</span>
          <h2>A Booking System That Runs Your Day for You</h2>
          <p>Stop juggling diaries, spreadsheets and messages. Customers book themselves, and your team sees everything in one place.</p>
        </div>
        <div class="feature-grid">
          <?php foreach ($features as $f): ?>
            <article class="feature reveal">
              <div class="feature-icon"><?= e($f['icon']) ?></div>
              <h3><?= e($f['title']) ?></h3>
              <p><?= e($f['text']) ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="workflow" id="how-it-works">
      <div class="container">
        <div class="section-head">
          <span class="kicker">From first click to repeat customer</span>
          <h2>How Online Booking Works</h2>
          <p>Set it up once. After that, bookings, payments and reminders happen on their own.</p>
        </div>
        <div class="steps">
          <?php foreach ($steps as $i => [$title, $text]): ?>
            <div class="step reveal"><div class="step-num"><?= $i + 1 ?></div><strong><?= e($title) ?></strong><small><?= e($text) ?></small></div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- FEATURE LIST + PORTAL MOCK -->
    <section>
      <div class="container split">
        <div class="split-copy">
          <span class="kicker">Built around your operation</span>
          <h2>Flexible Features. A Better Customer Experience.</h2>
          <p>Give customers an easy way to book, and give your team the tools to manage the work behind every booking.</p>
          <ul class="check-list">
            <?php foreach ($checks as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
          </ul>
        </div>
        <div class="portal-card reveal">
          <div class="portal-window">
            <div class="window-bar"><i></i><i></i><i></i></div>
            <div class="portal-content">
              <h3>Upcoming bookings</h3>
              <div class="portal-row"><span>First Aid Training · 10:00</span><span class="pill">Paid</span></div>
              <div class="portal-row"><span>Consultation · 11:30</span><span class="pill">Confirmed</span></div>
              <div class="portal-row"><span>Beauty Appointment · 13:00</span><span class="pill pill-wait">Deposit</span></div>
              <div class="portal-row"><span>Private Session · 14:30</span><span class="pill">Confirmed</span></div>
              <div class="portal-foot"><span>Your business, in one view</span><strong>View dashboard →</strong></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- INDUSTRIES -->
    <section class="industries" id="industries">
      <div class="container">
        <div class="section-head">
          <span class="kicker kicker-cyan">One platform. Many possibilities.</span>
          <h2>Made for Service-Based Businesses</h2>
          <p>If your customers book a time with you, the platform can be shaped around the way you work.</p>
        </div>
        <div class="industry-grid">
          <?php foreach ($industries as [$icon, $name]): ?>
            <div class="industry"><span><?= e($icon) ?></span><?= e($name) ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- CUSTOMISE -->
    <section class="custom">
      <div class="container">
        <div class="custom-box">
          <div>
            <span class="kicker">Your business. Your branding.</span>
            <h2>A Customisable Platform That Grows With You</h2>
            <p>We tailor the booking journey, forms and dashboard to your services and requirements, with a consistent experience on web and mobile.</p>
          </div>
          <a class="btn" href="#contact">Discuss Your Requirements →</a>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section id="faq">
      <div class="container faq-wrap">
        <div class="section-head">
          <span class="kicker">Questions</span>
          <h2>Frequently Asked Questions</h2>
        </div>
        <div class="faq-list">
          <?php foreach ($faqs as [$q, $a]): ?>
            <details class="faq">
              <summary><?= e($q) ?></summary>
              <p><?= e($a) ?></p>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- CONTACT / DEMO FORM -->
    <section class="cta" id="contact">
      <div class="container cta-grid">
        <div class="cta-copy">
          <span class="eyebrow">Let's simplify the way you work</span>
          <h2>Ready to Start Taking Bookings Online?</h2>
          <p>Tell us a little about your business and we’ll arrange a free, personalised demo of the booking system.</p>
          <div class="cta-contact">
            Or call <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a><br>
            Email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
          </div>
        </div>

        <form class="demo-form" method="post" action="submit.php" novalidate>
          <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
          <?php endif; ?>
          <?php if ($dbError): ?>
            <div class="alert alert-error"><?= e($dbError) ?></div>
          <?php elseif ($errors): ?>
            <div class="alert alert-error">Please fix the highlighted fields.</div>
          <?php endif; ?>

          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <!-- Honeypot: real visitors leave this empty -->
          <input type="hidden" name="source" value="classic.php">
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">

          <div class="field-row">
            <label>Full name
              <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required>
              <?php if (isset($errors['name'])): ?><small class="err"><?= e($errors['name']) ?></small><?php endif; ?>
            </label>
            <label>Business name
              <input type="text" name="business" value="<?= e($old['business'] ?? '') ?>">
            </label>
          </div>
          <div class="field-row">
            <label>Email
              <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
              <?php if (isset($errors['email'])): ?><small class="err"><?= e($errors['email']) ?></small><?php endif; ?>
            </label>
            <label>Phone
              <input type="tel" name="phone" value="<?= e($old['phone'] ?? '') ?>">
              <?php if (isset($errors['phone'])): ?><small class="err"><?= e($errors['phone']) ?></small><?php endif; ?>
            </label>
          </div>
          <div class="field-row">
            <label>What do you take bookings for?
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
          <label>Message (optional)
            <textarea name="message" rows="3"><?= e($old['message'] ?? '') ?></textarea>
          </label>
          <button type="submit" class="btn btn-block">Request My Free Demo →</button>
        </form>
      </div>
    </section>
  </main>
<?php include __DIR__ . '/includes/footer.php'; ?>
