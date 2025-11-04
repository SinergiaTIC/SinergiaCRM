REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
      ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Signature Request','Template used to notify the signer that their electronic signature has been requested for a document.','[Signature Request for Document] $stic_signatures_pdf_template','Hello $contact_first_name$contact_user_first_name,

Your signature has been requested for the following document:

Document Name: $stic_signatures_pdf_template
Expiration Date: $stic_signatures_expiration_date
Signer: $contact_full_name$contact_user_full_name
Contact Email: $contact_email1$contact_user_email1

To sign the document, please click on the link below. You will be redirected to the secure signing portal.

Sign Document: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

If you have trouble accessing it, please copy and paste this link into your browser:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

This is an automated email, please do not reply to this message.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Document Signature</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Your signature has been requested on the following document:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Signer: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Expiration Date:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">To sign the document, please click below and you will be redirected to the secure signing portal.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Sign Document</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">If you cannot access the signing portal, copy and paste the following link into your browser: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Document Signed','Template used to send the final electronically signed version of the document to the signer.','[Signed Document] $stic_signatures_pdf_template','Hello $contact_first_name$contact_user_first_name,

Attached you will find the final version of the document you have electronically signed:

Document Name: $stic_signatures_pdf_template
Signer: $contact_full_name$contact_user_full_name
Email: $contact_email1$contact_user_email1

Thank you for completing the signing process.

---

This is an automated email, please do not reply to this message.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Document Signed</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Attached to this email you will find the final version of the document you have electronically signed.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Signer: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Date of Signature: </strong>$stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">You can access the signed document in the signing portal whenever necessary.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Go to Signing Portal</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','OTP Code for Signature','Template used to send the One-Time Password (OTP) to validate the signer before accessing the signing process.','[Verification Code] Your key to sign the document','Hello $contact_first_name$contact_user_first_name,

To complete the electronic signature of the document:

Document Name: $stic_signatures_pdf_template

Use the following One-Time Password (OTP) to validate yourself in the signing portal. This code is case-sensitive and expires soon.

Your Verification Code is: $stic_signatures_one_time_password

Please enter this code in the signing window to continue.

---

This is an automated email, please do not reply to this message.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Signature Verification</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">To ensure the security and authenticity of your signature, please use the verification code shown below.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Please enter this code in the signing portal to proceed. <br /><span style="font-size: 10pt; color: #7e8c8d;">The code is valid for 10 minutes and can only be used once.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','OTP Code for Signature (SMS)','Template used to send the one-time password (OTP) to validate the signer before accessing the signing process via SMS','[Verification Code] Your key to sign the document','$contact_first_name$contact_user_first_name, this is your code to sign: $stic_signers_otp. It is valid for 10 minutes.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');