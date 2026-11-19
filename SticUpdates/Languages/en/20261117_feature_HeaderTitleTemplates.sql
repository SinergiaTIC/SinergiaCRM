-- Example header title template for the Contacts module.
-- Assign it in Administration > Record header titles.
-- $stic_contact_photo resolves to the record photo, or a fake-face avatar if none.
REPLACE INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`) VALUES
(
 '000005f1-2e4e-3b11-051f-68e3c9e70340', NOW(), NOW(), '1', '1', 'on',
 'Example - Contacts - Header title',
 'Example header title for Contacts: photo, full name, email, phone and city. $stic_contact_photo shows the record photo or a fake-face avatar when empty.',
 '',
 '',
 '<div style="display:flex;align-items:center;gap:10px;line-height:1.35;">$stic_contact_photo<div><div><strong>$contact_full_name</strong></div><div style="color:#555;font-size:0.85em;">$contact_email1</div><div style="color:#555;font-size:0.85em;">$contact_phone_mobile</div><div style="color:#555;font-size:0.85em;">$contact_primary_address_city</div></div></div>',
 0, '1', 0, 'header_title'
);
