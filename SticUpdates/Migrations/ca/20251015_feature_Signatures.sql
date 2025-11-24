REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
      ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Exemple - Sol·licitud de firma','Plantilla per notificar al firmant que s''ha sol·licitat la seva firma en un document.','[Firma de document] Sol·licitud de firma: $stic_signatures_pdf_template','Sol·licitud de firma

S''ha sol·licitat la vostra firma al següent document:

$stic_signatures_pdf_template

Firmant: $contact_full_name$contact_user_full_name
Data de caducitat: $stic_signatures_expiration_date

Per firmar el document, feu clic a continuació i se us redirigirà al portal de firma segura.

Firmar el document: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Si no podeu accedir al portal de firma, copieu i enganxeu l''enllaç al vostre navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Firma de document</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">S''ha sol·licitat la vostra firma al següent document:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmant:</strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de caducitat:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Per firmar el document, feu clic a continuació i se us redirigirà al portal de firma segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Signar el document</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Si no podeu accedir al portal de firma, copieu i enganxeu l''enllaç al vostre navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Exemple - Document firmat','Plantilla per enviar al firmant el document firmat.','[Firma de document] Document firmat: $stic_signatures_pdf_template','Document firmat

Adjunt a aquest correu trobareu la versió final del document que heu firmat:

$stic_signatures_pdf_template

Firmant: $contact_full_name$contact_user_full_name
Data de la firma: $stic_signers_signature_date

Al portal de firma podreu accedir al document firmat sempre que sigui necessari.

Ves al portal de firma: $sugarurl/index.php?entryPoint=sticSign&signerId=$stic_signers_id','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Document firmat</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Adjunt a aquest correu trobareu la versió final del document que heu firmat:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmant:</strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de la firma:</strong> $stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Al portal de firma podreu accedir al document firmat sempre que sigui necessari.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Ves al portal de firma</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','Exemple - Codi d''un sol ús per firmar','Plantilla per enviar el codi d''un sol ús al firmant abans de poder firmar.','[Firma de document] Codi de verificació per firmar','Verificació de firma

Per garantir la seguretat i autenticitat de la vostra firma, feu servir el codi de verificació que es mostra a continuació:

$stic_signatures_pdf_template

$stic_signers_otp

Introduïu aquest codi al portal de firma per continuar el procés. El codi és vàlid durant 10 minuts i només es pot fer servir una vegada.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Verificació de firma</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Per garantir la seguretat i autenticitat de la vostra signatura, feu servir el codi de verificació que es mostra a continuació:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Introduïu aquest codi al portal de firma per continuar el procés. El codi és vàlid durant 10 minuts i només es pot fer servir una vegada.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','Exemple - Codi d''un sol ús per firmar (SMS)','Plantilla per enviar via SMS el codi d''un sol ús al firmant abans de poder firmar.','[Firma de document] Codi de verificació per firmar'','$contact_first_name$contact_user_first_name, aquest és el vostre codi per firmar: $stic_signers_otp. És vàlid durant 10 minuts.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');
