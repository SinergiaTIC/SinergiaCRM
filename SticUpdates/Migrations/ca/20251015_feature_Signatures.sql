INSERT INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
     ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-10-06 13:51:23','2025-10-15 13:03:19','2','2','off','Solicitud firma',NULL,'[Sol·licitud de signatura del document] $stic_signatures_pdf_template','Hola $contact_first_name$contact_user_first_name,

S\'ha sol·licitat la teva signatura per al següent document:

Nom del Document: $stic_signatures_pdf_template
Data de Caducitat: $stic_signatures_expiration_date
Signant: $contact_full_name$contact_user_full_name
Correu electrònic de contacte: $contact_email1$contact_user_email1

Per signar el document, fes clic al següent enllaç. Seràs redirigit al portal de signatura segura.

Signar Document: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Si tens problemes per accedir, per favor, copia i enganxa aquest enllaç al teu navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Aquest és un correu automàtic, per favor no responguis a aquest missatge.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Signatura de Document</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Hola <strong>$contact_first_name$contact_user_first_name</strong>,</p>
<p style="font-size: 16px; color: #555555;">S\'ha sol·licitat la teva signatura per al següent document:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>Nom del Document: $stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de Caducitat:</strong> $stic_signatures_expiration_date</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Signant: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Correu electrònic de contacte: </strong>$contact_email1$contact_user_email1</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Per signar el document, fes clic al següent enllaç. Seràs redirigit al portal de signatura segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Signar Document</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Si tens problemes per accedir, per favor, copia i enganxa aquest enllaç al teu navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
<div style="background-color: #f0f0f0; color: #aaaaaa; text-align: center; padding: 15px; font-size: 12px;">
<p style="margin: 0;">Aquest és un correu automàtic, per favor no responguis a aquest missatge.</p>
</div>
</div>
</body>
</html>',0,'2',0,'notification');