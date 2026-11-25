<?php
// Site-wide settings. Edit these values to match your business.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$site = [
    'name'    => 'XGTECH',
    'tagline' => 'Online Booking & Scheduling Software',
    'email'   => 'info@xgtech.co.uk',
    'phone'   => '0151 246 1103',
    'phone_raw' => '01512461103',
    'url'     => 'https://xgtech.co.uk/',
    // Pages on the main XGTECH website
    'product_url' => 'https://xgtech.co.uk/booking_sys.html',
    'pricing_url' => 'https://xgtech.co.uk/pricing.html',
    'contact_url' => 'https://xgtech.co.uk/contact.php',
];

// Database (XAMPP defaults: user root, empty password).
// Change these on a live server.
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_NAME', 'booking-system');
define('DB_USER', 'root');
define('DB_PASS', '');

require_once __DIR__ . '/db.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function flash(string $key, $value = null)
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
}
