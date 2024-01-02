-- Active: 1632214630318@@localhost@2002@sinergiacrm
-- Actualiza los campos expected_payments_detail y pending_annualized_fee en todos los compromisos de pago activos
UPDATE stic_payment_commitments AS t1
JOIN (
    SELECT 
        id,
        GROUP_CONCAT( 
            CASE
                WHEN month_counter < DATE_FORMAT(first_payment_date, '%Y%m') THEN 0
                WHEN month_counter > DATE_FORMAT(end_date, '%Y%m') AND STR_TO_DATE(end_date, '%Y-%m-%d') IS NOT NULL THEN 0
                WHEN (DATE_FORMAT(first_payment_date, '%Y%m') = month_counter) OR
                     (periodicity = 'monthly') OR
                     (periodicity = 'bimonthly' AND SUBSTRING(month_counter, 5) % 2 = MONTH(first_payment_date) % 2) OR
                     (periodicity = 'quarterly' AND SUBSTRING(month_counter, 5) % 3 = MONTH(first_payment_date) % 3) OR
                     (periodicity = 'four_monthly' AND SUBSTRING(month_counter, 5) % 4 = MONTH(first_payment_date) % 4) OR
                     (periodicity = 'half_yearly' AND SUBSTRING(month_counter, 5) % 6 = MONTH(first_payment_date) % 6) OR
                     (periodicity = 'annual' AND SUBSTRING(month_counter, 5) % 12 = MONTH(first_payment_date) % 12)
                THEN amount
                ELSE 0
            END ORDER BY month_counter SEPARATOR '|' 
        ) AS projection,
        SUM(
            CASE
                WHEN SUBSTRING(month_counter, 1, 4) = YEAR(NOW()) AND month_counter >= DATE_FORMAT(NOW(), '%Y%m') THEN 
                    CASE
                        WHEN month_counter > DATE_FORMAT(end_date, '%Y%m') AND STR_TO_DATE(end_date, '%Y-%m-%d') IS NOT NULL THEN 0
                        WHEN (DATE_FORMAT(first_payment_date, '%Y%m') = month_counter) OR
                             (periodicity = 'monthly') OR
                             (periodicity = 'bimonthly' AND SUBSTRING(month_counter, 5) % 2 = MONTH(first_payment_date) % 2) OR
                             (periodicity = 'quarterly' AND SUBSTRING(month_counter, 5) % 3 = MONTH(first_payment_date) % 3) OR
                             (periodicity = 'four_monthly' AND SUBSTRING(month_counter, 5) % 4 = MONTH(first_payment_date) % 4) OR
                             (periodicity = 'half_yearly' AND SUBSTRING(month_counter, 5) % 6 = MONTH(first_payment_date) % 6) OR
                             (periodicity = 'annual' AND SUBSTRING(month_counter, 5) % 12 = MONTH(first_payment_date) % 12)
                        THEN amount
                        ELSE 0
                    END
                ELSE 0
            END
        ) AS this_year_projection
    FROM 
        stic_payment_commitments
    CROSS JOIN 
        (SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 MONTH), '%Y%m') AS month_counter UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 3 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 4 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 5 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 6 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 8 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 9 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 10 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 11 MONTH), '%Y%m') UNION ALL
         SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 12 MONTH), '%Y%m')) as months
    WHERE 
        active = 1 
        AND deleted = 0
        AND periodicity != 'punctual'
    GROUP BY 
        id
) AS t2 ON t1.id = t2.id
SET t1.expected_payments_detail = t2.projection,
    t1.pending_annualized_fee = t2.this_year_projection;


-- Recalcula el valor del campo _paid_annualized_fee_ para todos los CdP activos.
UPDATE stic_payment_commitments pc
            JOIN (
            SELECT rel.stic_paymebfe2itments_ida, SUM(sp.amount) AS total
                FROM stic_payments sp
                JOIN stic_payments_stic_payment_commitments_c rel
                    ON rel.stic_payments_stic_payment_commitmentsstic_payments_idb = sp.id
                WHERE sp.status = 'paid'
                    AND sp.deleted = 0
                    AND rel.deleted = 0
                    AND YEAR(sp.payment_date) = YEAR(CURDATE())
                    AND rel.stic_paymebfe2itments_ida IN (SELECT id FROM stic_payment_commitments where active=1 AND deleted=0)
                GROUP BY rel.stic_paymebfe2itments_ida
            ) pay_sum
            ON pc.id = pay_sum.stic_paymebfe2itments_ida
            SET pc.date_modified = DATE_FORMAT(UTC_TIMESTAMP(), '%Y-%m-%d %H:%i:%s'),
                pc.paid_annualized_fee = pay_sum.total
            WHERE pc.id IN (SELECT id FROM stic_payment_commitments where active=1 AND deleted=0 ) ;
