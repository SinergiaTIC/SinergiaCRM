REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
      ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Sol·licitud de signatura','Plantilla usada per notificar al signant que s\'ha sol·licitat la seva signatura electrònica per a un document.','[Sol·licitud de signatura del document] $stic_signatures_pdf_template','Hola $contact_first_name$contact_user_first_name,

S\'ha sol·licitat la vostra signatura per al següent document:

Nom del Document: $stic_signatures_pdf_template
Data de Caducitat: $stic_signatures_expiration_date
Signant: $contact_full_name$contact_user_full_name
Correu electrònic de contacte: $contact_email1$contact_user_email1

Per signar el document, feu clic al següent enllaç. Serà redirigit/da al portal de signatura segura.

Signar Document: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Si teniu problemes per accedir, si us plau, copieu i enganxeu aquest enllaç al vostre navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Aquest és un correu automàtic, per favor no respongueu a aquest missatge.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Signatura de document</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">S\'ha sol·licitat la vostra signatura en el següent document:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Signant: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de caducitat:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Per signar el document, feu clic a continuació i sereu redirigit/da al portal de signatura segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Signar el document</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Si no podeu accedir al portal de signatura, copieu i enganxeu el següent enllaç al vostre navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Document Signat','Plantilla usada per enviar la versió final del document signat electrònicament al signant.','[Document Signat] $stic_signatures_pdf_template','Hola $contact_first_name$contact_user_first_name,

Adjunt trobareu la versió final del document que heu signat electrònicament:

Nom del Document: $stic_signatures_pdf_template
Signant: $contact_full_name$contact_user_full_name
Correu electrònic: $contact_email1$contact_user_email1

Gràcies per completar el procés de signatura.

---

Aquest és un correu automàtic, per favor no respongueu a aquest missatge.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Document signat</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Adjunt a aquest correu trobareu la versió final del document que heu signat electrònicament.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Signant: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de la signatura: </strong>$stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Al portal de signatura podreu accedir al document signat sempre que sigui necessari.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Anar al portal de signatura</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','Codi OTP per a Signatura','Plantilla usada per enviar la Contrasenya d\'Un Sol Ús (OTP) per validar al signant abans d\'accedir al procés de signatura.','[Codi de Verificació] La vostra clau per signar el document','Hola $contact_first_name$contact_user_first_name,

Per completar la signatura electrònica del document:

Nom del Document: $stic_signatures_pdf_template

Utilitzeu la següent Contrasenya d\'Un Sol Ús (OTP) per validar-vos al portal de signatura. Aquest codi és sensible a majúscules i minúscules i expira aviat.

El vostre Codi de Verificació és: $stic_signatures_one_time_password

Si us plau, introduïu aquest codi a la finestra de signatura per continuar.

---

Aquest és un correu automàtic, per favor no respongueu a aquest missatge.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Verificació de signatura</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Per garantir la seguretat i autenticitat de la vostra signatura, utilitzeu el codi de verificació que es mostra a continuació.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Introduïu aquest codi al portal de signatura per procedir. <br /><span style="font-size: 10pt; color: #7e8c8d;">El codi és vàlid durant 10 minuts i només es pot fer servir una vegada.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','Codi OTP per a Signatura (SMS)','Plantilla usada per enviar la contrasenya d\'un sol ús (OTP) per validar al signant abans d\'accedir al procés de signatura per SMS','[Codi de Verificació] La vostra clau per signar el document','$contact_first_name$contact_user_first_name, aquest és el vostre codi per signar: $stic_signers_otp. És vàlid durant 10 minuts.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');