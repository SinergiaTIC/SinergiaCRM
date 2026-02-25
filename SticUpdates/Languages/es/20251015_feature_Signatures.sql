REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
	 ('000005f1-2e4e-3b11-051f-68e3c9e70330',NOW(),NOW(),'1','1','off','Ejemplo - Solicitud de firma','Plantilla para notificar al firmante que se ha solicitado su firma en un documento.','[Firma de documento] Solicitud de firma: $stic_signatures_pdf_template','Solicitud de firma

Se ha solicitado su firma en el siguiente documento:

$stic_signatures_pdf_template

Firmante: $contact_full_name$contact_user_full_name
Fecha de caducidad: $stic_signatures_expiration_date

Para firmar el documento, haga clic a continuación y se le redirigirá al portal de firma segura.

Firmar el documento: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Si no puede acceder al portal de firma, copie y pegue el enlace en su navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id','<!DOCTYPE html>
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
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmante:</strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Fecha de caducidad:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Para firmar el documento, haga clic a continuación y se le redirigirá al portal de firma segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Firmar el documento</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Si no puede acceder al portal de firma, copie y pegue el enlace en su navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70331',NOW(),NOW(),'1','1','off','Ejemplo - Documento firmado','Plantilla para enviar al firmante el documento firmado.','[Firma de documento] Documento firmado: $stic_signatures_pdf_template','Documento firmado

Adjunto a este correo encontrará la versión final del documento que ha firmado:

$stic_signatures_pdf_template

Firmante: $contact_full_name$contact_user_full_name
Fecha de la firma: $stic_signers_signature_date

En el portal de firma podrá acceder al documento firmado siempre que sea necesario.

Ir al portal de firma: $sugarurl/index.php?entryPoint=sticSign&signerId=$stic_signers_id','<!DOCTYPE html>
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
<p style="font-size: 16px; color: #555555;">Adjunto a este correo encontrará la versión final del documento que ha firmado:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Firmante:</strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Fecha de la firma:</strong> $stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">En el portal de firma podrá acceder al documento firmado siempre que sea necesario.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signerId=$stic_signers_id">Ir al portal de firma</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70332',NOW(),NOW(),'1','1','off','Ejemplo - Código de un solo uso para firmar','Plantilla para enviar el código de un solo uso al firmante antes de poder firmar.','[Firma de documento] Código de verificación para firmar','Verificación de firma

Para garantizar la seguridad y autenticidad de su firma, utilice el código de verificación que se muestra a continuación:

$stic_signatures_pdf_template

$stic_signers_otp

Introduzca este código en el portal de firma para proceder. El código es válido durante 10 minutos y solo se puede usar una vez.','<!DOCTYPE html>
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
<p style="font-size: 16px; color: #555555;">Para garantizar la seguridad y autenticidad de su firma, utilice el código de verificación que se muestra a continuación:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Introduzca este código en el portal de firma para proceder. El código es válido durante 10 minutos y solo se puede usar una vez.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
	 ('000005f1-2e4e-3b11-051f-68e3c9e70333',NOW(),NOW(),'1','1','off','Ejemplo - Código de un solo uso para firmar (SMS)','Plantilla para enviar vía SMS el código de un solo uso al firmante antes de poder firmar','[Firma de documento] Código de verificación para firmar','$contact_first_name$contact_user_first_name, este es su código para firmar: $stic_signers_otp. Es válido durante 10 minutos.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');
