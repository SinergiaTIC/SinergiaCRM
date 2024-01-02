<?PHP

// NOTA: Se crean clases condicionales para evitar problemas con el modo estricto de PHP 7

require_once('modules/DHA_PlantillasDocumentos/DHA_PlantillasDocumentosParent.php');

global $sugar_version;
$sugar_7 = version_compare($sugar_version, '7.0.0', '>=');


if ($sugar_7) {
class DHA_PlantillasDocumentos extends DHA_PlantillasDocumentosParent {

   ///////////////////////////////////////////////////////////////////////////////////////////////////
   function populateFromRow(array $row, $convert = false) {
       
      $row = parent::populateFromRow($row, $convert);

      if (!empty($this->document_name) && empty($this->name)) {
         $this->name = $this->document_name;
      }

      return $row;
   }

}
}


if (!$sugar_7) {
class DHA_PlantillasDocumentos extends DHA_PlantillasDocumentosParent {

   ///////////////////////////////////////////////////////////////////////////////////////////////////
   function populateFromRow($row) {

      parent::populateFromRow($row);

      if (!empty($this->document_name) && empty($this->name)) {
         $this->name = $this->document_name;
      }
   }

}
}


?>