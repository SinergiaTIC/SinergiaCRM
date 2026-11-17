-- Example header title template for the Contacts module.
-- Assign it in Administration > Record header titles.
REPLACE INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`) VALUES
(
 '000005f1-2e4e-3b11-051f-68e3c9e70340', NOW(), NOW(), '1', '1', 'on',
 'Example - Contacts - Header title',
 'Example header title for Contacts: full name, email, phone and city.',
 '',
 '',
 '<div style="line-height:1.35;"><strong>$contact_full_name</strong><br><span style="color:#555;font-size:0.85em;">$contact_email1 &middot; $contact_phone_mobile &middot; $contact_primary_address_city</span></div>',
 0, '1', 0, 'header_title'
);
