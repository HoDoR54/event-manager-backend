# AGENTS.md — Agentic Development Guide for Event Manager Backend

This guide provides critical context, architecture patterns, coding conventions, project structure, and instructions for AI agents and developers working on **Event Manager Backend**.

---

## 1. Quick Overview

- **App Purpose**: REST API service providing cloud storage, synchronization, and retrieval for the Event Manager ecosystem (events with nested sessions).
- **Tech Stack**: PHP 8.3+, Slim 4 framework, PSR-7 HTTP message interfaces (`slim/psr7`), PHP-DotEnv (`vlucas/phpdotenv`), PostgreSQL with PDO, PHPUnit 12.
- **Architecture**: Clean Architecture / Layered Design: DTOs, Repositories (Interface + PDO implementation), Domain Services, Controllers, Validators, Routes, Migrations.
- **Port / Server**: `127.0.0.1:8080` (or `0.0.0.0:8080` in container / remote).

---

## 2. Project Folder Structure & Organization

```text
event-manager-backend/
│
├── database/
│   └── migrations/
│       └── 001_create_events_and_sessions_tables.sql # Raw idempotent SQL migrations
│
├── public/
│   └── index.php                                     # HTTP entry point (Slim 4 application)
│
├── scripts/
│   ├── migrate.php                                   # Migration execution CLI script
│   └── test-db.php                                   # Connectivity diagnostic script
│
├── src/
│   ├── Config/
│   │   └── Database.php                              # PDO connection provider using .env
│   ├── Controllers/
│   │   ├── HealthController.php                      # Health status and DB ping endpoint
│   │   └── EventController.php                       # Event upload, list, get, and delete actions
│   ├── DTOs/
│   │   ├── SessionDto.php                            # Immutable session data transfer object
│   │   ├── EventDto.php                              # Immutable event with nested sessions DTO
│   │   └── UploadPayloadDto.php                      # Bulk cloud upload payload DTO
│   ├── Repositories/
│   │   ├── EventRepositoryInterface.php              # Storage contract for event operations
│   │   └── PostgresEventRepository.php               # Transactional PostgreSQL implementation
│   ├── Services/
│   │   ├── HealthService.php                         # Service pinging DB status
│   │   └── EventService.php                          # Core business logic coordinating events
│   ├── Validators/
│   │   └── UploadPayloadValidator.php                # Strict payload and field validator
│   ├── Routes/
│   │   ├── api.php                                   # Root greeting endpoint
│   │   ├── HealthRoute.php                           # /api/health endpoint
│   │   └── EventRoute.php                            # /api/events/* endpoints
│   └── routes.php                                    # Master route aggregator
│
├── tests/
│   ├── Unit/
│   │   ├── EventDtoTest.php                          # DTO transformation tests
│   │   ├── EventServiceTest.php                      # Service business logic tests
│   │   └── UploadPayloadValidatorTest.php            # Strict validation rule tests
│   └── Integration/
│       └── EventApiTest.php                          # HTTP request / response simulation tests
│
├── docs/                                             # Modular documentation & logs
│   ├── README.md
│   ├── ARCHITECTURE.md
│   └── features/
│       └── feature-cloud-upload-api/
│           ├── plan.md
│           ├── log.md
│           └── notes.md
│
├── composer.json                                     # Dependencies, scripts, and autoloading
└── phpunit.xml                                       # PHPUnit 12 configuration
```

---

## 3. Architecture & Data Flow

```text
[ Client (Android Mobile App) ]
               │
               │ HTTP POST /api/events/upload (application/json)
               ▼
       [ Slim 4 Router ]
               │
               ▼
    [ EventController ] ────> [ UploadPayloadValidator ]
               │                     │ (Fails -> 400 / 422 JSON)
               │ (Passes)
               ▼
       [ EventService ]
               │
               ▼
 [ EventRepositoryInterface ]
               ▲
               │ (Implements)
 [ PostgresEventRepository ] ──> PDO Transaction (BEGIN / COMMIT / ROLLBACK)
                                 ├── Upsert events table
                                 ├── Cascade replace sessions table
                                 └── Return synced counts
```

---

## 4. API Specification & Endpoints

| Method | Endpoint | Description | Request Body | Response Codes |
| :---: | :--- | :--- | :--- | :---: |
| `GET` | `/api/health` | Service and database health check | None | 200 / 503 |
| `POST` | `/api/events/upload` | Atomic bulk upload of events & sessions | `UploadPayloadDto` (JSON) | 200 / 400 / 422 / 500 |
| `GET` | `/api/events` | Retrieve all events with nested sessions | None | 200 / 500 |
| `GET` | `/api/events/{id}` | Retrieve single event with nested sessions | None | 200 / 404 / 500 |
| `DELETE` | `/api/events/{id}` | Delete single event and cascade delete sessions | None | 200 / 404 / 500 |

### Bulk Upload JSON Format
```json
{
  "uploadTimestamp": "2026-11-09T10:46:36Z",
  "appVersion": "1.0",
  "eventCount": 1,
  "events": [
    {
      "id": 1,
      "name": "Mobile Dev Summit",
      "date": "2026-12-05",
      "location": "Convention Center",
      "organiser": "Tech Group",
      "type": "CONFERENCE",
      "status": "UPCOMING",
      "description": "Annual conference on mobile architectures",
      "contactEmail": "summit@tech.org",
      "contactPhone": "+441234567890",
      "sessions": [
        {
          "sessionId": "S01",
          "name": "Keynote Opening",
          "time": "09:00 - 10:00",
          "type": "KEYNOTE",
          "speaker": "Dr. Smith",
          "room": "Hall A",
          "description": "Opening keynote"
        }
      ]
    }
  ]
}
```

---

## 5. Coding Conventions & Invariants

1. **Strict Types**: Every PHP file must declare `declare(strict_types=1);` at the top.
2. **DTO Immutability**: All DTO properties are `public readonly`.
3. **Validation First**: Always validate incoming request payloads before instantiating domain models or invoking services.
4. **Transactions for Mutations**: All multi-step database mutations must use `beginTransaction()`, `commit()`, and `rollBack()` inside `try ... catch (Throwable $e)`.
5. **Decoupled Architecture**: Controllers never run raw SQL queries; they delegate exclusively to services, which call repository interfaces.
6. **No External Network Dependencies in Unit Tests**: Unit tests must use PHPUnit mocks or test doubles to enable running tests without requiring a live database or Docker.

---

## 6. Common Composer & CLI Commands

- **Run Test Suite**:
  ```bash
  composer test
  ```
- **Lint All PHP Files**:
  ```bash
  composer lint
  ```
- **Run Database Migrations** (when Docker / PostgreSQL is running):
  ```bash
  php scripts/migrate.php
  ```
- **Start Local Development Server**:
  ```bash
  composer start
  ```
