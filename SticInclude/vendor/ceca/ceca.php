<?php
namespace Ubublog\Ceca;
use Exception;
/**
* Ceca
* @package ceca
* @author Eduardo Díaz
* @since 1.0.0
*/
class Ceca{
	/**	 
	 * @var integer $_merchantID Requerido 9 Identifica al comercio. Facilitado por la caja en el proceso de alta
	 */
	protected $_merchantID;
	
	/**
	 * @var integer $_acquirerBIN Requerido 10 Identifica la caja. Facilitado por la caja en el proceso de alta.
	 */
	protected $_acquirerBIN;

	/**
	 * @var integer $_terminalID Requerido 8 Identifica al terminal. Facilitado por la caja en el proceso de alta.
	 */
	protected $_terminalID;

	/**
	 * @var string $_num_operacion Requerido 50
	 */
	protected $_num_operacion;

	/**	 
	 * @var float $_importe Requerido 12 Importe de la operación sin formatear. Siempre será un número entero donde los dos últimos dígitos serán los céntimos de Euro.
	 */
	protected $_importe;

	/**
	 * @var string $_tipoMoneda Requerido 3
	 */
	protected $_tipoMoneda;

	/**
	 * @var integer $_tipoMoneda Requerido 1 Actualmente siempre será 2
	 */
	protected $_exponente;

	/**
	 * @var string $_url_ok Requerido 500 URL completa. 
	 */
	protected $_url_ok;

	/**
	 * 
	 * @var string $_url_nok Requerido 500 URL completa. 
	 */
	protected $_url_nok;

	/**
	 * @var string $_firma Requerido 256 Es una cadena de caracteres calculada por el comercio.
	 */
	protected $_firma;
	
	/**
	 * @var string $_cifrado Requerido 4 Valor fijo SHA2.
	 */
	protected $_cifrado;

	/**
	 * @var integer $_idioma Opcional 1 Código de idioma.
	 * 
	 * 1.- Español 2.- Catalán 3.- Euskera 4.- Gallego 5.- Valenciano
	 * 6.- Inglés 7.- Francés 8.- Alemán 9.- Portugués 10.- Italiano 
	 * 11.- Sueco 12.- Danés 13.- Ruso 14.- Holandés 15.- Noruego
	 */
	protected $_idioma;

	/**
	 * @var string $_pago_soportado Requerido 3 Valor fijo SSL.
	 */
	protected $_pago_soportado;

	/**
	 * @var string $_descripcion Opcional 1000 Campo reservado para mostrar información extra en la página de pago.
	 */
	protected $_descripcion;
	/**
	 * 
	 * @var string $_pago_elegido Opcional Dependiendo de quien solicite los datos de la tarjeta. Si los solicita el comercio será SSL. Si los solicita el TPV será vacío o no viajará.
	 */
	protected $_pago_elegido;
	
	/**
	 * @var integer $_pan Opcional 19 Nº de tarjeta del cliente. Este campo tendrá contenido sólo en el caso de que la caja haya autorizado al comercio a solicitar este  tipo de datos. En caso contrario dejarlo sin contenido.
	 */
	protected $_pan;

	/**
	 * @var string $_caducidad Opcional 6 Fecha de Caducidad. Formato AAAAMM. Este campo tendrá  contenido sólo en el caso de que la caja haya autorizado al comercio a solicitar este tipo de datos. En caso contrario dejarlo sin contenido.
	 */
	protected $_caducidad;

	/**
	 * @var integer $_cvv2 Opcional CVC2 de la tarjeta. Este campo tendrá contenido sólo en el caso  de que la caja haya autorizado al comercio a solicitar este tipo de datos. En caso contrario dejarlo sin contenido.
	 */
	protected $_cvv2;

	/**
	 * @var string Referencia Opcional 30 Si el comercio está realizando el pago de una compra el campo  viajará sin contenido. Si el comercio está realizando la anulación de una operación, se informará con el valor correspondiente.
	 */
	protected $_referencia;
	
	/**
	 * Pasarela final a la que se conectara
	 * @var string $_urlPasarela
	 */
	protected $_urlPasarela;

	/**
	 * URL para conectarse a la TPV en produccion
	 * @var string
	 */
	protected $_urlPasarelaproduccion;

	/**
	 * URL para conectarse a la TPV en desarrollo
	 * @var string
	 */
	protected $_urlPasareladesarrollo;
	/**
	 * Clave de encriptacion
	 * @var string $_clave_encriptacion Cualquier valor
	 */
	protected $_clave_encriptacion;


	protected $_nameForm;
	protected $_idForm;
	protected $_submit;

	public function __construct()
	{
		$this->_exponente = 2;
		$this->_cifrado = 'SHA2';
		$this->_idioma = 1; //Por defecto español
		$this->_pago_soportado = 'SSL';
		$this->_urlPasarelaproduccion = 'https://pgw.ceca.es/tpvweb/tpv/compra.action';
		$this->_urlPasareladesarrollo = 'https://tpv.ceca.es/tpvweb/tpv/compra.action';
		$this->_urlPasarela = $this->_urlPasareladesarrollo;
		$this->_nameForm = 'form_tpv';
		$this->_idForm = 'id_tpv';
		$this->_setSubmit = '';
		$this->_clave_encriptacion = '';
		$this->_tipoMoneda = "978";
		$this->_terminalID = '00000003';
	}

