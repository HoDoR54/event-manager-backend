<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/db.php';

echo "\033[1;36m====================================================\033[0m\n";
echo "\033[1;36m        EVENT MANAGER DATABASE SEEDER               \033[0m\n";
echo "\033[1;36m====================================================\033[0m\n\n";

$seedUsers = [
    [
        'id' => 1,
        'name' => 'Admin Manager',
        'email' => 'admin@eventmanager.com',
        'password' => 'admin123',
        'role' => 'ADMIN',
    ],
    [
        'id' => 2,
        'name' => 'Alex User',
        'email' => 'user@eventmanager.com',
        'password' => 'user123',
        'role' => 'USER',
    ],
    [
        'id' => 3,
        'name' => 'Thura Aung',
        'email' => 'thura@eventmanager.mm',
        'password' => 'thura123',
        'role' => 'USER',
    ],
];

$seedEvents = [
    [
        'id' => 1,
        'clientId' => 1,
        'name' => 'Myanmar SME & Startup Innovation Expo 2026',
        'date' => '2026-11-28',
        'location' => 'Myanmar Expo Hall, Fortune Plaza, Yangon',
        'organiser' => 'Myanmar Young Entrepreneurs Association (MYEA)',
        'type' => 'CONFERENCE',
        'status' => 'UPCOMING',
        'description' => 'National expo uniting over 150 local SMEs, agritech ventures, e-commerce retailers, and venture capital partners exploring sustainable domestic commerce and digital banking.',
        'contactEmail' => 'contact@myea.org.mm',
        'contactPhone' => '+95 9 795 112233',
        'sessions' => [
            [
                'id' => 1,
                'sessionId' => 'SME-01',
                'name' => 'Keynote: Scaling Local MSMEs in the Digital Era',
                'time' => '09:00 - 10:15',
                'type' => 'KEYNOTE',
                'speaker' => 'Daw Khin Sandar Tun (President, MYEA)',
                'room' => 'Main Grand Hall',
                'description' => 'Strategic frameworks for expanding Myanmar domestic brands into regional markets.',
            ],
            [
                'id' => 2,
                'sessionId' => 'SME-02',
                'name' => 'Fintech Integration: KBZPay, WavePay & QR Payments',
                'time' => '10:30 - 12:00',
                'type' => 'WORKSHOP',
                'speaker' => 'U Aung Myo Min',
                'room' => 'Workshop Room B',
                'description' => 'Practical setup for omni-channel retail payments and reconciliation for small businesses.',
            ],
            [
                'id' => 3,
                'sessionId' => 'SME-03',
                'name' => 'Founders & Investors Speed Networking',
                'time' => '13:30 - 15:00',
                'type' => 'NETWORKING',
                'speaker' => null,
                'room' => 'Executive Atrium',
                'description' => 'Curated 1-on-1 sessions matching regional startups with micro-finance and angel investors.',
            ],
        ],
        'images' => [
            [
                'id' => 1,
                'imageUrl' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Main Exhibition Hall & SME Showcases',
            ],
        ],
    ],
    [
        'id' => 2,
        'clientId' => 2,
        'name' => 'Yangon Literature & Cultural Arts Festival',
        'date' => '2026-12-05',
        'location' => 'The Secretariat (ဝန်ကြီးများရုံး), Yangon',
        'organiser' => 'Myanmar Literary & Arts Guild',
        'type' => 'SOCIAL',
        'status' => 'UPCOMING',
        'description' => 'A celebration of Myanmar contemporary writing, poetry, indigenous folklore, and visual arts hosted across the colonial courtyards of the historic Secretariat.',
        'contactEmail' => 'info@yangonlitfest.mm',
        'contactPhone' => '+95 9 450 998877',
        'sessions' => [
            [
                'id' => 4,
                'sessionId' => 'LIT-01',
                'name' => 'Modern Burmese Prose & Storytelling in Translation',
                'time' => '10:00 - 11:30',
                'type' => 'PRESENTATION',
                'speaker' => 'Sayar Min Lu Tribute Circle',
                'room' => 'Heritage Gallery 1',
                'description' => 'Preserving historical memoirs and fostering modern Burmese literary translation into international languages.',
            ],
            [
                'id' => 5,
                'sessionId' => 'LIT-02',
                'name' => 'Panel: Safeguarding Ethnic Folktales & Oral Traditions',
                'time' => '13:00 - 14:30',
                'type' => 'PANEL_DISCUSSION',
                'speaker' => 'Daw Thinzar Myint & Cultural Ethnographers',
                'room' => 'Courtyard Stage',
                'description' => 'Documenting oral heritage from Shan, Kachin, Chin, and Kayin communities.',
            ],
            [
                'id' => 6,
                'sessionId' => 'LIT-03',
                'name' => 'Acoustic Poetry Recital & Classical Harp Performance',
                'time' => '16:00 - 17:30',
                'type' => 'OTHERS',
                'speaker' => 'Yangon Youth Poetry Collective',
                'room' => 'North Garden Terrace',
                'description' => 'Evening poetry recitals accompanied by traditional Myanmar Saung Gauk (harp) music.',
            ],
        ],
        'images' => [
            [
                'id' => 2,
                'imageUrl' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'The Secretariat Heritage Courtyard Book Fair',
            ],
        ],
    ],
    [
        'id' => 3,
        'clientId' => 3,
        'name' => 'Tazaungdaing Lantern Festival & Community Gala',
        'date' => '2026-11-24',
        'location' => 'Mandalay Convention Centre Arena, Mandalay',
        'organiser' => 'Upper Myanmar Cultural Heritage Foundation',
        'type' => 'OTHER',
        'status' => 'UPCOMING',
        'description' => 'Commemorating the festival of lights with traditional lantern displays, artisanal weaving competitions, night bazaar stalls, and community charity distribution.',
        'contactEmail' => 'festivals@mandalayheritage.org',
        'contactPhone' => '+95 9 201 334455',
        'sessions' => [
            [
                'id' => 7,
                'sessionId' => 'TAZ-01',
                'name' => 'Traditional Bamboo & Paper Lantern Crafting',
                'time' => '14:00 - 16:00',
                'type' => 'WORKSHOP',
                'speaker' => 'U Hla Win (Master Craftsman)',
                'room' => 'Open Craft Pavilion',
                'description' => 'Hands-on workshop teaching traditional Mandalay lantern making techniques for families and youth.',
            ],
            [
                'id' => 8,
                'sessionId' => 'TAZ-02',
                'name' => 'Festival of Lights Opening & Charity Robe Offering',
                'time' => '18:00 - 20:30',
                'type' => 'OTHERS',
                'speaker' => 'Heritage Preservation Committee',
                'room' => 'Grand Arena Stage',
                'description' => 'Ceremonial Matho Thingan weaving showcase and evening illumination celebration.',
            ],
        ],
        'images' => [
            [
                'id' => 3,
                'imageUrl' => 'https://images.unsplash.com/photo-1514565131-fce0801e5785?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Tazaungdaing Night Illuminations & Lanterns',
            ],
        ],
    ],
    [
        'id' => 4,
        'clientId' => 4,
        'name' => 'BarCamp Yangon 2026: Open Tech & Community',
        'date' => '2026-12-12',
        'location' => 'MICT Park, Hlaing Township, Yangon',
        'organiser' => 'BarCamp Yangon Organizing Committee',
        'type' => 'CONFERENCE',
        'status' => 'UPCOMING',
        'description' => 'Myanmar\'s flagship community-driven unconference connecting software engineers, designers, students, and open-source advocates for collaborative knowledge sharing.',
        'contactEmail' => 'volunteers@barcampyangon.org',
        'contactPhone' => '+95 9 778 556677',
        'sessions' => [
            [
                'id' => 9,
                'sessionId' => 'BC-01',
                'name' => 'Burmese NLP & LLM Localization Challenges',
                'time' => '09:30 - 10:45',
                'type' => 'PRESENTATION',
                'speaker' => 'Ko Thura Aung',
                'room' => 'Building 9, Auditorium',
                'description' => 'Addressing font encoding, tokenization, and cross-lingual embeddings for the Myanmar language.',
            ],
            [
                'id' => 10,
                'sessionId' => 'BC-02',
                'name' => 'Hands-on Kotlin & Offline-First Mobile Architectures',
                'time' => '11:00 - 12:30',
                'type' => 'WORKSHOP',
                'speaker' => 'Ma Ei Phyu Zin',
                'room' => 'Lab 3',
                'description' => 'Building resilient Room + SQLite mobile workflows for low-connectivity environments.',
            ],
            [
                'id' => 11,
                'sessionId' => 'BC-03',
                'name' => 'Panel: Empowering Women in STEM Across Myanmar',
                'time' => '14:00 - 15:15',
                'type' => 'PANEL_DISCUSSION',
                'speaker' => 'Myanmar Women in Technology',
                'room' => 'Main Hall',
                'description' => 'Mentorship programs, remote career paths, and technical leadership for women in tech.',
            ],
        ],
        'images' => [
            [
                'id' => 4,
                'imageUrl' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'MICT Park Auditorium Developer Unconference',
            ],
        ],
    ],
    [
        'id' => 5,
        'clientId' => 5,
        'name' => 'Myanmar Traditional Cuisine & Specialty Coffee Summit',
        'date' => '2026-12-19',
        'location' => 'Inya Lake Hotel Ballroom & Lakeside Lawn, Yangon',
        'organiser' => 'Myanmar Culinary & Hospitality Guild',
        'type' => 'WORKSHOP',
        'status' => 'UPCOMING',
        'description' => 'Exploring regional gastronomic traditions from Shan, Rakhine, and Mon states, highlighting artisan Laphet (fermented tea) and Ywangan specialty coffee exports.',
        'contactEmail' => 'events@myanmarculinary.org',
        'contactPhone' => '+95 9 420 112233',
        'sessions' => [
            [
                'id' => 12,
                'sessionId' => 'CUL-01',
                'name' => 'The Chemistry and Art of Shan Laphet Fermentation',
                'time' => '10:00 - 11:30',
                'type' => 'WORKSHOP',
                'speaker' => 'Daw May Thet (Culinary Heritage Scholar)',
                'room' => 'Culinary Demonstration Studio',
                'description' => 'Traditional fermentation cycles, quality grading, and pairing tea leaf salad with regional staples.',
            ],
            [
                'id' => 13,
                'sessionId' => 'CUL-02',
                'name' => 'Ywangan to Global Cafes: Single-Origin Coffee Cupping',
                'time' => '13:00 - 14:30',
                'type' => 'WORKSHOP',
                'speaker' => 'Southern Shan Coffee Growers Co-op',
                'room' => 'Lakeside Tasting Deck',
                'description' => 'Live cupping and sensory assessment of Arabica varieties cultivated in Southern Shan State.',
            ],
        ],
        'images' => [
            [
                'id' => 5,
                'imageUrl' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Culinary Masterclass & Artisan Tasting Pavilion',
            ],
        ],
    ],
    [
        'id' => 6,
        'clientId' => 6,
        'name' => 'Mandalay Youth Healthcare & Mental Wellness Seminar',
        'date' => '2026-10-20',
        'location' => 'University of Medicine Mandalay Auditorium, Mandalay',
        'organiser' => 'Myanmar Medical Students Association (MMSA)',
        'type' => 'SEMINAR',
        'status' => 'COMPLETED',
        'description' => 'An educational public healthcare initiative addressing university student wellness, stress management, and community health volunteer training.',
        'contactEmail' => 'wellness@mmsa-myanmar.org',
        'contactPhone' => '+95 9 965 432100',
        'sessions' => [
            [
                'id' => 14,
                'sessionId' => 'MED-01',
                'name' => 'De-stigmatizing Mental Health Support in Higher Ed',
                'time' => '09:00 - 10:30',
                'type' => 'KEYNOTE',
                'speaker' => 'Dr. Kyaw Swar Myint',
                'room' => 'Medical Hall A',
                'description' => 'Evidence-based strategies for student peer counseling, emotional resilience, and clinical support.',
            ],
            [
                'id' => 15,
                'sessionId' => 'MED-02',
                'name' => 'Mindfulness and Breathing Techniques Workshop',
                'time' => '11:00 - 12:15',
                'type' => 'WORKSHOP',
                'speaker' => 'Dr. Nilar Win',
                'room' => 'Studio 2',
                'description' => 'Interactive guided relaxation routines designed for demanding academic schedules.',
            ],
        ],
        'images' => [
            [
                'id' => 6,
                'imageUrl' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Public Health Seminar at University of Medicine',
            ],
        ],
    ],
    [
        'id' => 7,
        'clientId' => 7,
        'name' => 'Yangon Clean Energy & Sustainability Roundtable',
        'date' => '2027-01-15',
        'location' => 'Lotte Hotel Yangon, Crystal Ballroom, Yangon',
        'organiser' => 'Green Myanmar Sustainable Development Initiative',
        'type' => 'SEMINAR',
        'status' => 'UPCOMING',
        'description' => 'High-level forum convening engineers, municipal planners, and environmental scientists to discuss rooftop solar grids, waste recycling, and urban greenery.',
        'contactEmail' => 'forum@greenmyanmar.org',
        'contactPhone' => '+95 9 765 890123',
        'sessions' => [
            [
                'id' => 16,
                'sessionId' => 'ENV-01',
                'name' => 'Decentralized Solar & Battery Microgrids for Communities',
                'time' => '09:30 - 11:00',
                'type' => 'PRESENTATION',
                'speaker' => 'U Zaw Min Oo (Renewable Energy Lead)',
                'room' => 'Crystal Ballroom',
                'description' => 'Case studies on cost-effective commercial rooftop photovoltaic setups in Yangon industrial zones.',
            ],
            [
                'id' => 17,
                'sessionId' => 'ENV-02',
                'name' => 'Panel: Circular Economy & Municipal Waste Reduction',
                'time' => '11:30 - 13:00',
                'type' => 'PANEL_DISCUSSION',
                'speaker' => 'Urban Recycling Advocates & Civic Leaders',
                'room' => 'Meeting Suite 3',
                'description' => 'Practical roadmaps for plastic reduction and neighborhood composting programs.',
            ],
        ],
        'images' => [
            [
                'id' => 7,
                'imageUrl' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Sustainability Forum at Lotte Hotel Ballroom',
            ],
        ],
    ],
];

