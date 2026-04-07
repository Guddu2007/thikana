<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Roommate Board';
$currentPage = 'roommate-board.php';

$posts = [];
$postResult = db()->query("
    SELECT *
    FROM roommate_posts
    WHERE approved = 1 AND expires_at > NOW()
    ORDER BY created_at DESC
");
if ($postResult) {
    while ($row = $postResult->fetch_assoc()) {
        $posts[] = $row;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <div class="surface">
            <span class="eyebrow">Community board</span>
            <h1>Find a roommate with a simple notice-style post</h1>
            <p>Posts are lightweight, practical, and expire automatically after 30 days so the board stays useful.</p>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container roommate-layout">
        <form class="card form-card" action="<?php echo BASE_URL; ?>/submit-roommate-post.php" method="POST" data-validate-form>
            <span class="eyebrow">Post a request</span>
            <div class="form-grid">
                <div class="form-field col-6">
                    <label for="poster_name">Name</label>
                    <input type="text" id="poster_name" name="poster_name" required minlength="2" maxlength="100">
                </div>
                <div class="form-field col-6">
                    <label for="preferred_area">Preferred area</label>
                    <input type="text" id="preferred_area" name="preferred_area" required minlength="3" maxlength="150">
                </div>
                <div class="form-field col-6">
                    <label for="budget">Budget</label>
                    <input type="number" id="budget" name="budget" min="0" required>
                </div>
                <div class="form-field col-6">
                    <label for="college_or_workplace">College or workplace</label>
                    <input type="text" id="college_or_workplace" name="college_or_workplace" required minlength="3" maxlength="150">
                </div>
                <div class="form-field col-6">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                        <option value="Any">Any</option>
                    </select>
                </div>
                <div class="form-field col-6">
                    <label for="whatsapp_number">WhatsApp number</label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" required pattern="[0-9]{10,15}" inputmode="numeric" maxlength="15">
                </div>
                <div class="form-field col-12">
                    <label for="note">Short note</label>
                    <textarea id="note" name="note" required minlength="20" maxlength="500" placeholder="Share your routine, move-in timeline, and what kind of roommate setup you want."></textarea>
                </div>
            </div>
            <p class="small-text">Posts are simple community notices and may be reviewed before publishing.</p>
            <div class="form-actions">
                <button type="submit">Post to board</button>
            </div>
            <p class="form-status" data-form-status aria-live="polite"></p>
        </form>

        <div class="details-stack">
            <div class="card">
                <span class="eyebrow">Active posts</span>
                <h2>Current roommate requests</h2>
                <p>Only approved posts that have not expired are shown here.</p>
            </div>

            <div class="roommate-list">
                <?php foreach ($posts as $post): ?>
                    <article class="roommate-card">
                        <div class="badge-row">
                            <span class="badge badge-info"><?php echo e($post['preferred_area']); ?></span>
                            <span class="badge badge-primary">Budget <?php echo e(format_price($post['budget'])); ?></span>
                            <span class="badge badge-success">Expires <?php echo e(date('d M', strtotime($post['expires_at']))); ?></span>
                        </div>
                        <h3><?php echo e($post['poster_name']); ?></h3>
                        <div class="roommate-meta">
                            <span><?php echo e($post['college_or_workplace']); ?></span>
                            <span><?php echo e($post['gender']); ?></span>
                            <span>Posted <?php echo e(date('d M Y', strtotime($post['created_at']))); ?></span>
                        </div>
                        <p><?php echo e($post['note']); ?></p>
                        <a class="btn btn-whatsapp" target="_blank" rel="noopener" href="<?php echo e(whatsapp_link($post['whatsapp_number'], 'Hi, I saw your roommate request on Thikana and wanted to discuss.')); ?>">Talk on WhatsApp</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
