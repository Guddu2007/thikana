<?php
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to(BASE_URL . '/roommate-board.php');
}

$posterName = trim($_POST['poster_name'] ?? '');
$preferredArea = trim($_POST['preferred_area'] ?? '');
$budget = (int) ($_POST['budget'] ?? 0);
$collegeOrWorkplace = trim($_POST['college_or_workplace'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$note = trim($_POST['note'] ?? '');
$whatsappNumber = normalize_phone($_POST['whatsapp_number'] ?? '');

if (
    $posterName === '' || $preferredArea === '' || $budget <= 0 || $collegeOrWorkplace === '' ||
    $gender === '' || $note === '' || $whatsappNumber === ''
) {
    set_flash('error', 'Please fill all roommate post fields.');
    redirect_to(BASE_URL . '/roommate-board.php');
}

$stmt = db()->prepare("
    INSERT INTO roommate_posts (
        poster_name, preferred_area, budget, college_or_workplace, gender, note,
        whatsapp_number, approved, expires_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 1, DATE_ADD(NOW(), INTERVAL 30 DAY))
");
$stmt->bind_param('ssissss', $posterName, $preferredArea, $budget, $collegeOrWorkplace, $gender, $note, $whatsappNumber);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    set_flash('success', 'Your roommate post is live for 30 days. People can contact you directly on WhatsApp.');
} else {
    set_flash('error', 'We could not publish your roommate post right now.');
}

redirect_to(BASE_URL . '/roommate-board.php');
