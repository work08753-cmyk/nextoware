-- Add work_address to bookings table
ALTER TABLE bookings ADD COLUMN work_address TEXT AFTER time_slot;
