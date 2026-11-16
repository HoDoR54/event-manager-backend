-- Database Migration 002: Create event_images table
-- Target Database: PostgreSQL

CREATE TABLE IF NOT EXISTS event_images (
    id BIGSERIAL PRIMARY KEY,
    event_id BIGINT NOT NULL,
    image_url TEXT NULL,
    image_data TEXT NULL,
    caption VARCHAR(255) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_event_images_event FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_event_images_event_id ON event_images(event_id);
