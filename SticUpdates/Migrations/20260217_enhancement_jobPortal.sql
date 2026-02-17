-- Create new fields in Accounts and Contacts in field_meta_data table
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Accountsstic_pa_password_c', 'Accounts', 'stic_pa_password_c'),
('Accountsstic_pa_username_c', 'Accounts', 'stic_pa_username_c'),
('Accountsstic_pa_enable_c', 'Accounts', 'stic_pa_enable_c'),
('Contactsstic_pa_password_c', 'Contacts', 'stic_pa_password_c'),
('Contactsstic_pa_username_c', 'Contacts', 'stic_pa_username_c'),
('Contactsstic_pa_enable_c', 'Contacts', 'stic_pa_enable_c');

-- Job Offers application counters new fields
ALTER TABLE `stic_job_offers`
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_total` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_expected_presentation` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_presented` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_pending_interview` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_interviewed` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_accepted` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_rejected_closed` INT(11) DEFAULT 0 NULL,
	ADD COLUMN IF NOT EXISTS `stic_job_applications_count_review` INT(11) DEFAULT 0 NULL;

-- Recalculate Job application counts for existing offers
UPDATE `stic_job_offers` o
LEFT JOIN (
	SELECT
		rel.stic_job_applications_stic_job_offersstic_job_offers_ida AS offer_id,
		COUNT(*) AS total,
		SUM(CASE WHEN ja.status = 'expected_presentation' THEN 1 ELSE 0 END) AS expected_presentation,
		SUM(CASE WHEN ja.status = 'presented' THEN 1 ELSE 0 END) AS presented,
		SUM(CASE WHEN ja.status = 'pending_interview' THEN 1 ELSE 0 END) AS pending_interview,
		SUM(CASE WHEN ja.status = 'interviewed' THEN 1 ELSE 0 END) AS interviewed,
		SUM(CASE WHEN ja.status = 'accepted' THEN 1 ELSE 0 END) AS accepted,
		SUM(CASE WHEN ja.status = 'rejected_closed' THEN 1 ELSE 0 END) AS rejected_closed,
		SUM(CASE WHEN ja.status = 'review' THEN 1 ELSE 0 END) AS review
	FROM stic_job_applications_stic_job_offers_c rel
	INNER JOIN stic_job_applications ja
		ON rel.stic_job_applications_stic_job_offersstic_job_applications_idb = ja.id
	WHERE rel.deleted = 0
	  AND ja.deleted = 0
	GROUP BY rel.stic_job_applications_stic_job_offersstic_job_offers_ida
) c ON c.offer_id = o.id
SET
	o.stic_job_applications_count_total = COALESCE(c.total, 0),
	o.stic_job_applications_count_expected_presentation = COALESCE(c.expected_presentation, 0),
	o.stic_job_applications_count_presented = COALESCE(c.presented, 0),
	o.stic_job_applications_count_pending_interview = COALESCE(c.pending_interview, 0),
	o.stic_job_applications_count_interviewed = COALESCE(c.interviewed, 0),
	o.stic_job_applications_count_accepted = COALESCE(c.accepted, 0),
	o.stic_job_applications_count_rejected_closed = COALESCE(c.rejected_closed, 0),
	o.stic_job_applications_count_review = COALESCE(c.review, 0)
WHERE o.deleted = 0;