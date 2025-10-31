REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
     ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-10-06 13:51:23','2025-10-15 13:03:19','2','2','off','Solicitud firma',NULL,'[Dokumentua sinatzeko eskaera] $stic_signatures_pdf_template','Kaixo $contact_first_name$contact_user_first_name,

Zure sinadura eskatu da hurrengo dokumenturako:

Dokumentuaren izena: $stic_signatures_pdf_template
Iraungitze data: $stic_signatures_expiration_date
Sinatzailea: $contact_full_name$contact_user_full_name
Harremanetarako helbide elektronikoa: $contact_email1$contact_user_email1

Dokumentua sinatzeko, egin klik beheko estekan. Sinadura-atari segurura birbideratuko zara.

Sinatu dokumentua: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Sartzeko arazoak badituzu, kopiatu eta itsatsi esteka hau zure nabigatzailean:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Mezu automatikoa da, mesedez ez erantzun mezu honi.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Dokumentua sinatzea</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Kaixo <strong>$contact_first_name$contact_user_first_name</strong>,</p>
<p style="font-size: 16px; color: #555555;">Zure sinadura eskatu da hurrengo dokumenturako:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>Dokumentuaren izena: $stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Iraungitze data:</strong> $stic_signatures_expiration_date</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Sinatzailea: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Harremanetarako helbide elektronikoa: </strong>$contact_email1$contact_user_email1</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Dokumentua sinatzeko, egin klik beheko estekan. Sinadura-atari segurura birbideratuko zara.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Sinatu dokumentua</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Sartzeko arazoak badituzu, kopiatu eta itsatsi esteka hau zure nabigatzailean: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
<div style="background-color: #f0f0f0; color: #aaaaaa; text-align: center; padding: 15px; font-size: 12px;">
<p style="margin: 0;">Mezu automatikoa da, mesedez ez erantzun mezu honi.</p>
</div>
</div>
</body>
</html>',0,'2',0,'notification');