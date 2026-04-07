<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Report Scam';
$currentPage = 'submit-scam-report.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listingId = isset($_POST['listing_id']) && $_POST['listing_id'] !== '' ? (int) $_POST['listing_id'] : null;
    $reporterName = trim($_POST['reporter_name'] ?? '');
    $contactInfo = trim($_POST['contact_info'] ?? '');
    $issueType = trim($_POST['issue_type'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($reporterName === '' || $contactInfo === '' || $issueType === '' || $description === '') {
        set_flash('error', 'Please fill all required scam report fields.');
        redirect_to(BASE_URL . '/submit-scam-report.php' . ($listingId ? '?listing_id=' . $listingId : ''));
    }

    $screenshotPath = null;
    if (isset($_FILES['screenshot']) && ($_FILES['screenshot']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $screenshotUpload = upload_file(
            $_FILES['screenshot'],
            'uploads/reports',
            ['jpg', 'jpeg', 'png', 'webp'],
            5 * 1024 * 1024,
            ['image/jpeg', 'image/png', 'image/webp']
        );

        if (!$screenshotUpload['success']) {
            set_flash('error', 'Please upload a valid JPG, PNG, or WEBP screenshot.');
            redirect_to(BASE_URL . '/submit-scam-report.php' . ($listingId ? '?listing_id=' . $listingId : ''));
        }

        $screenshotPath = $screenshotUpload['path'];
    }

    $stmt = db()->prepare('INSERT INTO scam_reports (listing_id, reporter_name, contact_info, issue_type, description, screenshot_path) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssss', $listingId, $reporterName, $contactInfo, $issueType, $description, $screenshotPath);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        set_flash('success', 'Thank you. Your report has been submitted for review by the Thikana team.');
        redirect_to($listingId ? BASE_URL . '/listing-details.php?id=' . $listingId : BASE_URL . '/listings.php');
    }

    set_flash('error', 'We could not save your report right now.');
    redirect_to(BASE_URL . '/submit-scam-report.php' . ($listingId ? '?listing_id=' . $listingId : ''));
}

$selectedListingId = isset($_GET['listing_id']) ? (int) $_GET['listing_id'] : 0;
$listings = [];
$listingResult = db()->query("SELECT id, title FROM listings WHERE status = 'approved' ORDER BY title ASC");
if ($listingResult) {
    while ($row = $listingResult->fetch_assoc()) {
        $listings[] = $row;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <div class="surface">
            <span class="eyebrow">Trust protection</span>
            <h1>Report suspicious listing</h1>
            <p>If you noticed misleading media, fake owner behavior, pressure tactics, or pricing issues, send a clear report here.</p>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container form-shell">
        <form class="card form-card" action="<?php echo BASE_URL; ?>/submit-scam-report.php" method="POST" enctype="multipart/form-data" data-validate-form>
            <div class="form-grid">
                <div class="form-field col-12">
                    <label for="listing_id">Related listing</label>
                    <select id="listing_id" name="listing_id">
                        <option value="">Select listing if known</option>
                        <?php foreach ($listings as $listing): ?>
                            <option value="<?php echo (int) $listing['id']; ?>" <?php echo $selectedListingId === (int) $listing['id'] ? 'selected' : ''; ?>><?php echo e($listing['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field col-6">
                    <label for="reporter_name">Your name</label>
                    <input type="text" id="reporter_name" name="reporter_name" required minlength="2" maxlength="100">
                </div>
                <div class="form-field col-6">
                    <label for="contact_info">Contact info</label>
                    <input type="text" id="contact_info" name="contact_info" required minlength="6" maxlength="150" placeholder="Phone, email, or WhatsApp">
                </div>
                <div class="form-field col-12">
                    <label for="issue_type">Issue type</label>
                    <select id="issue_type" name="issue_type" required>
                        <option value="">Choose issue type</option>
                        <option value="Fake photos">Fake photos</option>
                        <option value="Hidden charges">Hidden charges</option>
                        <option value="Unavailable property">Unavailable property</option>
                        <option value="Harassment or pressure">Harassment or pressure</option>
                        <option value="Other suspicious issue">Other suspicious issue</option>
                    </select>
                </div>
                <div class="form-field col-12">
                    <label for="description">Detailed description</label>
                    <textarea id="description" name="description" required minlength="20" maxlength="1200" placeholder="Tell us what happened, what felt suspicious, and any useful context."></textarea>
                </div>
                <div class="form-field col-12">
                    <label for="screenshot">Optional screenshot</label>
                    <input type="file" id="screenshot" name="screenshot" accept=".jpg,.jpeg,.png,.webp" data-file-hint="#reportHint" data-max-size-mb="5" data-extensions="jpg,jpeg,png,webp">
                    <p class="small-text" id="reportHint">No file chosen</p>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit">Submit Report</button>
            </div>
            <p class="form-status" data-form-status aria-live="polite"></p>
        </form>

        <aside class="details-stack">
            <div class="card helper-box">
                <h3>Why this matters</h3>
                <p>Reporting suspicious listings helps keep the platform more transparent for the next student searching in a hurry.</p>
            </div>
            <div class="card helper-box">
                <h3>What the team checks</h3>
                <p>Thikana reviews pricing mismatches, false media, identity issues, and any proof you attach.</p>
            </div>
        </aside>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
