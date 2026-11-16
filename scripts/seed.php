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
];

$seedEvents = [
    [
        'id' => 1,
        'clientId' => 1,
        'name' => 'Google I/O Extended 2026',
        'date' => '2026-11-20',
        'location' => 'Tech Park Auditorium, Hall A',
        'organiser' => 'Google Developer Group',
        'type' => 'CONFERENCE',
        'status' => 'UPCOMING',
        'description' => 'Annual community-led developer conference covering modern Android, Jetpack Compose, Gemini AI, and Cloud Architecture.',
        'contactEmail' => 'gdg-events@techhub.org',
        'contactPhone' => '+1 555-0199',
        'sessions' => [
            [
                'id' => 1,
                'sessionId' => 'SES-01',
                'name' => 'Keynote: Next-Gen AI & Mobile',
                'time' => '09:00 - 10:15',
                'type' => 'KEYNOTE',
                'speaker' => 'Dr. Sarah Lin (Google)',
                'room' => 'Main Hall',
                'description' => 'Opening keynote on generative AI models running directly on device.',
            ],
            [
                'id' => 2,
                'sessionId' => 'SES-02',
                'name' => 'Hands-on Kotlin Multiplatform',
                'time' => '10:30 - 12:00',
                'type' => 'WORKSHOP',
                'speaker' => 'Marcus Vance',
                'room' => 'Lab 3',
                'description' => 'Building shared business logic across Android and iOS.',
            ],
            [
                'id' => 3,
                'sessionId' => 'SES-03',
                'name' => 'Networking & Partner Showcase',
                'time' => '12:00 - 13:30',
                'type' => 'NETWORKING',
                'speaker' => null,
                'room' => 'Atrium',
                'description' => 'Lunch, project demonstrations, and partner career fair.',
            ],
        ],
        'images' => [
            [
                'id' => 1,
                'imageUrl' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Main Auditorium Keynote Stage',
            ],
        ],
    ],
    [
        'id' => 2,
        'clientId' => 2,
        'name' => 'KotlinConf Global Summit',
        'date' => '2026-11-25',
        'location' => 'Amsterdam Convention Hall',
        'organiser' => 'JetBrains Community',
        'type' => 'CONFERENCE',
        'status' => 'UPCOMING',
        'description' => 'The premier conference for developers building with Kotlin on Android, server-side, and cross-platform stacks.',
        'contactEmail' => 'contact@kotlinconf.com',
        'contactPhone' => '+31 20 555 1234',
        'sessions' => [
            [
                'id' => 4,
                'sessionId' => 'SES-10',
                'name' => 'Deep Dive: Compose Multiplatform 2.0',
                'time' => '10:00 - 11:00',
                'type' => 'PRESENTATION',
                'speaker' => 'Elena Rostova',
                'room' => 'Track 1',
                'description' => 'Architecting high-performance UI across desktop, web, and mobile.',
            ],
            [
                'id' => 5,
                'sessionId' => 'SES-11',
                'name' => 'Coroutines Internals and Best Practices',
                'time' => '11:15 - 12:30',
                'type' => 'WORKSHOP',
                'speaker' => 'Roman Elizarov',
                'room' => 'Workshop Studio',
                'description' => 'Structuring asynchronous pipelines and error propagation.',
            ],
        ],
        'images' => [
            [
                'id' => 2,
                'imageUrl' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Live Developer Keynote & Q&A',
            ],
        ],
    ],
    [
        'id' => 3,
        'clientId' => 3,
        'name' => 'Advanced React Native Masterclass',
        'date' => '2026-12-02',
        'location' => 'Design & Code Lab, Room 402',
        'organiser' => 'Mobile Creators Guild',
        'type' => 'WORKSHOP',
        'status' => 'UPCOMING',
        'description' => 'Intensive hands-on workshop covering the New Architecture, Expo Router SDK 58, and native modules integration.',
        'contactEmail' => 'workshops@mobileguild.dev',
        'contactPhone' => '+1 555-0422',
        'sessions' => [
            [
                'id' => 6,
                'sessionId' => 'SES-20',
                'name' => 'JSI and TurboModules from Scratch',
                'time' => '13:00 - 15:00',
                'type' => 'WORKSHOP',
                'speaker' => 'David Kim',
                'room' => 'Room 402',
                'description' => 'Direct C++ communication without serializing JSON over the bridge.',
            ],
        ],
        'images' => [
            [
                'id' => 3,
                'imageUrl' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Hands-on Coding & Architecture Review',
            ],
        ],
    ],
    [
        'id' => 4,
        'clientId' => 4,
        'name' => 'Tech Community Mixer & Pitch Night',
        'date' => '2026-11-28',
        'location' => 'Atrium Lounge, Silicon Hub',
        'organiser' => 'Founders & Builders Club',
        'type' => 'SOCIAL',
        'status' => 'UPCOMING',
        'description' => 'Casual evening connecting founders, mobile developers, and designers. 5 fast pitches followed by refreshments and open networking.',
        'contactEmail' => 'mixer@foundersclub.io',
        'contactPhone' => '+1 555-0811',
        'sessions' => [
            [
                'id' => 7,
                'sessionId' => 'SES-30',
                'name' => 'Lightning Startup Pitches',
                'time' => '18:00 - 19:00',
                'type' => 'PRESENTATION',
                'speaker' => 'Community Founders',
                'room' => 'Main Stage',
                'description' => 'Five 5-minute pitches followed by feedback.',
            ],
            [
                'id' => 8,
                'sessionId' => 'SES-31',
                'name' => 'Open Social & Networking',
                'time' => '19:00 - 21:00',
                'type' => 'NETWORKING',
                'speaker' => null,
                'room' => 'Atrium Lounge',
                'description' => 'Drinks, appetizers, and collaborative discussion.',
            ],
        ],
        'images' => [
            [
                'id' => 4,
                'imageUrl' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'Social Gathering & Pitch Lounge',
            ],
        ],
    ],
    [
        'id' => 5,
        'clientId' => 5,
        'name' => 'Cybersecurity In Cloud Operations',
        'date' => '2026-10-15',
        'location' => 'Metropolitan Center',
        'organiser' => 'InfoSec Alliance',
        'type' => 'SEMINAR',
        'status' => 'COMPLETED',
        'description' => 'Executive seminar examining zero-trust security postures and compliance for enterprise workloads.',
        'contactEmail' => 'info@infosecalliance.org',
        'contactPhone' => null,
        'sessions' => [],
        'images' => [
            [
                'id' => 5,
                'imageUrl' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&auto=format&fit=crop&q=80',
                'imageData' => null,
                'caption' => 'SecOps Conference Room',
            ],
        ],
    ],
];

$seedFavorites = [
    ['id' => 1, 'userId' => 2, 'eventId' => 1],
    ['id' => 2, 'userId' => 2, 'eventId' => 4],
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
    echo "\033[32m  [✓] Seeded " . count($seedEvents) . " Events\033[0m\n";
    echo "\033[32m  [✓] Seeded " . $totalSessions . " Sessions\033[0m\n";
    echo "\033[32m  [✓] Seeded " . $totalImages . " Event Photos\033[0m\n";
    echo "\033[32m  [✓] Seeded " . count($seedFavorites) . " User Favorites\033[0m\n\n";

    echo "\033[1;37mReady-to-use Demo Accounts:\033[0m\n";
    echo "  • \033[1;36mAdmin Account\033[0m: admin@eventmanager.com  |  password: admin123  (Role: ADMIN)\n";
    echo "  • \033[1;35mUser Account\033[0m:  user@eventmanager.com   |  password: user123   (Role: USER)\n\n";
    echo "\033[1;32mDatabase seeding completed successfully!\033[0m\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "\033[31m[ERROR] Seeding failed: " . $e->getMessage() . "\033[0m\n");
    exit(1);
}
