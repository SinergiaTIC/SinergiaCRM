TPV CECA
========

Historia
--------
Esta clase la desarrolle por un amigo que me comento si tenía una pasarela de pago para la **TPV Ceca** (solo había usado [sermepa/redsys](https://github.com/ssheduardo/sermepa)).

Así que me puse a investigar, recopilar información, preguntar en foros. Y gracias a la ayuda desinteresa de muchas personas, obtuve todo necesario para realizarlo.

Esta clase es fácil de integrar en nuestros proyectos y hasta puedes usarlo de base para crear un plugin para otros framework.

Muchas gracias a Miquel Camps, Alberto Molpeceres y más personas que han proporcionado los medios necesarios para finalizar este proyecto.


Introducción
------------
La clase CECA sirve para generar el formulario que se comunicará con la pasarela de pagos que usan utilizan bancos y cajas: [Caja badajoz, Caja Círculo, Caja de Burgos, CajaSur, Caja Granada, Caja de guadalajara, Caja Rioja, Caixa Laietana, Caja Murcia, CajAstur, Sanostra, La Caja de Canarias, CAN (Caja navarra), Caja Canarias, Caja Cantabria, Caja Segovia, CaixaNova, IberCaja, CAM, Caixa Galicia, Caja de Ávila, BBK, Caja Vital Kutxa, Caja de Extremadura, Kutxa, Caja duero, CCM, Cajasol.]

Es una versión que ira creciendo, mejorando y actualizándose.

Si lo usas en algún proyecto y te fue de utilidad estaré más que contento de poder haber aportado un granito de arena.

Requerimientos
--------------
PHP 5.3 o superior.

Créditos
--------
	Clase creada por Eduardo Diaz, Madrid 2013
	Twitter: @eduardo_dx


Como usar la clase
------------------
**Paso 1:** Clonamos la clase

	git clone git@github.com:ssheduardo/ceca.git

**Paso 2:** Incluir la clase

	include_once 'ceca/ceca.php';

**Paso 3:** Configuramos la clase

	use Ubublog\Ceca\Ceca as Tpv;
	try{
		$tpv = new Tpv;
		$tpv->setEntorno();
		$tpv->setMerchantID('xxxxxx');
		$tpv->setClaveEncriptacion('xxxxxx');
		$tpv->setAcquirerBIN('xxxxxx');
		$tpv->setUrlOk('http://www.url.com/respuesta_ok.php');
		$tpv->setUrlNok('http://www.url.com/respuesta_nok.php');
		$tpv->setNumOperacion('A00'.date('His'));
		$tpv->setImporte('43,81');
		$tpv->setSubmit();
		$form = $tpv->create_form();
	}
	catch (Exception $e){
		echo $e->getMessage();
		exit();
	}
	echo $form;

	//xxxxx -> reemplezar por los parámetros proporcionados por el banco

#####Opcional

	//Asignar nombre a name del formulario
	$tpv->setNameform('nombre_formulario');	

	//Asignar nombre a id del formulario
	$tpv->setIdform('id_formulario');	

	//Generar el input submit (si en caso no se usa javascript u otro)
	$tpv->setSubmit('nombre_submit','texto_del_boton');


#####Generamos el formulario

	//En el ejemplo anterior lo hemos usado
	$formulario = $tpv->create_form();

Con esto generamos el form para la comunicación con la pasarela de pagos.
Solo queda agregar un `input submit personalizado` o por medio de `javascript` para realizar el submit.

#####Redirección automática

	//Incluyo este método de sermepa a esta clase, gracias a jaumecornado (github)
	Podemos forzar la redirección sin pasar por el método create_form()
	$tpv->launchRedirection(); 
	
	[Esto método llamaría a create_form y lanzaría el submit por javacript, no hace falta agregar el método setSubmit()]

>**Nota:**
	Por defecto se conecta por la pasarela de pruebas, para cambiar a un entorno real usar el método: **setEntorno('produccion')**.

