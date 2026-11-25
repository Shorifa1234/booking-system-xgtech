<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Online bookings, scheduling, payments and customer management in one platform with <?= e($site['name']) ?>. Book a free demo.">
  <title><?= e($site['tagline']) ?> | <?= e($site['name']) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="topbar">
    <div class="container">
      <span>One platform to publish, book, schedule and sell your services.</span>
      <span><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a> &nbsp;|&nbsp; <a href="tel:<?= e($site['phone_raw']) ?>"><?= e($site['phone']) ?></a></span>
    </div>
  </div>

  <header>
    <div class="container nav">
      <a class="logo" href="#home" aria-label="<?= e($site['name']) ?> home">XG<span>TECH</span></a>
      <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
      <nav class="navlinks" aria-label="Main navigation">
        <a href="#features">Features</a>
        <a href="#how-it-works">How it works</a>
        <a href="#industries">Who it's for</a>
        <a href="#faq">FAQ</a>
        <a href="#contact" class="btn">Book a Free Demo</a>
      </nav>
    </div>
  </header>
