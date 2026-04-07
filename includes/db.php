<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'thikana');
define('BASE_URL', '/thikana');
define('PROJECT_ROOT', dirname(__DIR__));

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error . '. Please import schema.sql into phpMyAdmin first.');
}

$mysqli->set_charset('utf8mb4');

function db()
{
    global $mysqli;
    return $mysqli;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function normalize_phone($value)
{
    return preg_replace('/[^0-9]/', '', (string) $value);
}

function set_flash($type, $message)
{
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash()
{
    if (!isset($_SESSION['flash_message'])) {
        return null;
    }

    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);

    return $flash;
}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit;
}

function whatsapp_link($number, $message)
{
    $cleanNumber = normalize_phone($number);
    return 'https://wa.me/' . $cleanNumber . '?text=' . rawurlencode($message);
}

function upload_file($file, $targetFolder, $allowedExtensions, $maxSizeBytes = null, $allowedMimeTypes = [])
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'No file uploaded.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload failed. Please try again.'];
    }

    if ($maxSizeBytes !== null && $file['size'] > $maxSizeBytes) {
        return ['success' => false, 'message' => 'Uploaded file is too large.'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        return ['success' => false, 'message' => 'Unsupported file type uploaded.'];
    }

    if (!empty($allowedMimeTypes)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return ['success' => false, 'message' => 'Uploaded file type did not match the expected format.'];
        }
    }

    $safeName = uniqid('thikana_', true) . '.' . $extension;
    $absoluteFolder = PROJECT_ROOT . '/' . trim($targetFolder, '/');

    if (!is_dir($absoluteFolder)) {
        mkdir($absoluteFolder, 0777, true);
    }

    $absolutePath = $absoluteFolder . '/' . $safeName;
    $relativePath = trim($targetFolder, '/') . '/' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
        return ['success' => false, 'message' => 'Could not save the uploaded file.'];
    }

    return ['success' => true, 'path' => $relativePath];
}

function get_listing_media_map($listingId)
{
    $stmt = db()->prepare('SELECT media_type, file_path FROM listing_media WHERE listing_id = ? ORDER BY id ASC');
    $stmt->bind_param('i', $listingId);
    $stmt->execute();
    $result = $stmt->get_result();

    $media = [
        'room' => null,
        'washroom' => null,
        'kitchen' => null,
        'video' => null,
    ];

    while ($row = $result->fetch_assoc()) {
        $media[$row['media_type']] = $row['file_path'];
    }

    $stmt->close();
    return $media;
}

function format_price($amount)
{
    return 'Rs ' . number_format((int) $amount);
}

function excerpt($text, $limit = 140)
{
    $text = trim((string) $text);
    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit - 3)) . '...';
}

function star_rating_html($rating)
{
    $rating = max(1, min(5, (int) $rating));
    $filled = str_repeat('&#9733;', $rating);
    $empty = str_repeat('&#9734;', 5 - $rating);

    return '<span class="stars" aria-label="' . $rating . ' out of 5 stars">' . $filled . $empty . '</span>';
}

function parse_list($text)
{
    $items = preg_split('/\r\n|\r|\n|,/', (string) $text);
    $cleanItems = [];

    foreach ($items as $item) {
        $trimmed = trim($item);
        if ($trimmed !== '') {
            $cleanItems[] = $trimmed;
        }
    }

    return $cleanItems;
}

function current_page()
{
    return basename($_SERVER['PHP_SELF'] ?? '');
}
?>
