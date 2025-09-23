<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$GLOBALS['log']->debug("Executing SticUpdates/Scripts/CreateTemplateSectionLineSticExamples.php.");

global $db, $current_user, $config;

$db = DBManagerFactory::getInstance();

if ($db instanceof DBManager) 
{
    if ($db->query($query)) {
        $GLOBALS['log']->debug("Deleted template section lines successfully.");
    } else {
        $GLOBALS['log']->debug("Deleted template section lines unsuccessfully.");
    }

    $site_url = $sugar_config['site_url'];
    $site_url = 'https://manuelsuite.sinergiacrm.org';
    $default_language = $sugar_config['default_language'];
    switch ($default_language) 
    {
        case 'es_ES':
        case 'gl_ES':
        case 'eu_ES':
            $recordsName = array (
                'Ejemplo 01 - Imagen de cabecera',
                'Ejemplo 02 - Logo',
                'Ejemplo 03 - Encabezado H1',
                'Ejemplo 04 - Encabezado H3',
                'Ejemplo 05 - Texto',
                'Ejemplo 06 - Comentario',
                'Ejemplo 07 - Aviso',
                'Ejemplo 08 - Imagen a la izquierda',
                'Ejemplo 09 - Imagen a la derecha',
                'Ejemplo 10 - Dos columnas con imagen',
                'Ejemplo 11 - Tres columnas con imagen',
                'Ejemplo 12 - Video',
                'Ejemplo 13 - Botón',
                'Ejemplo 14 - Separador',
                'Ejemplo 15 - Pie - Redes sociales',
                'Ejemplo 16 - Pie - Enlace de Baja'        
            );
            $htmlTexts = array (
                'Cabecera H1',
                'Cabecera H3',
                '* Esto es un comentario',
                'Esto es un mensaje resaltado en color',
                'Botón',
                'Si no quieres recibir más información pulsa en este <a href="UNSUBSCRIBE_LINK">enlace</a> ... '
            );
            break;

        case 'ca_ES':
            $recordsName = array ( 
                'Exemple 01 - Imatge de capçalera', 
                'Exemple 02 - Logo', 
                'Exemple 03 - Encapçalat H1', 
                'Exemple 04 - Encapçalat H3', 
                'Exemple 05 - Text', 
                'Exemple 06 - Comentari', 
                'Exemple 07 - Avís', 
                "Exemple 08 - Imatge a l'esquerra", 
                'Exemple 09 - Imatge a la dreta', 
                'Exemple 10 - Dues columnes amb imatge', 
                'Exemple 11 - Tres columnes amb imatge',                
                'Exemple 12 - Video', 
                'Exemple 13 - Botó', 
                'Exemple 14 - Separador', 
                'Exemple 15 - Peu - Xarxes socials', 
                'Exemple 16 - Peu - Enllaç de Baixa' 
            );
            $htmlTexts = array ( 
                'Capçalera H1', 
                'Capçalera H3', 
                '* Això és un comentari', 
                'Això és un missatge ressaltat en color', 
                'Botó',                
                'Si no vols rebre més informació fes clic en aquest <a href="UNSUBSCRIBE_LINK">enllaç</a> ... ' 
            );                
            break;

        default:
            $recordsName = array (
                'Example 01 - Header Image',
                'Example 02 - Logo',
                'Example 03 - H1 Header',
                'Example 04 - H3 Header',
                'Example 05 - Text',
                'Example 06 - Comment',
                'Example 07 - Notice',
                'Example 08 - Image on the left',
                'Example 09 - Image on the right',
                'Example 10 - Two columns with an image',
                'Example 11 - Three columns with an image',                
                'Example 12 - Video',
                'Example 13 - Button',
                'Example 14 - Separator',
                'Example 15 - Footer - Social Media',
                'Example 16 - Footer - Unsubscribe Link'
            );
            $htmlTexts = array (
                'H1 Header',
                'H3 Header',
                '* This is a comment',
                'This is a highlighted message',
                'Button',
                'If you do not wish to receive further information, click this <a href="UNSUBSCRIBE_LINK">link</a> ...'
            );                
            break;
    }

    $insertQueries = array (
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000caa-7105-eef5-ad4a-68a414181198', '{$recordsName[0]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#ffffff;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center"><img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/cabecera_500px.png" /></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/01_ImagenDeCabecera_225px.png', '', 1, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000946-dbcc-c8ba-9c16-68a41426bf4e', '{$recordsName[1]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center"><img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" /></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/02_Logo_225px.png', '', 2, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000050-98e6-6f0d-04c0-68a4143f6d6c', '{$recordsName[2]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center">
                        <h1>{$htmlTexts[0]}</h1>
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/03_Encabezado_H1_225px.png', '', 3, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('0000006d-a721-a6c5-9df6-68a414b67f90', '{$recordsName[3]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center"><h3>{$htmlTexts[1]}</h3></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/04_Encabezado_H3_225px.png', '', 4, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000fbe-4fbd-9659-bd90-68a31e6c9225', '{$recordsName[4]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/05_Texto.png', '', 5, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('000004dd-964f-9c67-1993-68a3289ad277', '{$recordsName[5]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center">
                        <p><em>{$htmlTexts[2]}</em></p>
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/06_Commentario_225px.png', '', 6, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000fc7-4281-5d09-45f3-68a415cd2a79', '{$recordsName[6]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;font-weight:bold;color:#FF0000;" align="center">{$htmlTexts[3]}</td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/07_Aviso_225px.png', '', 7, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('d3b49c38-eeba-b910-2590-5b4069caaac1', '{$recordsName[7]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table width="100%" style="background-color:white">
            <tbody>
                <tr>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                    </td>
                    <td align="center" style="padding:10px;">
                        <h2>Lorem ipsum</h2>                    
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/08_ImagenALaIzquierda_225px.png', '', 8, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('dba65685-d26f-6ecd-802c-5b406983c9b5', '{$recordsName[8]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table width="100%" style="background-color:white">
            <tbody>
                <tr>
                    <td align="center" style="padding:10px;">
                        <h2>Lorem ipsum</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>
                    </td>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/09_ImagenALaDerecha_225px.png', '', 9, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('52cdc554-bd90-f8f9-e2e0-66d718ae8fe9', '{$recordsName[9]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table width="100%" style="background-color:white">
            <tbody>
                <tr>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                        <h3>Lorem ipsum 1</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
                    </td>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                        <h3>Lorem ipsum 2</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
                    </td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/10_DosColumnasConImagen_225px.png', '', 10, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('9f118e85-13f2-baa9-4ee3-66d80c674dea', '{$recordsName[10]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table width="100%" style="background-color:white">
            <tbody>
                <tr>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                        <h3>Lorem ipsum 1</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
                    </td>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                        <h3>Lorem ipsum 2</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
                    </td>
                    <td style="padding:10px;text-align: center; vertical-align: middle;" align="center">
                        <img src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/logo_200px.png" />
                        <h3>Lorem ipsum 3</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dolor purus, dapibus quis tempor aliquet, pellentesque vel magna. Suspendisse quis leo a elit tincidunt tincidunt. Aliquam rhoncus semper tempor. Nunc porta ultricies neque, ut blandit libero dictum at. Sed non elementum lacus. Sed a finibus sapien. Morbi feugiat eget lectus porttitor vehicula. Maecenas condimentum turpis sit amet mi vestibulum, eget volutpat lectus tincidunt. Nunc nec mauris orci. Fusce tempor ut ante id tempus. Proin nisi nunc, egestas et fringilla ut, consectetur et sem. Nunc finibus nunc mi, suscipit sagittis orci lacinia eget. Phasellus in orci id ante tincidunt laoreet vitae nec diam.</p>                    
                    </td>                
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/11_TresColumnasConImagen_225px.png', '', 11, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000f55-0165-c6f0-d49e-68a31f58ce3f', '{$recordsName[11]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center"><a href="https://tuservidor.com/video.mp4"> <img style="border:0;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/reproductor_video_600px.jpg" alt="Ver video" width="500" height="333" /> </a></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/12_Video_225px.png', '', 12, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('000009b2-a259-4b41-df58-68a31fdc03aa', '{$recordsName[12]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;">
            <tbody>
                <tr>
                    <td style="font-size:16px;padding:20px;border-radius:50px;background-color:#b4bc32;"><a style="font-weight:bold;text-decoration:none;color:#ffffff;" title="Lorem Ipsum">{$htmlTexts[4]}</a></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/13_Boton_225px.png', '', 13, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('0000021e-291a-84c6-8641-68a323ea5043', '{$recordsName[13]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <br /><br />
            <hr />
        <br /><br />
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/14_Separador_225px.png', '', 14, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000138-07eb-c36d-1c5f-68a325bf884c', '{$recordsName[14]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td style="padding:10px;" align="center">
                        <a href="https://www.twitter.com/entidad"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/twitter_64px.png" alt="Twitter" width="24" /></a> 
                        <a href="https://www.facebook.com/entidad"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/facebook_64px.png" alt="Facebook" width="24" /></a> 
                        <a href="https://www.instagram.com/entidad"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/instagram_64px.png" alt="Instagram" width="24" /></a> 
                        <a href="https://www.youtube.com/entidad"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/youtube_64px.png" alt="YouTube" width="24" /></a> 
                        <a href="https://es.linkedin.com/company/entidad"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/linkedin_64px.png" alt="Linkedin" width="24" /></a> 
                        <a><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/web_64px.png" alt="Web" width="24" /></a> 
                        <a href="mailto:info@entidad.org"><img style="margin:0 5px;" src="{$site_url}/custom/modules/TemplateSectionLine/stic_images_files/Images/email_64px.png" alt="Correo electrónico" width="24" /></a></td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/15_Pie_Redes_Sociales_225px.png', '', 15, '1');
        SQL
        ,
        <<<SQL
        INSERT INTO templatesectionline VALUES('00000ec2-1b97-6474-0aa2-68a3254bbb85', '{$recordsName[15]}', UTC_TIMESTAMP(), UTC_TIMESTAMP(), '1', '1', '
        <table style="background-color:#FFFFFF;" width="100%">
            <tbody>
                <tr>
                    <td >{$htmlTexts[5]}</td>
                </tr>
            </tbody>
        </table>
        ', 0, 'custom/modules/TemplateSectionLine/stic_images_files/Thumbnails/16_Pie_Texto_Baja_225px.png', '', 16, '1');
        SQL
    );

    foreach($insertQueries as $key => $insertQuery) {
        if ($db->query($insertQuery)) {
            $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.':' . "The example record 'Example " . $key + 1 . "' has been insertd or created in the TemplateSectionLine module.");
        } else {
            $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':' . "The example record 'Example " . $key + 1 . "' has not been insertd or created in the TemplateSectionLine module.");
        }
    }
} else {
    $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': DBManager is not set');
}