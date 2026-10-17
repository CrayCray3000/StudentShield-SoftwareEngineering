-- Run ONCE in phpMyAdmin BEFORE deploying the role-separation application files.
-- Existing enabled students remain approved; existing inactive students become pending.
-- Existing staff access is preserved until explicitly disabled through Account Management.
ALTER TABLE users
    ADD COLUMN registration_status VARCHAR(12) NOT NULL DEFAULT 'approved',
    ADD COLUMN access_disabled TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN auth_version INT NOT NULL DEFAULT 1;
UPDATE users SET registration_status = 'pending' WHERE role = 0 AND is_active = 0;
-- Principal is role=3 and uses the existing admins table for staff OTP contact information.
-- No case, vent, note, contact, or audit records are deleted by this upgrade.