$seedFavorites = [
    ['id' => 1, 'userId' => 2, 'eventId' => 1],
    ['id' => 2, 'userId' => 2, 'eventId' => 4],
    ['id' => 3, 'userId' => 2, 'eventId' => 2],
];

$now = gmdate('Y-m-d H:i:s');

try {
    if (checkDbHealth()) {
        echo "Target Mode: \033[1;32mPostgreSQL Database\033[0m\n\n";
        $pdo = getDb();
        $pdo->beginTransaction();

        // 1. Clear existing seedable tables in order
        $pdo->exec('DELETE FROM user_favorites;');
        $pdo->exec('DELETE FROM event_images;');
        $pdo->exec('DELETE FROM sessions;');
        $pdo->exec('DELETE FROM events;');
        $pdo->exec('DELETE FROM users;');

        // Reset sequences
        $pdo->exec('ALTER SEQUENCE users_id_seq RESTART WITH 1;');
        $pdo->exec('ALTER SEQUENCE events_id_seq RESTART WITH 1;');
        $pdo->exec('ALTER SEQUENCE sessions_id_seq RESTART WITH 1;');
        $pdo->exec('ALTER SEQUENCE event_images_id_seq RESTART WITH 1;');
        $pdo->exec('ALTER SEQUENCE user_favorites_id_seq RESTART WITH 1;');

        // 2. Seed Users
        $insertUserStmt = $pdo->prepare(
            'INSERT INTO users (id, name, email, password_hash, role, created_at, updated_at) ' .
            'VALUES (:id, :name, :email, :password_hash, :role, :created_at, :updated_at)'
        );
        foreach ($seedUsers as $u) {
            $insertUserStmt->execute([
                ':id' => $u['id'],
                ':name' => $u['name'],
                ':email' => strtolower($u['email']),
                ':password_hash' => password_hash($u['password'], PASSWORD_BCRYPT),
                ':role' => $u['role'],
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        }
        $pdo->exec('SELECT setval(\'users_id_seq\', (SELECT MAX(id) FROM users));');

        // 3. Seed Events, Sessions, and Images
        $insertEventStmt = $pdo->prepare(
            'INSERT INTO events (id, client_id, name, date, location, organiser, type, status, description, contact_email, contact_phone, created_at, updated_at) ' .
            'VALUES (:id, :client_id, :name, :date, :location, :organiser, :type, :status, :description, :contact_email, :contact_phone, :created_at, :updated_at)'
        );
        $insertSessionStmt = $pdo->prepare(
            'INSERT INTO sessions (id, event_id, session_id, name, time, type, speaker, room, description, created_at, updated_at) ' .
            'VALUES (:id, :event_id, :session_id, :name, :time, :type, :speaker, :room, :description, :created_at, :updated_at)'
        );
        $insertImageStmt = $pdo->prepare(
            'INSERT INTO event_images (id, event_id, image_url, image_data, caption, created_at) ' .
            'VALUES (:id, :event_id, :image_url, :image_data, :caption, :created_at)'
        );

        $totalSessions = 0;
        $totalImages = 0;

        foreach ($seedEvents as $ev) {
            $insertEventStmt->execute([
                ':id' => $ev['id'],
                ':client_id' => $ev['clientId'],
                ':name' => $ev['name'],
                ':date' => $ev['date'],
                ':location' => $ev['location'],
                ':organiser' => $ev['organiser'],
                ':type' => $ev['type'],
                ':status' => $ev['status'],
                ':description' => $ev['description'],
                ':contact_email' => $ev['contactEmail'],
                ':contact_phone' => $ev['contactPhone'],
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);

            foreach ($ev['sessions'] as $s) {
                $insertSessionStmt->execute([
                    ':id' => $s['id'],
                    ':event_id' => $ev['id'],
                    ':session_id' => $s['sessionId'],
                    ':name' => $s['name'],
                    ':time' => $s['time'],
                    ':type' => $s['type'],
                    ':speaker' => $s['speaker'],
                    ':room' => $s['room'],
                    ':description' => $s['description'],
                    ':created_at' => $now,
                    ':updated_at' => $now,
                ]);
                $totalSessions++;
            }

            foreach ($ev['images'] as $img) {
                $insertImageStmt->execute([
                    ':id' => $img['id'],
                    ':event_id' => $ev['id'],
                    ':image_url' => $img['imageUrl'],
                    ':image_data' => $img['imageData'],
                    ':caption' => $img['caption'],
                    ':created_at' => $now,
                ]);
                $totalImages++;
            }
        }
        $pdo->exec('SELECT setval(\'events_id_seq\', (SELECT MAX(id) FROM events));');
        $pdo->exec('SELECT setval(\'sessions_id_seq\', (SELECT MAX(id) FROM sessions));');
        $pdo->exec('SELECT setval(\'event_images_id_seq\', (SELECT MAX(id) FROM event_images));');

        // 4. Seed User Favorites
        $insertFavStmt = $pdo->prepare(
            'INSERT INTO user_favorites (id, user_id, event_id, created_at) VALUES (:id, :uid, :eid, :created_at)'
        );
        foreach ($seedFavorites as $fav) {
            $insertFavStmt->execute([
                ':id' => $fav['id'],
                ':uid' => $fav['userId'],
                ':eid' => $fav['eventId'],
                ':created_at' => $now,
            ]);
        }
        $pdo->exec('SELECT setval(\'user_favorites_id_seq\', (SELECT MAX(id) FROM user_favorites));');

        $pdo->commit();
    } else {
        echo "Target Mode: \033[1;33mJSON File Store (PostgreSQL offline)\033[0m\n\n";

        // 1. Seed Users JSON Store
        $usersFormatted = [];
        foreach ($seedUsers as $u) {
            $usersFormatted[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => strtolower($u['email']),
                'password_hash' => password_hash($u['password'], PASSWORD_BCRYPT),
                'role' => $u['role'],
                'createdAt' => $now,
                'updatedAt' => $now,
            ];
        }
        writeUsersJsonStore($usersFormatted);

        // 2. Seed Events JSON Store
        $eventsFormatted = [];
        $totalSessions = 0;
        $totalImages = 0;
        foreach ($seedEvents as $ev) {
            $formattedSessions = [];
            foreach ($ev['sessions'] as $s) {
                $formattedSessions[] = [
                    'id' => $s['id'],
                    'eventId' => $ev['id'],
                    'sessionId' => $s['sessionId'],
                    'name' => $s['name'],
                    'time' => $s['time'],
                    'type' => $s['type'],
                    'speaker' => $s['speaker'],
                    'room' => $s['room'],
                    'description' => $s['description'],
                ];
                $totalSessions++;
            }

            $formattedImages = [];
            foreach ($ev['images'] as $img) {
                $formattedImages[] = [
                    'id' => $img['id'],
                    'eventId' => $ev['id'],
                    'imageUrl' => $img['imageUrl'],
                    'imageData' => $img['imageData'],
                    'caption' => $img['caption'],
                ];
                $totalImages++;
            }

            $eventsFormatted[] = [
                'id' => $ev['id'],
                'clientId' => $ev['clientId'],
                'name' => $ev['name'],
                'date' => $ev['date'],
                'location' => $ev['location'],
                'organiser' => $ev['organiser'],
                'type' => $ev['type'],
                'status' => $ev['status'],
                'description' => $ev['description'],
                'contactEmail' => $ev['contactEmail'],
                'contactPhone' => $ev['contactPhone'],
                'createdAt' => $now,
                'updatedAt' => $now,
                'sessions' => $formattedSessions,
                'images' => $formattedImages,
            ];
        }
        writeJsonStore($eventsFormatted);

        // 3. Seed Favorites JSON Store
        $favoritesFormatted = [];
        foreach ($seedFavorites as $fav) {
            $favoritesFormatted[] = [
                'id' => $fav['id'],
                'userId' => $fav['userId'],
                'eventId' => $fav['eventId'],
                'createdAt' => $now,
            ];
        }
        writeFavoritesJsonStore($favoritesFormatted);
    }

    echo "\033[32m  [✓] Seeded " . count($seedUsers) . " Users\033[0m\n";
    echo "\033[32m  [✓] Seeded " . count($seedEvents) . " Events (Myanmar Scoped & Diverse)\033[0m\n";
    echo "\033[32m  [✓] Seeded " . $totalSessions . " Sessions\033[0m\n";
    echo "\033[32m  [✓] Seeded " . $totalImages . " Event Photos\033[0m\n";
    echo "\033[32m  [✓] Seeded " . count($seedFavorites) . " User Favorites\033[0m\n\n";

    echo "\033[1;37mReady-to-use Demo Accounts:\033[0m\n";
    echo "  • \033[1;36mAdmin Account\033[0m: admin@eventmanager.com  |  password: admin123  (Role: ADMIN)\n";
    echo "  • \033[1;35mUser Account\033[0m:  user@eventmanager.com   |  password: user123   (Role: USER)\n";
    echo "  • \033[1;35mLocal User\033[0m:    thura@eventmanager.mm  |  password: thura123  (Role: USER)\n\n";
    echo "\033[1;32mDatabase seeding completed successfully!\033[0m\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "\033[31m[ERROR] Seeding failed: " . $e->getMessage() . "\033[0m\n");
    exit(1);
}
