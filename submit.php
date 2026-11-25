<?php
// Handles the "Book a Free Demo" form: validate, save, redirect back.
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Send the visitor back to the page the form was on (whitelisted).
$pages  = ['index.php', 'classic.php', 'book-nboost.php'];
$source = in_array($_POST['source'] ?? '', $pages, true) ? $_POST['source'] : 'index.php';
$back   = $source . '#contact';

// CSRF check
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    flash('errors', ['form' => 'Session expired, please try again.']);
    header("Location: $back");
    exit;
}

// Honeypot: bots fill hidden fields. Pretend success, store nothing.
if (!empty($_POST['website'])) {
    flash('success', 'Thank you! We will be in touch shortly.');
    header("Location: $back");
    exit;
}

$input = [
    'name'     => trim($_POST['name'] ?? ''),
    'business' => trim($_POST['business'] ?? ''),
    'email'    => trim($_POST['email'] ?? ''),
    'phone'    => trim($_POST['phone'] ?? ''),
    'service'  => trim($_POST['service'] ?? ''),
    'date'     => trim($_POST['date'] ?? ''),
    'message'  => trim($_POST['message'] ?? ''),
];

$errors = [];
if ($input['name'] === '' || mb_strlen($input['name']) > 100) {
    $errors['name'] = 'Please enter your name.';
}
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}
if ($input['phone'] !== '' && !preg_match('/^[0-9 +()\-]{7,20}$/', $input['phone'])) {
    $errors['phone'] = 'Please enter a valid phone number.';
}
if (!in_array($input['service'], $services, true)) {
    $input['service'] = 'Something else';
}
if ($input['date'] !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $input['date']);
    if (!$d || $d->format('Y-m-d') !== $input['date'] || $input['date'] < date('Y-m-d')) {
        $errors['date'] = 'Please choose today or a future date.';
    }
}
$input['business'] = mb_substr($input['business'], 0, 150);
$input['message']  = mb_substr($input['message'], 0, 2000);

if ($errors) {
    flash('errors', $errors);
    flash('old', $input);
    header("Location: $back");
    exit;
}

try {
    $stmt = db()->prepare(
        'INSERT INTO demo_requests (name, business, email, phone, service, demo_date, message, source_page, ip_address)
         VALUES (:name, :business, :email, :phone, :service, :demo_date, :message, :source, :ip)'
    );
    $stmt->execute([
        ':name'      => $input['name'],
        ':business'  => $input['business'] !== '' ? $input['business'] : null,
        ':email'     => $input['email'],
        ':phone'     => $input['phone'] !== '' ? $input['phone'] : null,
        ':service'   => $input['service'],
        ':demo_date' => $input['date'] !== '' ? $input['date'] : null,
        ':message'   => $input['message'] !== '' ? $input['message'] : null,
        ':source'    => $source,
        ':ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (PDOException $e) {
    error_log('Demo request save failed: ' . $e->getMessage());
    flash('errors', ['form' => 'db']);
    flash('old', $input);
    flash('db_error', 'Sorry, we could not save your request right now. Please call or email us instead.');
    header("Location: $back");
    exit;
}

// Optional email notification (works when the server has mail configured).
// @mail($site['email'], 'New demo request: ' . $input['name'], print_r($input, true), 'Reply-To: ' . $input['email']);

unset($_SESSION['csrf']);
flash('success', 'Thank you, ' . $input['name'] . '! Your demo request has been received. We will contact you shortly.');
header("Location: $back");
exit;
