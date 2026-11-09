-- Database Migration 001: Create events and sessions tables
-- Target Database: PostgreSQL

CREATE TABLE IF NOT EXISTS events (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NULL,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    organiser VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'UPCOMING',
    description TEXT NULL,
    contact_email VARCHAR(255) NULL,
    contact_phone VARCHAR(50) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_events_name_date UNIQUE (name, date)
);

CREATE TABLE IF NOT EXISTS sessions (
    id BIGSERIAL PRIMARY KEY,
    event_id BIGINT NOT NULL,
    session_id VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    time VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    speaker VARCHAR(255) NULL,
    room VARCHAR(100) NULL,
    description TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_event FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_sessions_event_id ON sessions(event_id);
CREATE INDEX IF NOT EXISTS idx_events_date ON events(date);
