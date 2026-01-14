-- SinergiaCRM: Sample template sections


REPLACE INTO templatesectionline VALUES ('00000caa-7105-eef5-ad4a-68a414181198', 'Example 01 - Header Image', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#ffffff;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center"><img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/cabecera_500px.png" /></td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/01_ImagenDeCabecera_225px.png', '', 1, '1');

REPLACE INTO templatesectionline VALUES ('00000946-dbcc-c8ba-9c16-68a41426bf4e', 'Example 02 - Logo', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center"><img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/logo_200px.png" /></td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/02_Logo_225px.png', '', 2, '1');

REPLACE INTO templatesectionline VALUES ('00000050-98e6-6f0d-04c0-68a4143f6d6c', 'Example 03 - H1 Header', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center">
                <h1>H1 Header</h1>
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/03_Encabezado_H1_225px.png', '', 3, '1');

REPLACE INTO templatesectionline VALUES ('0000006d-a721-a6c5-9df6-68a414b67f90', 'Example 04 - H3 Header', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center"><h3>H3 Header</h3></td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/04_Encabezado_H3_225px.png', '', 4, '1');

REPLACE INTO templatesectionline VALUES ('00000fbe-4fbd-9659-bd90-68a31e6c9225', 'Example 05 - Text', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna.</p>
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/05_Texto.png', '', 5, '1');

REPLACE INTO templatesectionline VALUES ('000004dd-964f-9c67-1993-68a3289ad277', 'Example 06 - Comment', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center">
                <p><em>* This is a comment</em></p>
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/06_Commentario_225px.png', '', 6, '1');

REPLACE INTO templatesectionline VALUES ('00000fc7-4281-5d09-45f3-68a415cd2a79', 'Example 07 - Notice', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;font-weight:bold;color:#FF0000;" align="center">This is a highlighted message</td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/07_Aviso_225px.png', '', 7, '1');

REPLACE INTO templatesectionline VALUES ('d3b49c38-eeba-b910-2590-5b4069caaac1', 'Example 08 - Image on the left', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table width="100%" style="background-color:white">
    <tbody>
        <tr>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_350px.png" />
            </td>
            <td align="center" style="padding:10px;">
                <h2>Lorem ipsum</h2>                    
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/08_ImagenALaIzquierda_225px.png', '', 8, '1');

REPLACE INTO templatesectionline VALUES ('dba65685-d26f-6ecd-802c-5b406983c9b5', 'Example 09 - Image on the right', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table width="100%" style="background-color:white">
    <tbody>
        <tr>
            <td align="center" style="padding:10px;">
                <h2>Lorem ipsum</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>
            </td>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_350px.png" />
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/09_ImagenALaDerecha_225px.png', '', 9, '1');

REPLACE INTO templatesectionline VALUES ('52cdc554-bd90-f8f9-e2e0-66d718ae8fe9', 'Example 10 - Two columns with an image', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table width="100%" style="background-color:white">
    <tbody>
        <tr>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_350px.png" />
                <h3>Lorem ipsum 1</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
            </td>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_350px.png" />
                <h3>Lorem ipsum 2</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/10_DosColumnasConImagen_225px.png', '', 10, '1');

REPLACE INTO templatesectionline VALUES ('9f118e85-13f2-baa9-4ee3-66d80c674dea', 'Example 11 - Three columns with an image', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table width="100%" style="background-color:white">
    <tbody>
        <tr>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_250px.png" />
                <h3>Lorem ipsum 1</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
            </td>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_250px.png" />
                <h3>Lorem ipsum 2</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
            </td>
            <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                <img src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/stic_image_250px.png" />
                <h3>Lorem ipsum 3</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
            </td>                
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/11_TresColumnasConImagen_225px.png', '', 11, '1');

REPLACE INTO templatesectionline VALUES ('00000f55-0165-c6f0-d49e-68a31f58ce3f', 'Example 12 - Video', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center"><a href="https://tuservidor.com/video.mp4"> <img style="border:0;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/video_player.png" /> </a></td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/12_Video_225px.png', '', 12, '1');

REPLACE INTO templatesectionline VALUES ('000009b2-a259-4b41-df58-68a31fdc03aa', 'Example 13 - Button', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF; width:100%;">
    <tbody>
        <tr>
            <td align="center">
                <a href="#" style="display:inline-block; font-size:16px; padding:20px; border-radius:50px; background-color:#b4bc32; font-weight:bold; text-decoration:none; color:#ffffff;">
                    Button
                </a>
            </td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/13_Boton_225px.png', '', 13, '1');

REPLACE INTO templatesectionline VALUES ('0000021e-291a-84c6-8641-68a323ea5043', 'Example 14 - Separator', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<br /><br />
    <hr />
<br /><br />
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/14_Separador_225px.png', '', 14, '1');

REPLACE INTO templatesectionline VALUES ('00000138-07eb-c36d-1c5f-68a325bf884c', 'Example 15 - Footer - Social Media', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td style="padding:10px;" align="center">
                <a href="https://www.youtube.com/entidad"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/youtube_64px.png" alt="YouTube" width="24" /></a> 
                <a href="https://www.facebook.com/entidad"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/facebook_64px.png" alt="Facebook" width="24" /></a> 
                <a href="https://www.instagram.com/entidad"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/instagram_64px.png" alt="Instagram" width="24" /></a> 
                <a href="https://www.x.com/entidad"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/twitter_64px.png" alt="Twitter" width="24" /></a> 
                <a href="https://es.linkedin.com/company/entidad"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/linkedin_64px.png" alt="Linkedin" width="24" /></a> 
                <a href="https://tu_web.org/"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/web_64px.png" alt="Web" width="24" /></a> 
                <a href="mailto:info@entidad.org"><img style="margin:0 5px;" src="http://localhost:8000/sinergiacrm/custom/modules/TemplateSectionLine/sticImagesFiles/Images/email_64px.png" alt="Correo electrónico" width="24" /></a></td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/15_Pie_Redes_Sociales_225px.png', '', 15, '1');

REPLACE INTO templatesectionline VALUES ('00000ec2-1b97-6474-0aa2-68a3254bbb85', 'Example 16 - Footer - Unsubscribe Link', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
<table style="background-color:#FFFFFF;" width="100%">
    <tbody>
        <tr>
            <td >If you do not wish to receive further information, click this <a href="UNSUBSCRIBE_LINK">link</a> ...</td>
        </tr>
    </tbody>
</table>
', 0, 'custom/modules/TemplateSectionLine/sticImagesFiles/Thumbnails/16_Pie_Texto_Baja_225px.png', '', 16, '1');
