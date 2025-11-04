REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
	 ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Solicitud firma','Plantilla usada para notificar al firmante que se ha solicitado su firma electrónica para un documento.','[Solicitud de firma del documento] $stic_signatures_pdf_template','Hola $contact_first_name$contact_user_first_name,

Se ha solicitado su firma para el siguiente documento:

Nombre del Documento: $stic_signatures_pdf_template
Fecha de Caducidad: $stic_signatures_expiration_date
Firmante: $contact_full_name$contact_user_full_name
Correo electrónico de contacto: $contact_email1$contact_user_email1

Para firmar el documento, haga clic en el siguiente enlace. Será redirigido al portal de firma segura.

Firmar Documento: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Si tiene problemas para acceder, por favor, copie y pegue este enlace en su navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Este es un correo automático, por favor no responda a este mensaje.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Firma de documento</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Se ha solicitado su firma en el siguiente documento:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmante: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Fecha de caducidad:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Para firmar el documento, haga clica continuación y será redirigido al portal de firma segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Firmar el documento</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Si no puede acceder al portal de firma, copie y pegue el siguiente enlace en su navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Documento Firmado','Plantilla usada para enviar la versión final del documento firmado electrónicamente al firmante.','[Documento Firmado] $stic_signatures_pdf_template','Hola $contact_first_name$contact_user_first_name,

Adjunto encontrará la versión final del documento que ha firmado electrónicamente:

Nombre del Documento: $stic_signatures_pdf_template
Firmante: $contact_full_name$contact_user_full_name
Correo electrónico: $contact_email1$contact_user_email1

Gracias por completar el proceso de firma.

---

Este es un correo automático, por favor no responda a este mensaje.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Documento firmado</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Adjunto a este correo encontrará la versión final del documento que ha firmado electrónicamente.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmante: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Fecha de la firma: </strong>$stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">En el portal de firma podrá acceder al documento firmado siempre que sea necesario.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Ir al portal de firma</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','Codigo OTP para Firma','Plantilla usada para enviar la Contraseña de Un Solo Uso (OTP) para validar al firmante antes de acceder al proceso de firma.','[Código de Verificación] Su clave para firmar el documento','Hola $contact_first_name$contact_user_first_name,

Para completar la firma electrónica del documento:

Nombre del Documento: $stic_signatures_pdf_template

Utilice la siguiente Contraseña de Un Solo Uso (OTP) para validarse en el portal de firma. Este código es sensible a mayúsculas y minúsculas y expira pronto.

Su Código de Verificación es: $stic_signatures_one_time_password

Por favor, introduzca este código en la ventana de firma para continuar.

---

Este es un correo automático, por favor no responda a este mensaje.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Verificación de firma</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Para garantizar la seguridad y autenticidad de su firma, utilice el código de verificación que se muestra a continuación.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Introduzca este código  en el portal de firma para proceder. <br /><span style="font-size: 10pt; color: #7e8c8d;">El código es válido durante 10 minutos y solo se puede usar una vez.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','Codigo OTP para Firma (SMS)','Plantilla usada para enviar la contraseña de un solo uso (OTP) para validar al firmante antes de acceder al proceso de firma por SMS','[Código de Verificación] Su clave para firmar el documento','$contact_first_name$contact_user_first_name, este es su código para firmar: $stic_signers_otp. Es válido durante 10 minutos.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');
