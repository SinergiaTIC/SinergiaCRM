-- Number of attendances aggregated in aggregated services payments
ALTER TABLE `stic_payments` 
	ADD COLUMN `attendances_count` INT(11) DEFAULT 0 NULL;

-- Recalculate attendances count for existing aggregated services payments
UPDATE `stic_payments` p
LEFT JOIN (
	SELECT
		rel.stic_payments_stic_attendancesstic_payments_ida AS payment_id,
		COUNT(*) AS total
	FROM stic_payments_stic_attendances_c rel
	INNER JOIN stic_attendances a
		ON rel.stic_payments_stic_attendancesstic_attendances_idb = a.id
	WHERE rel.deleted = 0
	  AND a.deleted = 0
	GROUP BY rel.stic_payments_stic_attendancesstic_payments_ida
) c ON c.payment_id = p.id
SET
	p.attendances_count = COALESCE(c.total, 0)
WHERE p.payment_type = 'aggregated_services'
  AND p.deleted = 0;