	/**
	 * Asignar código comercio 
	 * @param integer $merchantid Código identificativo del comercio (Proporcionado por el Comercio).
	 */	
	public function setMerchantID($merchantid='')
	{
		if(strlen(trim($merchantid)) > 0)
		{
			$this->_merchantID = $merchantid;
		}
		else
		{
			throw new \Exception('Falta agregar MerchantID proporcionada por el comercio, Obligatorio');
		}
		
	}

	/**
	 * Asignar código entidad
	 * @param string $acquirerbin Código identificativo de su Caja (Proporcionado por el Comercio).
	 */
	public function setAcquirerBIN($acquirerbin='')
	{
		if(strlen(trim($acquirerbin)) > 0)
		{
			$this->_acquirerBIN = $acquirerbin;
		}
		else
		{
			throw new \Exception('Falta agregar AcquirerBIN proporcionada por el comercio, Obligatorio');
		}
		
	}

	/**
	 * Asignar url_ok
	 * @param string $urlok Es la URL determinada por el comercio a la que Cecabank devolverá el control en el caso de que la  operación finalice correctamente.
	 *                      Esta URL no deberá utilizarse  para actualizar la operación como pagada en el servidor del  comercio.
	 */
	public function setUrlOk($urlok='')
	{
		if(strlen(trim($urlok)) > 0)
		{
			$this->_url_ok = $urlok;
		}
		else
		{
			throw new \Exception('Falta agregar Url Ok de respuesta tras la compra, Obligatorio');
		}
		
	}

	/**
	 * Asignar url_nok
	 * @param string $urlnok Es la URL determinada por el comercio a  la que Cecabank devolverá el control en el caso de que la  operación no pueda realizarse por algún motivo.
	 */
	public function setUrlNok($urlnok='')
	{
		if(strlen(trim($urlnok)) > 0)
		{
			$this->_url_nok = $urlnok;
		}
		else
		{
			throw new \Exception('Falta agregar Url NOk de respuesta cuando se produce un error, Obligatorio');
		}
		
	}

	/**
	 * Asignar número de pedido
	 * @param string $numoperacion Identifica para el comercio la operación, nº de pedido, factura,  albaran, etc.… Puede ser alfanumérico pero están prohibidos los caracteres extraños típicos como ¿,?,%,&,*,etc.
	 */
	public function setNumOperacion($numoperacion='')
	{
		if(strlen(trim($numoperacion)) > 0)
		{
			$this->_num_operacion = $numoperacion;
		}
		else
		{
			throw new \Exception('Falta agregar Numero de operacion (Num. de factura, pedido, etc), Obligatorio');
		}
		
	}

	/**
	 * Asignar importe a pagar
	 * @param string $importe Importe a pagar, se puede especificar con comas y puntos (Ejm. 10,67 / 10.32)
	 */
	public function setImporte($importe='')
	{
		if(strlen(trim($importe)) > 0)
		{
			$importe = $this->priceToSQL($importe);
        
        	// Siempre será un número entero donde los dos últimos dígitos serán los céntimos de Euro.
        	$importe = intval(strval($importe*100));
        	$this->_importe=$importe;
		}
		else
		{
			throw new \Exception('Falta agregar Importe, Obligatorio');
		}
        
    }

    /**
     * Asignar el tipo de moneda
     * @param integer $tipomoneda Es el código ISO-4217 correspondiente a la moneda en la que se  efectúa el pago. Contendrá el valor 978 para Euros.
     */
	public function setTipoMoneda($tipomoneda)
	{
		$this->_tipoMoneda = $tipomoneda;
	}

	/**
	 * Asignar el ID del terminal
	 * @param string $terminalid Código identificativo de un terminal dentro de un comercio. Por defecto es 00000003
	 */
	public function setTerminalID($terminalid='')
	{
		$this->_terminalID = $terminalid;
	}

	/**
	 * Asignar URL de producción
	 * @param string $urlpasarelaproduccion Url del entorno de producción
	 */
	public function setUrlpasarelaproduccion($urlpasarelaproduccion='')
	{
		$this->_urlPasarelaproduccion = $urlpasarelaproduccion;
	}

	/**
	 * Asignar URL de desarrollo
	 * @param string $urlpasareladesarrollo Url del entorno de desarrollo
	 */
	public function setUrlpasareladesarrollo($urlpasareladesarrollo='')
	{
		$this->_urlPasareladesarrollo = $urlpasareladesarrollo;
	}

	/**
	 * Asignar entorno
	 * @param string $entorno Asignar el tipo de entorno que usaremos para comunicarnos con la TPV (por defecto modo desarrollo)
	 */
	public function setEntorno($entorno='desarrollo')
	{
		if(strtolower(trim($entorno)) == 'produccion'){
            //produccion
            $this->_urlPasarela=$this->_urlPasarelaproduccion;
        }
        elseif(strtolower(trim($entorno)) == 'desarrollo'){
            //desarrollo
            $this->_urlPasarela = $this->_urlPasareladesarrollo;
        } 
	}

