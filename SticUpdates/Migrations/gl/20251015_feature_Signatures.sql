REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
      ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Solicitude de sinatura','Modelo empregado para notificar ao asinante que se solicitou a súa sinatura electrónica para un documento.','[Solicitude de sinatura do documento] $stic_signatures_pdf_template','Ola $contact_first_name$contact_user_first_name,

Solicitouse a súa sinatura para o seguinte documento:

Nome do Documento: $stic_signatures_pdf_template
Data de Caducidade: $stic_signatures_expiration_date
Asinante: $contact_full_name$contact_user_full_name
Correo electrónico de contacto: $contact_email1$contact_user_email1

Para asinar o documento, faga clic no seguinte enlace. Será redirixido/a ao portal de sinatura segura.

Asinar Documento: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Se ten problemas para acceder, por favor, copie e pegue este enlace no seu navegador:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Este é un correo automático, por favor non responda a esta mensaxe.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Sinatura de documento</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Solicitouse a súa sinatura no seguinte documento:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Asinante: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data de caducidade:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Para asinar o documento, faga clic a continuación e será redirixido/a ao portal de sinatura segura.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Asinar o documento</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Se non pode acceder ao portal de sinatura, copie e pegue o seguinte enlace no seu navegador: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Documento Asinado','Modelo empregado para enviar a versión final do documento asinado electronicamente ao asinante.','[Documento Asinado] $stic_signatures_pdf_template','Ola $contact_first_name$contact_user_first_name,

Adxunto atopará a versión final do documento que asinou electronicamente:

Nome do Documento: $stic_signatures_pdf_template
Asinante: $contact_full_name$contact_user_full_name
Correo electrónico: $contact_email1$contact_user_email1

Grazas por completar o proceso de sinatura.

---

Este é un correo automático, por favor non responda a esta mensaxe.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Documento asinado</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Adxunto a este correo atopará a versión final do documento que asinou electronicamente.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Asinante: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Data da sinatura: </strong>$stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">No portal de sinatura poderá acceder ao documento asinado sempre que sexa necesario.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Ir ao portal de sinatura</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','Código OTP para Sinatura','Modelo empregado para enviar o Contrasinal dun Só Uso (OTP) para validar ao asinante antes de acceder ao proceso de sinatura.','[Código de Verificación] A súa clave para asinar o documento','Ola $contact_first_name$contact_user_first_name,

Para completar a sinatura electrónica do documento:

Nome do Documento: $stic_signatures_pdf_template

Utilice o seguinte Contrasinal dun Só Uso (OTP) para validarse no portal de sinatura. Este código é sensible a maiúsculas e minúsculas e caduca pronto.

O seu Código de Verificación é: $stic_signatures_one_time_password

Por favor, introduza este código na xanela de sinatura para continuar.

---

Este é un correo automático, por favor non responda a esta mensaxe.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Verificación de sinatura</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Para garantir a seguridade e autenticidade da súa sinatura, utilice o código de verificación que se mostra a continuación.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Introduza este código no portal de sinatura para proceder. <br /><span style="font-size: 10pt; color: #7e8c8d;">O código é válido durante 10 minutos e só se pode usar unha vez.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','Código OTP para Sinatura (SMS)','Modelo empregado para enviar o contrasinal dun só uso (OTP) para validar ao asinante antes de acceder ao proceso de sinatura por SMS','[Código de Verificación] A súa clave para asinar o documento','$contact_first_name$contact_user_first_name, este é o seu código para asinar: $stic_signers_otp. É válido durante 10 minutos.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');