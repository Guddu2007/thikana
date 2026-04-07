CREATE DATABASE IF NOT EXISTS thikana;
USE thikana;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS scam_reports;
DROP TABLE IF EXISTS roommate_posts;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS listing_media;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS owners;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE owners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    address VARCHAR(255) NOT NULL,
    nearby_college VARCHAR(150) NOT NULL,
    location_area VARCHAR(100) NOT NULL,
    rent INT NOT NULL,
    security_deposit INT NOT NULL,
    food_included ENUM('Yes', 'No') DEFAULT 'No',
    gender_allowed ENUM('Boys', 'Girls', 'Unisex') DEFAULT 'Unisex',
    room_type VARCHAR(100) NOT NULL,
    available_seats INT DEFAULT 1,
    description TEXT NOT NULL,
    rules TEXT NOT NULL,
    amenities TEXT NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    no_hidden_charges TINYINT(1) DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_listings_owner FOREIGN KEY (owner_id) REFERENCES owners(id) ON DELETE CASCADE
);

CREATE TABLE listing_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    media_type ENUM('room', 'washroom', 'kitchen', 'video') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_media_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    reviewer_name VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    review_text TEXT NOT NULL,
    proof_file VARCHAR(255) NOT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

CREATE TABLE roommate_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poster_name VARCHAR(100) NOT NULL,
    preferred_area VARCHAR(150) NOT NULL,
    budget INT NOT NULL,
    college_or_workplace VARCHAR(150) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    note TEXT NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL,
    approved TINYINT(1) DEFAULT 1,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE scam_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NULL,
    reporter_name VARCHAR(100) NOT NULL,
    contact_info VARCHAR(150) NOT NULL,
    issue_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    screenshot_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reports_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE SET NULL
);

INSERT INTO owners (owner_name, phone, whatsapp_number) VALUES
('Rahul Sharma', '9876500011', '919876500011'),
('Meera Sethi', '9876500022', '919876500022'),
('Imran Khan', '9876500033', '919876500033'),
('Nidhi Verma', '9876500044', '919876500044'),
('Arun Das', '9876500055', '919876500055');

INSERT INTO listings (
    owner_id, title, address, nearby_college, location_area, rent, security_deposit,
    food_included, gender_allowed, room_type, available_seats, description, rules,
    amenities, verified, no_hidden_charges, status
) VALUES
(
    1,
    'Sunrise Girls PG',
    '12 Lake Road, Sector 2, Kolkata',
    'Near Techno Main Salt Lake',
    'Salt Lake',
    7200,
    7200,
    'Yes',
    'Girls',
    'Double Sharing',
    2,
    'A bright and quiet girls PG with regular housekeeping, fresh meals, and a short auto ride to campus. Best for students who want simple pricing and a calm study routine.',
    'Curfew: 9:30 PM
Food restrictions: Outside food allowed in room only
Visitors policy: Female guardians only in common area
Late-night policy: Prior WhatsApp notice required
Gender preference: Girls only
Special house rule: Keep study floor quiet after 10 PM',
    'WiFi, Bed, Cupboard, Attached Washroom, Meals, Laundry, Power Backup',
    1,
    1,
    'approved'
),
(
    2,
    'Blue Nest Student Stay',
    '44 Civil Lines, Prayagraj',
    'Near Allahabad University',
    'Civil Lines',
    5600,
    5000,
    'No',
    'Unisex',
    'Triple Sharing',
    3,
    'Budget-friendly student stay with clear house rules, wide rooms, and a practical setup for college life. Good for students who want central access without brokerage drama.',
    'Curfew: 10:00 PM
Food restrictions: Self-cooking not allowed
Visitors policy: No room visitors
Late-night policy: Entry register mandatory
Gender preference: Separate floors for boys and girls
Special house rule: Electricity over fair-use billed monthly',
    'WiFi, Bed, Cupboard, Laundry, Power Backup',
    1,
    1,
    'approved'
),
(
    3,
    'Campus Corner Boys Hostel',
    '5/9 FC Road, Pune',
    'Near Fergusson College',
    'Deccan',
    6800,
    6000,
    'Yes',
    'Boys',
    'Double Sharing',
    4,
    'A practical boys hostel near coaching hubs and college routes. Includes meals, study-friendly timings, and regular cleaning in a well-connected area.',
    'Curfew: 10:30 PM
Food restrictions: Meals served on fixed timings
Visitors policy: Family visit in lobby only
Late-night policy: Late entry allowed with prior call
Gender preference: Boys only
Special house rule: Smoking strictly prohibited',
    'WiFi, Bed, Cupboard, Meals, Laundry, Power Backup',
    0,
    1,
    'approved'
),
(
    4,
    'Maple House Co-Living',
    '88 Bannerghatta Road, Bengaluru',
    'Near Christ University',
    'BTM Layout',
    9800,
    12000,
    'Yes',
    'Unisex',
    'Single Room',
    1,
    'Designed for students and young professionals who care about clean interiors, privacy, and a friendly shared community. Strong on transparency and regular maintenance.',
    'Curfew: No fixed curfew
Food restrictions: Veg and non-veg both allowed in dining area
Visitors policy: Day visitors allowed after entry note
Late-night policy: Security informed after 11 PM
Gender preference: Unisex with separate wings
Special house rule: Keep common kitchen clean after use',
    'WiFi, Bed, Cupboard, Attached Washroom, Meals, Laundry, Power Backup',
    1,
    1,
    'approved'
),
(
    5,
    'Budget Bridge PG',
    '17 University Road, Jaipur',
    'Near Rajasthan University',
    'Malviya Nagar',
    4800,
    4000,
    'No',
    'Boys',
    'Triple Sharing',
    3,
    'An affordable upcoming PG under manual review. The owner has shared basic details and media, and the listing stays pending until the Thikana team checks it fully.',
    'Curfew: 10:00 PM
Food restrictions: Shared induction only
Visitors policy: No visitors inside rooms
Late-night policy: Gate closes at 10 PM
Gender preference: Boys only
Special house rule: Keep ID proof ready at move-in',
    'WiFi, Bed, Cupboard',
    0,
    0,
    'pending'
);