	/**
	 * Clave de encriptación
	 * @param string $claveencriptacion Utilizada para firmar las llamadas realizadas al TPV. Las claves son distintas en pruebas y en real. (Proporcionado por el Comercio).
	 */
	public function setClaveEncriptacion($claveencriptacion='')
	{
		if(strlen(trim($claveencriptacion)) > 0)
		{
			$this->_clave_encriptacion = $claveencriptacion;
		}
		else
		{
			throw new \Exception('Falta agregar la clave de encriptacion proporcionada por el comercio, Obligatorio');
		}
	}

	/**
	 * Generar la firma
	 * @return string Este método construye la firma con los parámetros anteriormente asignados
	 */
	private function firma(){                
        $lafirma = $this->_clave_encriptacion . $this->_merchantID . $this->_acquirerBIN . $this->_terminalID . $this->_num_operacion . $this->_importe . $this->_tipoMoneda . $this->_exponente . $this->_cifrado . $this->_url_ok . $this->_url_nok;
        if(strlen(trim($lafirma)) > 0){
            // Cálculo del SHA256
            $this->_firma = strtolower(hash('sha256', $lafirma));           
        }
        else{
            throw new Exception('Falta agregar la firma, Obligatorio');
        }
    }

	/**
	* Asignar la descripcion
	* @param string $descripcion Utilizada para mostrar la información descriptiva sobre la compra en el TPV
	*/
	public function setDescripcion($descripcion)
	{
		$this->_descripcion = $descripcion;
	}


	/**
     * Asignar el nombre del formulario
     * @param string nombre Nombre del formulario
     */

    public function setNameform($nombre = 'form_tpv')
    {
        $this->_nameForm = $nombre;
    }

	/**
     * Asignar el id del formulario
     * @param string idform ID del formulario
     */

    public function setIdform($idform = 'id_tpv')
    {
        $this->_idForm = $idform;
    }

	/**
    * Generar boton submit
    * @param string nombre Nombre y ID del botón submit
    * @param string texto Texto que se mostrara en el botón
    */

    public function setSubmit($nombre = 'submitceca',$texto='Enviar')
    {
        if(strlen(trim($nombre))==0)
            throw new Exception('Asigne nombre al boton submit');

        $btnsubmit = '<input type="submit" name="'.$nombre.'" id="'.$nombre.'" value="'.$texto.'" />';
        $this->_submit = $btnsubmit;
    }

 	/**
 	 * Iniciar redirección automática
 	 * @return string Se ejecuta la redicción automática por javascript
 	 */
    public function launchRedirection() {
            echo $this->create_form();
            echo '<script>document.forms["'.$this->_nameForm.'"].submit();</script>';
    }

    /**
     * Creacion del formulario
     * @return string Retorna el formulario para la TPV
     */
	public function create_form(){
		$this->firma();
        $formulario='
        <form action="'.$this->_urlPasarela.'" method="post" id="'.$this->_idForm.'" name="'.$this->_nameForm.'" enctype="application/x-www-form-urlencoded" >
            <input type="hidden" name="MerchantID" value="'.$this->_merchantID.'" />
            <input type="hidden" name="AcquirerBIN" value="'.$this->_acquirerBIN.'" />
            <input type="hidden" name="TerminalID" value="'.$this->_terminalID.'" />
            <input type="hidden" name="URL_OK" value="'.$this->_url_ok.'" />
            <input type="hidden" name="URL_NOK" value="'.$this->_url_nok.'" />
            <input type="hidden" name="Firma" value="'.$this->_firma.'" />
            <input type="hidden" name="Cifrado" value="'.$this->_cifrado.'" />
            <input type="hidden" name="Num_operacion" value="'.$this->_num_operacion.'" />
            <input type="hidden" name="Importe" value="'.$this->_importe.'" />
            <input type="hidden" name="TipoMoneda" value="'.$this->_tipoMoneda.'" />
            <input type="hidden" name="Exponente" value="'.$this->_exponente.'" />
            <input type="hidden" name="Pago_soportado" value="'.$this->_pago_soportado.'" />
            <input type="hidden" name="Descripcion" value="'.$this->_descripcion.'" />
            <input type="hidden" name="Idioma" value="'.$this->_idioma.'" />            
        ';
        	$formulario.=$this->_submit;
        	$formulario.='
        </form>        
        ';
        return $formulario;
    }

    //Utilidades
    //http://stackoverflow.com/a/9111049/444225
    private function priceToSQL($price)
	{
	    $price = preg_replace('/[^0-9\.,]*/i', '', $price);
	    $price = str_replace(',', '.', $price);

	    if(substr($price, -3, 1) == '.')
	    {
	        $price = explode('.', $price);
	        $last = array_pop($price);
	        $price = join($price, '').'.'.$last;
	    }
	    else
	    {
	        $price = str_replace('.', '', $price);
	    }

	    return $price;
	}	

}
