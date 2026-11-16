# AGENTS.md — Agentic Development Guide for Event Manager Backend

This guide provides critical context, architecture patterns, coding conventions, project structure, and instructions for AI agents and developers working on **Event Manager Backend**.

---

## 1. Quick Overview

- **App Purpose**: Minimal REST API service providing cloud storage, synchronization, and retrieval for the Event Manager ecosystem (events with nested sessions).
- **Tech Stack**: Pure PHP 8.3+, zero external runtime dependencies, PostgreSQL with native PDO.
- **Architecture**: Clean 2-File Minimal Setup:
  - `public/index.php`: Request routing, payload validation, and HTTP/JSON responses.
  - `src/db.php`: Database connection (`.env` loading, PDO) and PostgreSQL transaction/query helpers.
- **Port / Server**: `127.0.0.1:8080` (or `0.0.0.0:8080`).

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
│   └── index.php                                     # HTTP entry point, router, and validation
│
├── scripts/
│   ├── migrate.php                                   # Migration execution CLI script
│   └── test-db.php                                   # Connectivity diagnostic script
│
├── src/
│   └── db.php                                        # Database connection provider and query functions
│
├── docs/                                             # Modular documentation & logs
│   ├── README.md
│   ├── ARCHITECTURE.md
│   └── features/
│
├── .env                                              # Environment variables (DB credentials)
├── .env.example                                      # Environment template
└── composer.json                                     # Command shortcuts (start, lint, migrate)
```

---

## 3. Architecture & Data Flow

```text
[ Client (Android Mobile App) ]
               │
               │ HTTP POST /api/events/upload (application/json)
               ▼
      [ public/index.php ]
               │
               ├──> validateUploadPayload() (Fails -> 400 / 422 JSON)
               │
               ▼ (Passes)
        [ src/db.php ]
               │
               ▼
        PDO Transaction (BEGIN / COMMIT / ROLLBACK)
               ├── Upsert events table
               ├── Cascade replace sessions table
               └── Return synced counts
```

---

## 4. API Specification & Endpoints

| Method | Endpoint | Description | Request Body | Response Codes |
| :---: | :--- | :--- | :--- | :---: |
| `GET` | `/` | Root API greeting | None | 200 |
| `GET` | `/api/health` | Service and database health check | None | 200 / 503 |
| `POST` | `/api/events/upload` | Atomic bulk upload of events & sessions | Bulk JSON Payload | 200 / 400 / 422 / 500 |
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
2. **Zero Runtime Dependencies**: Keep the backend pure PHP without bloated frameworks.
3. **Validation First**: Always validate incoming request payloads before invoking database operations.
4. **Transactions for Mutations**: All multi-step database mutations must use `beginTransaction()`, `commit()`, and `rollBack()` inside `try ... catch (Throwable $e)`.
5. **Prepared Statements**: Always use PDO prepared statements with bound parameters to prevent SQL injection.

---

## 6. Common Commands (Makefile / CLI)

- **Start Local Server**:
  ```bash
  make run
  # or: make run PORT=8080
  ```
- **Run Database Migrations**:
  ```bash
  make migrate
  ```
- **Check Database Connectivity**:
  ```bash
  make test-db
  ```
- **Lint All PHP Files**:
  ```bash
  make lint
  ```
- **Show All Makefile Targets**:
  ```bash
  make help
  ```
