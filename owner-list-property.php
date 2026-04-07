<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'List Property';
$currentPage = 'owner-list-property.php';

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <div class="surface">
            <span class="eyebrow">Owner submission</span>
            <h1>List your property the simple way</h1>
            <p>Share clear details, upload standard media, and your listing will go live only after manual verification by the Thikana team.</p>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container form-shell">
        <form class="card form-card" action="<?php echo BASE_URL; ?>/process-owner-listing.php" method="POST" enctype="multipart/form-data" data-validate-form>
            <span class="eyebrow">Owner details</span>
            <div class="form-grid">
                <div class="form-field col-4">
                    <label for="owner_name">Owner name</label>
                    <input type="text" id="owner_name" name="owner_name" required minlength="2" maxlength="100">
                </div>
                <div class="form-field col-4">
                    <label for="phone">Phone number</label>
                    <input type="tel" id="phone" name="phone" required pattern="[0-9]{10,15}" inputmode="numeric" maxlength="15">
                    <p class="field-hint">Use digits only, for example 9876500011.</p>
                </div>
                <div class="form-field col-4">
                    <label for="whatsapp_number">WhatsApp number</label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" required pattern="[0-9]{10,15}" inputmode="numeric" maxlength="15">
                    <p class="field-hint">Country code is okay, for example 919876500011.</p>
                </div>
            </div>

            <span class="eyebrow" style="margin-top: 1.5rem;">Property details</span>
            <div class="form-grid">
                <div class="form-field col-6">
                    <label for="title">Property title</label>
                    <input type="text" id="title" name="title" required minlength="4" maxlength="150">
                </div>
                <div class="form-field col-6">
                    <label for="address">Full address</label>
                    <input type="text" id="address" name="address" required minlength="8" maxlength="255">
                </div>
                <div class="form-field col-6">
                    <label for="nearby_college">Nearby college or landmark</label>
                    <input type="text" id="nearby_college" name="nearby_college" required minlength="3" maxlength="150">
                </div>
                <div class="form-field col-6">
                    <label for="location_area">Location area</label>
                    <input type="text" id="location_area" name="location_area" required minlength="3" maxlength="100">
                </div>
                <div class="form-field col-3">
                    <label for="rent">Monthly rent</label>
                    <input type="number" id="rent" name="rent" min="0" required>
                </div>
                <div class="form-field col-3">
                    <label for="security_deposit">Security deposit</label>
                    <input type="number" id="security_deposit" name="security_deposit" min="0" required>
                </div>
                <div class="form-field col-3">
                    <label for="food_included">Food included</label>
                    <select id="food_included" name="food_included" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="form-field col-3">
                    <label for="gender_allowed">Gender allowed</label>
                    <select id="gender_allowed" name="gender_allowed" required>
                        <option value="Boys">Boys</option>
                        <option value="Girls">Girls</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>
                <div class="form-field col-4">
                    <label for="room_type">Room type</label>
                    <input type="text" id="room_type" name="room_type" placeholder="Single / Double Sharing" required minlength="3" maxlength="100">
                </div>
                <div class="form-field col-4">
                    <label for="available_seats">Available seats</label>
                    <input type="number" id="available_seats" name="available_seats" min="1" required>
                </div>
                <div class="form-field col-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required minlength="30" maxlength="1200" placeholder="Tell students what makes your property suitable."></textarea>
                </div>
                <div class="form-field col-6">
                    <label for="rules">Rules</label>
                    <textarea id="rules" name="rules" required minlength="20" maxlength="1200" placeholder="Example:
Curfew: 10 PM
Visitors policy: Lobby only
Late-night policy: Prior call required"></textarea>
                </div>
                <div class="form-field col-6">
                    <label for="amenities">Amenities</label>
                    <textarea id="amenities" name="amenities" required minlength="10" maxlength="800" placeholder="Example:
WiFi, Bed, Cupboard, Laundry, Power Backup"></textarea>
                </div>
            </div>

            <span class="eyebrow" style="margin-top: 1.5rem;">Media uploads</span>
            <div class="form-grid">
                <div class="form-field col-6">
                    <label for="room_photo">Room photo</label>
                    <input type="file" id="room_photo" name="room_photo" accept=".jpg,.jpeg,.png,.webp" required data-file-hint="#roomPhotoHint" data-max-size-mb="5" data-extensions="jpg,jpeg,png,webp">
                    <p class="small-text" id="roomPhotoHint">No file chosen</p>
                </div>
                <div class="form-field col-6">
                    <label for="washroom_photo">Washroom photo</label>
                    <input type="file" id="washroom_photo" name="washroom_photo" accept=".jpg,.jpeg,.png,.webp" required data-file-hint="#washroomPhotoHint" data-max-size-mb="5" data-extensions="jpg,jpeg,png,webp">
                    <p class="small-text" id="washroomPhotoHint">No file chosen</p>
                </div>
                <div class="form-field col-6">
                    <label for="kitchen_photo">Kitchen photo</label>
                    <input type="file" id="kitchen_photo" name="kitchen_photo" accept=".jpg,.jpeg,.png,.webp" required data-file-hint="#kitchenPhotoHint" data-max-size-mb="5" data-extensions="jpg,jpeg,png,webp">
                    <p class="small-text" id="kitchenPhotoHint">No file chosen</p>
                </div>
                <div class="form-field col-6">
                    <label for="property_video">Short MP4 video under 10MB</label>
                    <input type="file" id="property_video" name="property_video" accept=".mp4" data-file-hint="#videoHint" data-max-size-mb="10" data-extensions="mp4">
                    <p class="small-text" id="videoHint">No file chosen</p>
                </div>
            </div>

            <div class="checkbox-row" style="margin-top: 1.5rem;">
                <label class="checkbox-card">
                    <input type="checkbox" name="no_hidden_charges" value="1" required>
                    <span>I confirm there are no hidden charges.</span>
                </label>
                <label class="checkbox-card">
                    <input type="checkbox" name="manual_verification_ack" value="1" required>
                    <span>I understand this listing goes live only after manual verification.</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit">Submit Listing</button>
                <a class="btn btn-outline" href="<?php echo BASE_URL; ?>/listings.php">Back to listings</a>
            </div>
            <p class="form-status" data-form-status aria-live="polite"></p>
        </form>

        <aside class="details-stack">
            <div class="card helper-box">
                <span class="eyebrow">What owners should know</span>
                <h3>Keep it clear, and approval is easier</h3>
                <p>Students respond better when the listing is simple, honest, and matched to the actual property. Clear rules are better than perfect marketing copy.</p>
            </div>
            <div class="card helper-box">
                <h3>Media checklist</h3>
                <ul class="mini-list">
                    <li>One room photo</li>
                    <li>One washroom photo</li>
                    <li>One kitchen photo</li>
                    <li>One short MP4 walkthrough</li>
                </ul>
            </div>
            <div class="card helper-box">
                <h3>Manual review note</h3>
                <p>Verified badges are never automatic. The Thikana team checks details before any public verification label appears.</p>
            </div>
        </aside>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
