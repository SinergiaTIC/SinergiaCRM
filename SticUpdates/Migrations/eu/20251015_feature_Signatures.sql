REPLACE INTO email_templates (id,date_entered,date_modified,modified_user_id,created_by,published,name,description,subject,body,body_html,deleted,assigned_user_id,text_only,`type`) VALUES
      ('000005f1-2e4e-3b11-051f-68e3c9e70330','2025-11-04 16:01:43','2025-11-04 15:09:27','2','1','off','Sinadura Eskaera','Sinatzaileari dokumentu baterako sinadura elektronikoa eskatu zaiola jakinarazteko erabiltzen den txantiloia.','[Dokumentuaren sinadura eskaera] $stic_signatures_pdf_template','Kaixo $contact_first_name$contact_user_first_name,

Zure sinadura eskatu da hurrengo dokumenturako:

Dokumentuaren Izena: $stic_signatures_pdf_template
Iraungitze Data: $stic_signatures_expiration_date
Sinatzailea: $contact_full_name$contact_user_full_name
Harremanetarako posta elektronikoa: $contact_email1$contact_user_email1

Dokumentua sinatzeko, egin klik beheko estekan. Sinadura atari segurura bideratuko zara.

Sinatu Dokumentua: $sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

Sartzeko arazoak badituzu, mesedez, kopiatu eta itsatsi esteka hau zure nabigatzailean:
$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id

---

Hau mezu automatikoa da; mesedez, ez erantzun mezu honi.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Dokumentuaren Sinadura</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Zure sinadura eskatu da hurrengo dokumentuan:</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Sinatzailea: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Iraungitze Data:</strong> $stic_signatures_expiration_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Dokumentua sinatzeko, egin klik behean eta sinadura atari segurura bideratuko zara.</p>
<p style="font-size: 16px; color: #555555; margin-top: 20px;"> </p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">Sinatu Dokumentua</a></div>
<p style="font-size: 14px; color: #aaaaaa; text-align: center; margin-top: 30px;">Sinadura atarira ezin baduzu sartu, kopiatu eta itsatsi hurrengo esteka zure nabigatzailean: <br /><a style="color: #b5bc31; text-decoration: none;" href="$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id">$sugarurl/index.php?entryPoint=sticSign&signatureId=$stic_signatures_id&targetId=$contact_id$contact_user_id</a></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70331','2025-11-04 16:01:43','2025-11-04 15:12:34','2','1','off','Dokumentua Sinatuta','Sinatutako dokumentuaren azken bertsio elektronikoa sinatzaileari bidaltzeko erabiltzen den txantiloia.','[Dokumentu Sinatua] $stic_signatures_pdf_template','Kaixo $contact_first_name$contact_user_first_name,

Erantsita aurkituko duzu elektronikoki sinatu duzun dokumentuaren azken bertsioa:

Dokumentuaren Izena: $stic_signatures_pdf_template
Sinatzailea: $contact_full_name$contact_user_full_name
Posta elektronikoa: $contact_email1$contact_user_email1

Eskerrik asko sinadura prozesua osatzeagatik.

---

Hau mezu automatikoa da; mesedez, ez erantzun mezu honi.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Dokumentua sinatuta</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Mezu elektroniko honi erantsita, elektronikoki sinatu duzun dokumentuaren azken bertsioa aurkituko duzu.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Sinatzailea: </strong> $contact_full_name$contact_user_full_name</p>
<p style="margin: 5px 0 0 0; font-size: 14px; color: #888888;"><strong>Sinadura Data: </strong>$stic_signers_signature_date</p>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Sinadura atarian sinatutako dokumentua atzitu ahal izango duzu beharrezkoa denean.</p>
<div style="text-align: center; margin-top: 30px;"><a style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #000000; background-color: #b5bc31; border-radius: 5px; text-decoration: none; font-weight: bold;" href="#">Joan Sinadura Atariara</a></div>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70332','2025-11-04 16:01:43','2025-11-04 15:18:28','2','1','off','OTP Kodea Sinadurarako','Behin-behineko Pasahitza (OTP) bidaltzeko erabiltzen den txantiloia, sinatzailea sinadura prozesura sartu aurretik balioztatzeko.','[Egiaztapen Kodea] Zure gakoa dokumentua sinatzeko','Kaixo $contact_first_name$contact_user_first_name,

Dokumentuaren sinadura elektronikoa osatzeko:

Dokumentuaren Izena: $stic_signatures_pdf_template

Erabili hurrengo Behin-behineko Pasahitza (OTP) sinadura atarian balioztatzeko. Kode honek maiuskulak eta minuskulak bereizten ditu eta laster iraungiko da.

Zure Egiaztapen Kodea hauxe da: $stic_signatures_one_time_password

Mesedez, sartu kode hau sinadura leihoan jarraitzeko.

---

Hau mezu automatikoa da; mesedez, ez erantzun mezu honi.','<!DOCTYPE html>
<html>
<head>
</head>
<body>
<p> </p>
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
<div style="background-color: #b5bc31; color: #000000; padding: 20px; text-align: center;">
<h1 style="margin: 0; font-size: 24px;">Sinadura Egiaztapena</h1>
</div>
<div style="padding: 20px;">
<p style="font-size: 16px; color: #555555;">Zure sinaduraren segurtasuna eta benetakotasuna bermatzeko, erabili behean agertzen den egiaztapen kodea.</p>
<div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eeeeee; margin-top: 20px;">
<p style="margin: 0; font-size: 16px; color: #333333;"><strong>$stic_signatures_pdf_template</strong></p>
<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><span style="display: inline-block; padding: 15px 30px; font-size: 28px; color: #000000; background-color: #b5bc31; border-radius: 5px; font-weight: bold; letter-spacing: 5px;">$stic_signers_otp</span></div>
</div>
<p style="font-size: 16px; color: #555555; margin-top: 20px;">Sartu kode hau sinadura atarian aurrera egiteko. <br /><span style="font-size: 10pt; color: #7e8c8d;">Kodea 10 minutuz da baliozkoa eta behin bakarrik erabili daiteke.</span></p>
</div>
</div>
</body>
</html>',0,'1',0,'notification'),
      ('000005f1-2e4e-3b11-051f-68e3c9e70333','2025-11-03 11:16:29','2025-11-04 15:20:51','2','2','off','OTP Kodea Sinadurarako (SMS)','Behin-behineko pasahitza (OTP) bidaltzeko erabiltzen den txantiloia, sinatzailea sinadura prozesura SMS bidez sartu aurretik balioztatzeko.','[Egiaztapen Kodea] Zure gakoa dokumentua sinatzeko','$contact_first_name$contact_user_first_name, hau da sinatzeko zure kodea: $stic_signers_otp. 10 minutuz da baliozkoa.','<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>',0,'1',1,'system');