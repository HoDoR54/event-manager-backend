# Feature: Cloud Upload API & Event Storage — Post-Implementation Notes

## 1. Key Architectural Decisions
1. **Idempotent Sync Mechanism**:
   - Re-uploading an existing event (`name` + `date`) performs an update on the `events` table and refreshes its associated `sessions` inside a database transaction (`BEGIN ... COMMIT`).
   - This prevents duplicate key constraint violations and ensures synchronization is reliable over unstable networks.
2. **Offline-Verifiable Testing Strategy**:
   - The test suite uses PHPUnit test doubles and mocks for the repository interface, allowing full CI/CD and agentic test execution without needing an active PostgreSQL Docker container.
3. **Decoupled Route Instantiation**:
   - Controller resolution in `src/Routes/EventRoute.php` is deferred to route execution, preventing runtime database connection attempts during routing configuration.

---

## 2. Verification Checklist
- [x] All 16 PHPUnit tests passing without notices or warnings.
- [x] Linting confirms zero syntax errors across `src/`, `public/`, and `tests/`.
- [x] Schema script `001_create_events_and_sessions_tables.sql` matches Android domain models.
- [x] `scripts/migrate.php` provides one-command execution once PostgreSQL is reachable.
