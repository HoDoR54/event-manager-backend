# Feature: Cloud Upload API & Event Storage — Plan

## 1. Overview & Requirements
- Provide REST endpoints for bulk uploading events and sessions from the Event Manager Android application.
- Guarantee atomic persistence (all-or-nothing per sync) within database transactions.
- Idempotently handle uploads: re-uploading an event updates fields and synchronizes sessions without duplicate key errors.
- Support querying stored events with nested sessions and deleting events.

---

## 2. API Contract
- `POST /api/events/upload`: Accepts `UploadPayloadDto` JSON body. Returns 200 on success with count metrics, 400 on malformed JSON, 422 on invalid schema.
- `GET /api/events`: Returns all stored events with nested sessions.
- `GET /api/events/{id}`: Returns single event with nested sessions.
- `DELETE /api/events/{id}`: Deletes event and cascades to sessions.

---

## 3. Database Schema
- `events` table: `id`, `client_id`, `name`, `date`, `location`, `organiser`, `type`, `status`, `description`, `contact_email`, `contact_phone`, `created_at`, `updated_at`. Unique on `(name, date)`.
- `sessions` table: `id`, `event_id`, `session_id`, `name`, `time`, `type`, `speaker`, `room`, `description`, `created_at`, `updated_at`. Foreign key references `events(id)` on delete cascade.

---

## 4. Testing Strategy
- Unit tests for `UploadPayloadValidator`: verify validation passes for valid payloads and catches missing/malformed fields.
- Unit tests for `EventDto` / `UploadPayloadDto`: verify serialization and array mappings.
- Unit tests for `EventService`: verify repository coordination using mocks.
- Integration tests for `EventController`: simulate HTTP requests, test status codes (200, 400, 422).
