# Event Manager Backend — Documentation Hub

Welcome to the **Event Manager Backend** technical documentation. This documentation mirrors the modular engineering and agentic lifecycle established across the Event Manager project ecosystem.

---

## 1. Documentation Structure

```text
docs/
├── README.md                                  # Central documentation hub
└── features/
    └── feature-cloud-upload-api/              # Cloud Upload API & Event Storage
        ├── plan.md                            # Endpoints, schema design, and contracts
        ├── log.md                             # Chronological development activity log
        └── notes.md                           # Architectural decisions & verification notes
```

---

## 2. Feature Status

| Feature | Description | Status | Documents |
| :--- | :--- | :---: | :--- |
| **Cloud Upload API & Persistence** | Bulk sync endpoint, event/session models, validation, and PostgreSQL storage | ✅ Complete | [`docs/features/feature-cloud-upload-api/`](features/feature-cloud-upload-api/plan.md) |
