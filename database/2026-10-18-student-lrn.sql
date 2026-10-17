-- Run once in phpMyAdmin before deploying the LRN registration update.
-- Existing students keep NULL until their LRN is supplied.
ALTER TABLE students ADD COLUMN lrn VARCHAR(12) NULL, ADD UNIQUE KEY students_lrn_unique (lrn);
