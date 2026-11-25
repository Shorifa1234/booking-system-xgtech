  <footer>
    <div class="container">
      <div class="footer-grid">
        <div>
          <a class="logo" href="#home">XG<span>TECH</span></a>
          <p>Technology that moves your business forward. Online booking, scheduling and payments for service-based businesses.</p>
        </div>
        <div>
          <h4>Explore</h4>
          <a href="#features">Platform Features</a>
          <a href="#how-it-works">How It Works</a>
          <a href="#industries">Industries</a>
          <a href="#contact">Book a Demo</a>
          <a href="<?= e($site['product_url']) ?>">Booking System</a>
          <a href="<?= e($site['pricing_url']) ?>">Pricing</a>
        </div>
        <div>
          <h4>Contact <?= e($site['name']) ?></h4>
          <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
          <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a>
          <a href="<?= e($site['url']) ?>"><?= e(parse_url($site['url'], PHP_URL_HOST)) ?></a>
        </div>
      </div>
      <div class="copyright">
        <span>© <?= date('Y') ?> <?= e($site['name']) ?> Ltd. All rights reserved.</span>
        <span>Online Booking · Scheduling · Payments</span>
      </div>
    </div>
  </footer>
  <script src="assets/js/main.js"></script>
</body>
</html>
