<?php

// ---------------------------------------------------------
// 1. PREPARACIÓ DE DADES (Calculades)
// ---------------------------------------------------------

// Helper per convertir HEX a RGB (necessari per a les ombres de Bootstrap)
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    return "{$r}, {$g}, {$b}";
}

// Càlcul RGB del color primari
$primaryRgb = hex2rgb($theme->primary_color);

// Mapeig de Columnes a Amplada Mínima (Per al Grid Fluid)
$sectionMap = ['1' => '100%', '2' => '500px', '3' => '350px'];
$fieldMap   = ['1' => '100%', '2' => '300px', '3' => '200px', '4' => '150px'];

$sectionMinWidth = $sectionMap[$theme->sections_per_row ?? '1'] ?? '100%';
$fieldMinWidth   = $fieldMap[$theme->fields_per_row ?? '1'] ?? '100%';

// ID únic per a l'scoping CSS (aïllament)
$wrapperId = 'stic-awf-' . $formId; 

?>

<div id="<?php echo $wrapperId; ?>">
<style>
    /* Aïllament: Totes les variables s'apliquen només dins del wrapper */
    #<?php echo $wrapperId; ?> {
        
        /* --- 1. COLORS CORPORATIUS (Bootstrap Mappings) --- */
        /* Color Primari (Botons, links, focus) */
        --bs-primary: <?php echo $theme->primary_color; ?>;
        --bs-primary-rgb: <?php echo $primaryRgb; ?>;
        
        /* Color de Text */
        --bs-body-color: <?php echo $theme->text_color; ?>;
        --bs-heading-color: <?php echo $theme->text_color; ?>;
        
        /* Fons dels Inputs i Cards (Sol ser blanc) */
        --bs-body-bg: <?php echo $theme->form_bg_color; ?>;

        /* Color de Vores (Inputs, separadors) */
        --bs-border-color: <?php echo $theme->border_color; ?>;
        
        /* --- 2. GEOMETRIA I TIPOGRAFIA --- */
        /* Arrodoniment (Inputs, Botons, Cards) */
        --bs-border-radius: <?php echo $theme->border_radius; ?>px;
        
        /* Font Global */
        --bs-body-font-family: <?php echo $theme->font_family; ?>;

        /* --- 3. GRID FLUID (Variables pròpies) --- */
        /* Amplada mínima abans de saltar de línia */
        --awf-section-min-width: <?php echo $sectionMinWidth; ?>;
        --awf-field-min-width: <?php echo $fieldMinWidth; ?>;

        /* --- 4. ESTILS ESPECÍFICS DEL FORMULARI --- */
        /* Gruix del borde de la targeta principal */
        --awf-card-border-width: <?php echo $theme->border_width; ?>px;
        
        /* Color de fons de la pàgina (el wrapper sencer) */
        background-color: <?php echo $theme->page_bg_color; ?>;
        
        /* Reset bàsic per assegurar que es veu bé dins de qualsevol web */
        font-family: var(--bs-body-font-family);
        color: var(--bs-body-color);
        padding: 2rem 1rem; /* Espai al voltant del formulari */

        /* 1. MIDA DE LLETRA BASE (Afecta a tot l'escalat) */
        /* Assegura't de posar px, ja que el navegador espera una unitat */
        font-size: <?php echo $theme->font_size; ?>px;

        /* 2. AMPLADA MÀXIMA */
        --awf-max-width: <?php echo $theme->form_width; ?>;
    
        /* 3. OMBRA (Mapping PHP -> CSS) */
        <?php 
            $shadows = [
                'none' => 'none', 
                'sm' => '0 .125rem .25rem rgba(0,0,0,.075)',
                'normal' => '0 .5rem 1rem rgba(0,0,0,.15)',
                'lg' => '0 1rem 3rem rgba(0,0,0,.175)'
            ];
            $shadowVal = $shadows[$theme->shadow_intensity] ?? $shadows['normal'];
        ?>
        --awf-box-shadow: <?php echo $shadowVal; ?>;
    }

    /* --- APLICACIÓ DELS ESTILS --- */

    /* Botons: Forcem el color perquè Bootstrap a vegades usa SASS */
    #<?php echo $wrapperId; ?> .btn-primary {
        --bs-btn-bg: var(--bs-primary);
        --bs-btn-border-color: var(--bs-primary);
        --bs-btn-hover-bg: var(--bs-primary);
        --bs-btn-hover-border-color: var(--bs-primary);
    }
    #<?php echo $wrapperId; ?> .btn-primary:hover {
        filter: brightness(0.9); /* Enfosquim lleugerament al hover */
    }

    /* Targeta Principal del Formulari */
    #<?php echo $wrapperId; ?> .awf-main-card {
        /* Centrar el formulari si és més estret que la pantalla */
        margin-left: auto;
        margin-right: auto;

        /* Aplicar amplada */
        width: 100%;
        max-width: var(--awf-max-width);

        background-color: var(--bs-body-bg);
        border: var(--awf-card-border-width) solid var(--bs-border-color);
        border-radius: var(--bs-border-radius);

        /* Aplicar ombra */
        box-shadow: var(--awf-box-shadow) !important; /* Force per sobreescriure Bootstrap si cal */
    }

    /* Grid System Màgic */
    #<?php echo $wrapperId; ?> .awf-grid-sections {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--awf-section-min-width)), 1fr));
    }

    #<?php echo $wrapperId; ?> .awf-grid-fields {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--awf-field-min-width)), 1fr));
    }
</style>

<div class="container" style="max-width: 1140px;">
    
    <div class="awf-main-card p-4 p-md-5">
        
        <div class="mb-4">
            <?php echo $layout->header_html; ?>
        </div>

        <form ... x-data="{...}">
            
            <div class="awf-grid-sections">
                
                <div class="card shadow-none border h-100">
                     <div class="card-header bg-light fw-bold">Dades</div>
                     <div class="card-body">
                         
                         <div class="awf-grid-fields">
                             </div>

                     </div>
                </div>

            </div>

            <div class="mt-4 text-end">
                 <button type="submit" class="btn btn-primary px-4 py-2">Enviar</button>
            </div>

        </form>

        <div class="mt-4 text-muted small">
            <?php echo $layout->footer_html; ?>
        </div>

    </div>
</div>

</div> ```

### Detalls clau d'aquesta implementació:

1.  **`hex2rgb`**: És vital. Si no ho fas, l'anell de focus blau (`focus-ring`) dels inputs de Bootstrap es veurà blau (el per defecte) en lloc del teu color corporatiu, perquè Bootstrap utilitza `rgba(var(--bs-primary-rgb), 0.25)`.
2.  **`page_bg_color`**: L'aplico al `#awf-wrapper` directament. Així, si incrusten el formulari en una pàgina buida o un iframe, tindrà el fons de color (gris suau). Si l'incrusten en una web (div), crearà una caixa de color al voltant del formulari.
3.  **`border_width`**: Com que Bootstrap no té una variable global per al gruix del borde de les *Cards*, he creat `--awf-card-border-width` i l'he aplicat manualment a la classe `.awf-main-card`.
4.  **`font_family`**: Mapejada a `--bs-body-font-family`. Això canviarà la font de tot el formulari automàticament.