INSERT INTO listing_media (listing_id, media_type, file_path) VALUES
(1, 'room', 'assets/images/room-sunrise.svg'),
(1, 'washroom', 'assets/images/washroom-soft.svg'),
(1, 'kitchen', 'assets/images/kitchen-warm.svg'),
(2, 'room', 'assets/images/room-blue.svg'),
(2, 'washroom', 'assets/images/washroom-soft.svg'),
(2, 'kitchen', 'assets/images/shared-space.svg'),
(3, 'room', 'assets/images/hostel-study.svg'),
(3, 'washroom', 'assets/images/washroom-soft.svg'),
(3, 'kitchen', 'assets/images/kitchen-warm.svg'),
(4, 'room', 'assets/images/shared-space.svg'),
(4, 'washroom', 'assets/images/washroom-soft.svg'),
(4, 'kitchen', 'assets/images/kitchen-warm.svg'),
(5, 'room', 'assets/images/room-blue.svg'),
(5, 'washroom', 'assets/images/washroom-soft.svg'),
(5, 'kitchen', 'assets/images/kitchen-warm.svg');

INSERT INTO reviews (listing_id, reviewer_name, rating, review_text, proof_file, approved) VALUES
(1, 'Ananya Raj', 5, 'The best part was that the rent was exactly what I was told on day one. Food was simple, rooms were clean, and the owner actually replies on time.', 'uploads/reviews/sample-proof-1.txt', 1),
(2, 'Rahul Kumar', 4, 'Good value for the location. The pricing was transparent and the listing photos matched the place closely. I would have liked meals, but it is fair for the budget.', 'uploads/reviews/sample-proof-2.txt', 1),
(3, 'Saket Mishra', 4, 'Clear rules, decent meals, and no surprise deposit demand later. Feels more trustworthy than random broker WhatsApp groups.', 'uploads/reviews/sample-proof-3.txt', 1),
(4, 'Apoorva Mukhija', 5, 'Clean interiors, safer vibe, and the house matched the media shared. The manual verification note made me trust the platform more.', 'uploads/reviews/sample-proof-4.txt', 1);

INSERT INTO roommate_posts (
    poster_name, preferred_area, budget, college_or_workplace, gender, note,
    whatsapp_number, approved, expires_at, created_at
) VALUES
('Priya Sen', 'Salt Lake', 7500, 'Techno Main Salt Lake', 'Female', 'Looking for a calm roommate who prefers early nights and shared meals.', '919812340001', 1, DATE_ADD(NOW(), INTERVAL 25 DAY), NOW()),
('Manish Gupta', 'Deccan', 6500, 'Fergusson College', 'Male', 'Need a roommate for double sharing near college. I am okay with simple food and study-heavy routine.', '919812340002', 1, DATE_ADD(NOW(), INTERVAL 18 DAY), NOW()),
('Sara Khan', 'BTM Layout', 10000, 'Christ University', 'Female', 'Open to co-living if the place is clean, secure, and has power backup.', '919812340003', 1, DATE_ADD(NOW(), INTERVAL 12 DAY), NOW());
