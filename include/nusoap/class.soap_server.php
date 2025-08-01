<?php

/*

Modification information for LGPL compliance

r57813 - 2010-08-19 10:34:44 -0700 (Thu, 19 Aug 2010) - kjing - Author: John Mertic <jmertic@sugarcrm.com>
    Bug 39085 - When loading the opposite search panel via ajax on the ListViews, call the index action instead of the ListView action to avoid touching pre-MVC code by accident.

r56990 - 2010-06-16 13:05:36 -0700 (Wed, 16 Jun 2010) - kjing - snapshot "Mango" svn branch to a new one for GitHub sync

r56989 - 2010-06-16 13:01:33 -0700 (Wed, 16 Jun 2010) - kjing - defunt "Mango" svn dev branch before github cutover

r55980 - 2010-04-19 13:31:28 -0700 (Mon, 19 Apr 2010) - kjing - create Mango (6.1) based on windex

r51719 - 2009-10-22 10:18:00 -0700 (Thu, 22 Oct 2009) - mitani - Converted to Build 3  tags and updated the build system

r51634 - 2009-10-19 13:32:22 -0700 (Mon, 19 Oct 2009) - mitani - Windex is the branch for Sugar Sales 1.0 development

r51443 - 2009-10-12 13:34:36 -0700 (Mon, 12 Oct 2009) - jmertic - Bug 33332 - Made application PHP 5.3 compliant with E_DEPRECATED warnings on by:
- Changing all ereg function to either preg or simple string based ones
- No more references to magic quotes.
- Change all the session_unregister() functions to just unset() the correct session variable instead.

r50375 - 2009-08-24 18:07:43 -0700 (Mon, 24 Aug 2009) - dwong - branch kobe2 from tokyo r50372

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r13782 - 2006-06-06 10:58:55 -0700 (Tue, 06 Jun 2006) - majed - changes entry point code

r11115 - 2006-01-17 14:54:45 -0800 (Tue, 17 Jan 2006) - majed - add entry point validation

r8846 - 2005-10-31 11:01:12 -0800 (Mon, 31 Oct 2005) - majed - new version of nusoap

r5462 - 2005-05-25 13:50:11 -0700 (Wed, 25 May 2005) - majed - upgraded nusoap to .6.9

r5104 - 2005-05-04 15:33:41 -0700 (Wed, 04 May 2005) - majed - gets rid of HTTP_GET_VARS and what not which has been deprecated

r573 - 2004-09-04 13:03:32 -0700 (Sat, 04 Sep 2004) - sugarclint - undoing copyrights added in inadvertantly.  --clint

r546 - 2004-09-03 11:49:38 -0700 (Fri, 03 Sep 2004) - sugarmsi - removed echo count

r354 - 2004-08-02 23:00:37 -0700 (Mon, 02 Aug 2004) - sugarjacob - Adding Soap


*/


if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}




/**
*
* nusoap_server allows the user to create a SOAP server
* that is capable of receiving messages and returning responses
*
* NOTE: WSDL functionality is experimental
*
* @author   Dietrich Ayala <dietrich@ganx4.com>
* @author   Scott Nichol <snichol@users.sourceforge.net>

* @access   public
*/
class nusoap_server extends nusoap_base
{
    /**
     * HTTP headers of request
     * @var array
     * @access private
     */
    public $headers = array();
    /**
     * HTTP request
     * @var string
     * @access private
     */
    public $request = '';
    /**
     * SOAP headers from request (incomplete namespace resolution; special characters not escaped) (text)
     * @var string
     * @access public
     */
    public $requestHeaders = '';
    /**
     * SOAP Headers from request (parsed)
     * @var mixed
     * @access public
     */
    public $requestHeader = null;
    /**
     * SOAP body request portion (incomplete namespace resolution; special characters not escaped) (text)
     * @var string
     * @access public
     */
    public $document = '';
    /**
     * SOAP payload for request (text)
     * @var string
     * @access public
     */
    public $requestSOAP = '';
    /**
     * requested method namespace URI
     * @var string
     * @access private
     */
    public $methodURI = '';
    /**
     * name of method requested
     * @var string
     * @access private
     */
    public $methodname = '';
    /**
     * method parameters from request
     * @var array
     * @access private
     */
    public $methodparams = array();
    /**
     * SOAP Action from request
     * @var string
     * @access private
     */
    public $SOAPAction = '';
    /**
     * character set encoding of incoming (request) messages
     * @var string
     * @access public
     */
    public $xml_encoding = '';
    /**
     * toggles whether the parser decodes element content w/ utf8_decode()
     * @var boolean
     * @access public
     */
    public $decode_utf8 = true;

    /**
     * HTTP headers of response
     * @var array
     * @access public
     */
    public $outgoing_headers = array();
    /**
     * HTTP response
     * @var string
     * @access private
     */
    public $response = '';
    /**
     * SOAP headers for response (text or array of soapval or associative array)
     * @var mixed
     * @access public
     */
    public $responseHeaders = '';
    /**
     * SOAP payload for response (text)
     * @var string
     * @access private
     */
    public $responseSOAP = '';
    /**
     * method return value to place in response
     * @var mixed
     * @access private
     */
    public $methodreturn = false;
    /**
     * whether $methodreturn is a string of literal XML
     * @var boolean
     * @access public
     */
    public $methodreturnisliteralxml = false;
    /**
     * SOAP fault for response (or false)
     * @var mixed
     * @access private
     */
    public $fault = false;
    /**
     * text indication of result (for debugging)
     * @var string
     * @access private
     */
    public $result = 'successful';

    /**
     * assoc array of operations => opData; operations are added by the register()
     * method or by parsing an external WSDL definition
     * @var array
     * @access private
     */
    public $operations = array();
    /**
     * wsdl instance (if one)
     * @var mixed
     * @access private
     */
    public $wsdl = false;
    /**
     * URL for WSDL (if one)
     * @var mixed
     * @access private
     */
    public $externalWSDLURL = false;
    /**
     * whether to append debug to response as XML comment
     * @var boolean
     * @access public
     */
    public $debug_flag = false;


    /**
    * constructor
    * the optional parameter is a path to a WSDL file that you'd like to bind the server instance to.
    *
    * @param mixed $wsdl file path or URL (string), or wsdl instance (object)
    * @access   public
    */
    public function __construct($wsdl=false)
    {
        parent::__construct();
        // turn on debugging?
        global $debug;
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // global $HTTP_SERVER_VARS;
        global $_SERVER;
        // End STIC Custom

        if (isset($_SERVER)) {
            $this->debug("_SERVER is defined:");
            $this->appendDebug($this->varDump($_SERVER));
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // } elseif (isset($HTTP_SERVER_VARS)) {
        } elseif (isset($_SERVER)) {
        // End STIC Custom
            $this->debug("HTTP_SERVER_VARS is defined:");
            // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
            // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
            // $this->appendDebug($this->varDump($HTTP_SERVER_VARS));
            $this->appendDebug($this->varDump($_SERVER));
            // End STIC Custom
        } else {
            $this->debug("Neither _SERVER nor HTTP_SERVER_VARS is defined.");
        }

        if (isset($debug)) {
            $this->debug("In nusoap_server, set debug_flag=$debug based on global flag");
            $this->debug_flag = $debug;
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $qs = explode('&', $_SERVER['QUERY_STRING']);
            foreach ($qs as $v) {
                if (substr($v, 0, 6) == 'debug=') {
                    $this->debug("In nusoap_server, set debug_flag=" . substr($v, 6) . " based on query string #1");
                    $this->debug_flag = substr($v, 6);
                }
            }
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // } elseif (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
        //     $qs = explode('&', $HTTP_SERVER_VARS['QUERY_STRING']);
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $qs = explode('&', $_SERVER['QUERY_STRING']);
        // End STIC Custom
            foreach ($qs as $v) {
                if (substr($v, 0, 6) == 'debug=') {
                    $this->debug("In nusoap_server, set debug_flag=" . substr($v, 6) . " based on query string #2");
                    $this->debug_flag = substr($v, 6);
                }
            }
        }

        // wsdl
        if ($wsdl) {
            $this->debug("In nusoap_server, WSDL is specified");
            if (is_object($wsdl) && (get_class($wsdl) == 'wsdl')) {
                $this->wsdl = $wsdl;
                $this->externalWSDLURL = $this->wsdl->wsdl;
                $this->debug('Use existing wsdl instance from ' . $this->externalWSDLURL);
            } else {
                $this->debug('Create wsdl from ' . $wsdl);
                $this->wsdl = new wsdl($wsdl);
                $this->externalWSDLURL = $wsdl;
            }
            $this->appendDebug($this->wsdl->getDebug());
            $this->wsdl->clearDebug();
            if ($err = $this->wsdl->getError()) {
                die('WSDL ERROR: '.$err);
            }
        }
    }

    /**
    * processes request and returns response
    *
    * @param    string $data usually is the value of $HTTP_RAW_POST_DATA
    * @access   public
    */
    public function service($data)
    {
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // global $HTTP_SERVER_VARS;
        global $_SERVER;
        // End STIC Custom

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $rm = $_SERVER['REQUEST_METHOD'];
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // } elseif (isset($HTTP_SERVER_VARS['REQUEST_METHOD'])) {
        //     $rm = $HTTP_SERVER_VARS['REQUEST_METHOD'];
        } elseif (isset($_SERVER['REQUEST_METHOD'])) {
            $rm = $_SERVER['REQUEST_METHOD'];
        // End STIC Custom
        } else {
            $rm = '';
        }

        if (isset($_SERVER['QUERY_STRING'])) {
            $qs = $_SERVER['QUERY_STRING'];
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // } elseif (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
        //     $qs = $HTTP_SERVER_VARS['QUERY_STRING'];
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $qs = $_SERVER['QUERY_STRING'];
        // End STIC Custom
        } else {
            $qs = '';
        }
        $this->debug("In service, request method=$rm query string=$qs strlen(\$data)=" . strlen($data));

        if ($rm == 'POST') {
            $this->debug("In service, invoke the request");
            $this->parse_request($data);
            if (! $this->fault) {
                $this->invoke_method();
            }
            if (! $this->fault) {
                $this->serialize_return();
            }
            $this->send_response();
        } elseif (preg_match('/wsdl/', $qs)) {
            $this->debug("In service, this is a request for WSDL");
            if ($this->externalWSDLURL) {
                if (strpos($this->externalWSDLURL, "http://") !== false) { // assume URL
                    $this->debug("In service, re-direct for WSDL");
                    header('Location: '.$this->externalWSDLURL);
                } else { // assume file
                    $this->debug("In service, use file passthru for WSDL");
                    header("Content-Type: text/xml\r\n");
                    $pos = strpos($this->externalWSDLURL, "file://");
                    if ($pos === false) {
                        $filename = $this->externalWSDLURL;
                    } else {
                        $filename = substr($this->externalWSDLURL, $pos + 7);
                    }
                    $fp = fopen($this->externalWSDLURL, 'rb');
                    fpassthru($fp);
                }
            } elseif ($this->wsdl) {
                $this->debug("In service, serialize WSDL");
                header("Content-Type: text/xml; charset=ISO-8859-1\r\n");
                print $this->wsdl->serialize($this->debug_flag);
                if ($this->debug_flag) {
                    $this->debug('wsdl:');
                    $this->appendDebug($this->varDump($this->wsdl));
                    print $this->getDebugAsXMLComment();
                }
            } else {
                $this->debug("In service, there is no WSDL");
                header("Content-Type: text/html; charset=ISO-8859-1\r\n");
                print "This service does not provide WSDL";
            }
        } elseif ($this->wsdl) {
            $this->debug("In service, return Web description");
            print $this->wsdl->webDescription();
        } else {
            $this->debug("In service, no Web description");
            header("Content-Type: text/html; charset=ISO-8859-1\r\n");
            print "This service does not provide a Web description";
        }
    }

    /**
    * parses HTTP request headers.
    *
    * The following fields are set by this function (when successful)
    *
    * headers
    * request
    * xml_encoding
    * SOAPAction
    *
    * @access   private
    */
    public function parse_http_headers()
    {
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // global $HTTP_SERVER_VARS;
        global $_SERVER;
        // End STIC Custom

        $this->request = '';
        $this->SOAPAction = '';
        if (function_exists('getallheaders')) {
            $this->debug("In parse_http_headers, use getallheaders");
            $headers = getallheaders();
            foreach ($headers as $k=>$v) {
                $k = strtolower($k);
                $this->headers[$k] = $v;
                $this->request .= "$k: $v\r\n";
                $this->debug("$k: $v");
            }
            // get SOAPAction header
            if (isset($this->headers['soapaction'])) {
                $this->SOAPAction = str_replace('"', '', $this->headers['soapaction']);
            }
            // get the character encoding of the incoming request
            if (isset($this->headers['content-type']) && strpos($this->headers['content-type'], '=')) {
                $enc = str_replace('"', '', substr(strstr($this->headers["content-type"], '='), 1));
                if (preg_match('/^(ISO-8859-1|US-ASCII|UTF-8)$/i', $enc)) {
                    $this->xml_encoding = strtoupper($enc);
                } else {
                    $this->xml_encoding = 'US-ASCII';
                }
            } else {
                // should be US-ASCII for HTTP 1.0 or ISO-8859-1 for HTTP 1.1
                $this->xml_encoding = 'ISO-8859-1';
            }
        } elseif (isset($_SERVER) && is_array($_SERVER)) {
            $this->debug("In parse_http_headers, use _SERVER");
            foreach ($_SERVER as $k => $v) {
                if (substr($k, 0, 5) == 'HTTP_') {
                    $k = str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($k, 5))));
                } else {
                    $k = str_replace(' ', '-', strtolower(str_replace('_', ' ', $k)));
                }
                if ($k == 'soapaction') {
                    // get SOAPAction header
                    $k = 'SOAPAction';
                    $v = str_replace('"', '', $v);
                    $v = str_replace('\\', '', $v);
                    $this->SOAPAction = $v;
                } elseif ($k == 'content-type') {
                    // get the character encoding of the incoming request
                    if (strpos($v, '=')) {
                        $enc = substr(strstr($v, '='), 1);
                        $enc = str_replace('"', '', $enc);
                        $enc = str_replace('\\', '', $enc);
                        if (preg_match('/^(ISO-8859-1|US-ASCII|UTF-8)$/i', $enc)) {
                            $this->xml_encoding = strtoupper($enc);
                        } else {
                            $this->xml_encoding = 'US-ASCII';
                        }
                    } else {
                        // should be US-ASCII for HTTP 1.0 or ISO-8859-1 for HTTP 1.1
                        $this->xml_encoding = 'ISO-8859-1';
                    }
                }
                $this->headers[$k] = $v;
                $this->request .= "$k: $v\r\n";
                $this->debug("$k: $v");
            }
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // } elseif (is_array($HTTP_SERVER_VARS)) {
        } elseif (is_array($_SERVER)) {
        // End STIC Custom
            $this->debug("In parse_http_headers, use HTTP_SERVER_VARS");
            // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
            // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
            // foreach ($HTTP_SERVER_VARS as $k => $v) {
            foreach ($_SERVER as $k => $v) {
            // End STIC Custom
                if (substr($k, 0, 5) == 'HTTP_') {
                    $k = str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($k, 5))));
                    $k = strtolower(substr($k, 5));
                } else {
                    $k = str_replace(' ', '-', strtolower(str_replace('_', ' ', $k)));
                    $k = strtolower($k);
                }
                if ($k == 'soapaction') {
                    // get SOAPAction header
                    $k = 'SOAPAction';
                    $v = str_replace('"', '', $v);
                    $v = str_replace('\\', '', $v);
                    $this->SOAPAction = $v;
                } elseif ($k == 'content-type') {
                    // get the character encoding of the incoming request
                    if (strpos($v, '=')) {
                        $enc = substr(strstr($v, '='), 1);
                        $enc = str_replace('"', '', $enc);
                        $enc = str_replace('\\', '', $enc);
                        if (preg_match('/^(ISO-8859-1|US-ASCII|UTF-8)$/i', $enc)) {
                            $this->xml_encoding = strtoupper($enc);
                        } else {
                            $this->xml_encoding = 'US-ASCII';
                        }
                    } else {
                        // should be US-ASCII for HTTP 1.0 or ISO-8859-1 for HTTP 1.1
                        $this->xml_encoding = 'ISO-8859-1';
                    }
                }
                $this->headers[$k] = $v;
                $this->request .= "$k: $v\r\n";
                $this->debug("$k: $v");
            }
        } else {
            $this->debug("In parse_http_headers, HTTP headers not accessible");
            $this->setError("HTTP headers not accessible");
        }
    }

    /**
    * parses a request
    *
    * The following fields are set by this function (when successful)
    *
    * headers
    * request
    * xml_encoding
    * SOAPAction
    * request
    * requestSOAP
    * methodURI
    * methodname
    * methodparams
    * requestHeaders
    * document
    *
    * This sets the fault field on error
    *
    * @param    string $data XML string
    * @access   private
    */
    public function parse_request($data='')
    {
        $this->debug('entering parse_request()');
        $this->parse_http_headers();
        $this->debug('got character encoding: '.$this->xml_encoding);
        // uncompress if necessary
        if (isset($this->headers['content-encoding']) && $this->headers['content-encoding'] != '') {
            $this->debug('got content encoding: ' . $this->headers['content-encoding']);
            if ($this->headers['content-encoding'] == 'deflate' || $this->headers['content-encoding'] == 'gzip') {
                // if decoding works, use it. else assume data wasn't gzencoded
                if (function_exists('gzuncompress')) {
                    if ($this->headers['content-encoding'] == 'deflate' && $degzdata = @gzuncompress($data)) {
                        $data = $degzdata;
                    } elseif ($this->headers['content-encoding'] == 'gzip' && $degzdata = gzinflate(substr($data, 10))) {
                        $data = $degzdata;
                    } else {
                        $this->fault('SOAP-ENV:Client', 'Errors occurred when trying to decode the data');
                        return;
                    }
                } else {
                    $this->fault('SOAP-ENV:Client', 'This Server does not support compressed data');
                    return;
                }
            }
        }
        $this->request .= "\r\n".$data;
        $data = $this->parseRequest($this->headers, $data);
        $this->requestSOAP = $data;
        $this->debug('leaving parse_request');
    }

    /**
    * invokes a PHP function for the requested SOAP method
    *
    * The following fields are set by this function (when successful)
    *
    * methodreturn
    *
    * Note that the PHP function that is called may also set the following
    * fields to affect the response sent to the client
    *
    * responseHeaders
    * outgoing_headers
    *
    * This sets the fault field on error
    *
    * @access   private
    */
    public function invoke_method()
    {
        $this->debug('in invoke_method, methodname=' . $this->methodname . ' methodURI=' . $this->methodURI . ' SOAPAction=' . $this->SOAPAction);

        //
        // if you are debugging in this area of the code, your service uses a class to implement methods,
        // you use SOAP RPC, and the client is .NET, please be aware of the following...
        // when the .NET wsdl.exe utility generates a proxy, it will remove the '.' or '..' from the
        // method name.  that is fine for naming the .NET methods.  it is not fine for properly constructing
        // the XML request and reading the XML response.  you need to add the RequestElementName and
        // ResponseElementName to the System.Web.Services.Protocols.SoapRpcMethodAttribute that wsdl.exe
        // generates for the method.  these parameters are used to specify the correct XML element names
        // for .NET to use, i.e. the names with the '.' in them.
        //
        $orig_methodname = $this->methodname;
        if ($this->wsdl) {
            if ($this->opData = $this->wsdl->getOperationData($this->methodname)) {
                $this->debug('in invoke_method, found WSDL operation=' . $this->methodname);
                $this->appendDebug('opData=' . $this->varDump($this->opData));
            } elseif ($this->opData = $this->wsdl->getOperationDataForSoapAction($this->SOAPAction)) {
                // Note: hopefully this case will only be used for doc/lit, since rpc services should have wrapper element
                $this->debug('in invoke_method, found WSDL soapAction=' . $this->SOAPAction . ' for operation=' . $this->opData['name']);
                $this->appendDebug('opData=' . $this->varDump($this->opData));
                $this->methodname = $this->opData['name'];
            } else {
                $this->debug('in invoke_method, no WSDL for operation=' . $this->methodname);
                $this->fault('SOAP-ENV:Client', "Operation '" . $this->methodname . "' is not defined in the WSDL for this service");
                return;
            }
        } else {
            $this->debug('in invoke_method, no WSDL to validate method');
        }

        // if a . is present in $this->methodname, we see if there is a class in scope,
        // which could be referred to. We will also distinguish between two deliminators,
        // to allow methods to be called a the class or an instance
        if (strpos($this->methodname, '..') > 0) {
            $delim = '..';
        } elseif (strpos($this->methodname, '.') > 0) {
            $delim = '.';
        } else {
            $delim = '';
        }
        $this->debug("in invoke_method, delim=$delim");

        $class = '';
        $method = '';
        if (strlen($delim) > 0 && substr_count($this->methodname, $delim) == 1) {
            $try_class = substr($this->methodname, 0, strpos($this->methodname, $delim));
            if (class_exists($try_class)) {
                // get the class and method name
                $class = $try_class;
                $method = substr($this->methodname, strpos($this->methodname, $delim) + strlen($delim));
                $this->debug("in invoke_method, class=$class method=$method delim=$delim");
            } else {
                $this->debug("in invoke_method, class=$try_class not found");
            }
        } else {
            $try_class = '';
            $this->debug("in invoke_method, no class to try");
        }

        // does method exist?
        if ($class == '') {
            if (!function_exists($this->methodname)) {
                $this->debug("in invoke_method, function '$this->methodname' not found!");
                $this->result = 'fault: method not found';
                $this->fault('SOAP-ENV:Client', "method '$this->methodname'('$orig_methodname') not defined in service('$try_class' '$delim')");
                return;
            }
        } else {
            $method_to_compare = (substr(phpversion(), 0, 2) == '4.') ? strtolower($method) : $method;
            if (!in_array($method_to_compare, get_class_methods($class))) {
                $this->debug("in invoke_method, method '$this->methodname' not found in class '$class'!");
                $this->result = 'fault: method not found';
                $this->fault('SOAP-ENV:Client', "method '$this->methodname'/'$method_to_compare'('$orig_methodname') not defined in service/'$class'('$try_class' '$delim')");
                return;
            }
        }

        // evaluate message, getting back parameters
        // verify that request parameters match the method's signature
        if (! $this->verify_method($this->methodname, $this->methodparams)) {
            // debug
            $this->debug('ERROR: request not verified against method signature');
            $this->result = 'fault: request failed validation against method signature';
            // return fault
            $this->fault('SOAP-ENV:Client', "Operation '$this->methodname' not defined in service.");
            return;
        }

        // if there are parameters to pass
        $this->debug('in invoke_method, params:');
        $this->appendDebug($this->varDump($this->methodparams));
        $this->debug("in invoke_method, calling '$this->methodname'");
        if (!function_exists('call_user_func_array')) {
            if ($class == '') {
                $this->debug('in invoke_method, calling function using eval()');
                $funcCall = "\$this->methodreturn = $this->methodname(";
            } else {
                if ($delim == '..') {
                    $this->debug('in invoke_method, calling class method using eval()');
                    $funcCall = "\$this->methodreturn = ".$class."::".$method."(";
                } else {
                    $this->debug('in invoke_method, calling instance method using eval()');
                    // generate unique instance name
                    $instname = "\$inst_".time();
                    $funcCall = $instname." = new ".$class."(); ";
                    $funcCall .= "\$this->methodreturn = ".$instname."->".$method."(";
                }
            }
            if ($this->methodparams) {
                foreach ($this->methodparams as $param) {
                    if (is_array($param) || is_object($param)) {
                        $this->fault('SOAP-ENV:Client', 'NuSOAP does not handle complexType parameters correctly when using eval; call_user_func_array must be available');
                        return;
                    }
                    $funcCall .= "\"$param\",";
                }
                $funcCall = substr($funcCall, 0, -1);
            }
            $funcCall .= ');';
            $this->debug('in invoke_method, function call: '.$funcCall);
            @eval($funcCall);
        } else {
            if ($class == '') {
                $this->debug('in invoke_method, calling function using call_user_func_array()');
                $call_arg = (string)$this->methodname;	// straight assignment changes $this->methodname to lower case after call_user_func_array()
            } elseif ($delim == '..') {
                $this->debug('in invoke_method, calling class method using call_user_func_array()');
                $call_arg = array($class, $method);
            } else {
                $this->debug('in invoke_method, calling instance method using call_user_func_array()');
                $instance = new $class();
                $call_arg = array(&$instance, $method);
            }
            if (is_array($this->methodparams)) {
                $this->methodreturn = call_user_func_array($call_arg, array_values($this->methodparams));
            } else {
                $this->methodreturn = call_user_func_array($call_arg, array());
            }
        }
        $this->debug('in invoke_method, methodreturn:');
        $this->appendDebug($this->varDump($this->methodreturn));
        $this->debug("in invoke_method, called method $this->methodname, received data of type ".gettype($this->methodreturn));
    }

    /**
    * serializes the return value from a PHP function into a full SOAP Envelope
    *
    * The following fields are set by this function (when successful)
    *
    * responseSOAP
    *
    * This sets the fault field on error
    *
    * @access   private
    */
    public function serialize_return()
    {
        $this->debug('Entering serialize_return methodname: ' . $this->methodname . ' methodURI: ' . $this->methodURI);
        // if fault
        if (isset($this->methodreturn) && is_object($this->methodreturn) && ((get_class($this->methodreturn) == 'soap_fault') || (get_class($this->methodreturn) == 'nusoap_fault'))) {
            $this->debug('got a fault object from method');
            $this->fault = $this->methodreturn;
            return;
        } elseif ($this->methodreturnisliteralxml) {
            $return_val = $this->methodreturn;
        // returned value(s)
        } else {
            $this->debug('got a(n) '.gettype($this->methodreturn).' from method');
            $this->debug('serializing return value');
            if ($this->wsdl) {
                if (count($this->opData['output']['parts']) > 1) {
                    $this->debug('more than one output part, so use the method return unchanged');
                    $opParams = $this->methodreturn;
                } elseif (count($this->opData['output']['parts']) == 1) {
                    $this->debug('exactly one output part, so wrap the method return in a simple array');
                    // TODO: verify that it is not already wrapped!
                    //foreach ($this->opData['output']['parts'] as $name => $type) {
                    //	$this->debug('wrap in element named ' . $name);
                    //}
                    $opParams = array($this->methodreturn);
                }
                $return_val = $this->wsdl->serializeRPCParameters($this->methodname, 'output', $opParams);
                $this->appendDebug($this->wsdl->getDebug());
                $this->wsdl->clearDebug();
                if ($errstr = $this->wsdl->getError()) {
                    $this->debug('got wsdl error: '.$errstr);
                    $this->fault('SOAP-ENV:Server', 'unable to serialize result');
                    return;
                }
            } else {
                if (isset($this->methodreturn)) {
                    $return_val = $this->serialize_val($this->methodreturn, 'return');
                } else {
                    $return_val = '';
                    $this->debug('in absence of WSDL, assume void return for backward compatibility');
                }
            }
        }
        $this->debug('return value:');
        $this->appendDebug($this->varDump($return_val));

        $this->debug('serializing response');
        if ($this->wsdl) {
            $this->debug('have WSDL for serialization: style is ' . $this->opData['style']);
            if ($this->opData['style'] == 'rpc') {
                $this->debug('style is rpc for serialization: use is ' . $this->opData['output']['use']);
                if ($this->opData['output']['use'] == 'literal') {
                    // http://www.ws-i.org/Profiles/BasicProfile-1.1-2004-08-24.html R2735 says rpc/literal accessor elements should not be in a namespace
                    if ($this->methodURI) {
                        $payload = '<ns1:'.$this->methodname.'Response xmlns:ns1="'.$this->methodURI.'">'.$return_val.'</ns1:'.$this->methodname."Response>";
                    } else {
                        $payload = '<'.$this->methodname.'Response>'.$return_val.'</'.$this->methodname.'Response>';
                    }
                } else {
                    if ($this->methodURI) {
                        $payload = '<ns1:'.$this->methodname.'Response xmlns:ns1="'.$this->methodURI.'">'.$return_val.'</ns1:'.$this->methodname."Response>";
                    } else {
                        $payload = '<'.$this->methodname.'Response>'.$return_val.'</'.$this->methodname.'Response>';
                    }
                }
            } else {
                $this->debug('style is not rpc for serialization: assume document');
                $payload = $return_val;
            }
        } else {
            $this->debug('do not have WSDL for serialization: assume rpc/encoded');
            $payload = '<ns1:'.$this->methodname.'Response xmlns:ns1="'.$this->methodURI.'">'.$return_val.'</ns1:'.$this->methodname."Response>";
        }
        $this->result = 'successful';
        if ($this->wsdl) {
            //if($this->debug_flag){
            $this->appendDebug($this->wsdl->getDebug());
            //	}
            if (isset($this->opData['output']['encodingStyle'])) {
                $encodingStyle = $this->opData['output']['encodingStyle'];
            } else {
                $encodingStyle = '';
            }
            // Added: In case we use a WSDL, return a serialized env. WITH the usedNamespaces.
            $this->responseSOAP = $this->serializeEnvelope($payload, $this->responseHeaders, $this->wsdl->usedNamespaces, $this->opData['style'], $this->opData['output']['use'], $encodingStyle);
        } else {
            $this->responseSOAP = $this->serializeEnvelope($payload, $this->responseHeaders);
        }
        $this->debug("Leaving serialize_return");
    }

    /**
    * sends an HTTP response
    *
    * The following fields are set by this function (when successful)
    *
    * outgoing_headers
    * response
    *
    * @access   private
    */
    public function send_response()
    {
        $this->debug('Enter send_response');
        if ($this->fault) {
            $payload = $this->fault->serialize();
            $this->outgoing_headers[] = "HTTP/1.0 500 Internal Server Error";
            $this->outgoing_headers[] = "Status: 500 Internal Server Error";
        } else {
            $payload = $this->responseSOAP;
            // Some combinations of PHP+Web server allow the Status
            // to come through as a header.  Since OK is the default
            // just do nothing.
            // $this->outgoing_headers[] = "HTTP/1.0 200 OK";
            // $this->outgoing_headers[] = "Status: 200 OK";
        }
        // add debug data if in debug mode
        if (isset($this->debug_flag) && $this->debug_flag) {
            $payload .= $this->getDebugAsXMLComment();
        }
        $this->outgoing_headers[] = "Server: $this->title Server v$this->version";
        preg_match('/\$Revisio' . 'n: ([^ ]+)/', $this->revision, $rev);
        $this->outgoing_headers[] = "X-SOAP-Server: $this->title/$this->version (".$rev[1].")";
        // Let the Web server decide about this
        //$this->outgoing_headers[] = "Connection: Close\r\n";
        $payload = $this->getHTTPBody($payload);
        $type = $this->getHTTPContentType();
        $charset = $this->getHTTPContentTypeCharset();
        $this->outgoing_headers[] = "Content-Type: $type" . ($charset ? '; charset=' . $charset : '');
        //begin code to compress payload - by John
        // NOTE: there is no way to know whether the Web server will also compress
        // this data.
        if (strlen($payload) > 1024 && isset($this->headers) && isset($this->headers['accept-encoding'])) {
            if (strstr($this->headers['accept-encoding'], 'gzip')) {
                if (function_exists('gzencode')) {
                    if (isset($this->debug_flag) && $this->debug_flag) {
                        $payload .= "<!-- Content being gzipped -->";
                    }
                    $this->outgoing_headers[] = "Content-Encoding: gzip";
                    $payload = gzencode($payload);
                } else {
                    if (isset($this->debug_flag) && $this->debug_flag) {
                        $payload .= "<!-- Content will not be gzipped: no gzencode -->";
                    }
                }
            } elseif (strstr($this->headers['accept-encoding'], 'deflate')) {
                // Note: MSIE requires gzdeflate output (no Zlib header and checksum),
                // instead of gzcompress output,
                // which conflicts with HTTP 1.1 spec (http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.5)
                if (function_exists('gzdeflate')) {
                    if (isset($this->debug_flag) && $this->debug_flag) {
                        $payload .= "<!-- Content being deflated -->";
                    }
                    $this->outgoing_headers[] = "Content-Encoding: deflate";
                    $payload = gzdeflate($payload);
                } else {
                    if (isset($this->debug_flag) && $this->debug_flag) {
                        $payload .= "<!-- Content will not be deflated: no gzcompress -->";
                    }
                }
            }
        }
        //end code
        $this->outgoing_headers[] = "Content-Length: ".strlen($payload);
        reset($this->outgoing_headers);
        foreach ($this->outgoing_headers as $hdr) {
            header($hdr, false);
        }
        print $payload;
        $this->response = implode("\r\n", $this->outgoing_headers)."\r\n\r\n".$payload;
    }

    /**
    * takes the value that was created by parsing the request
    * and compares to the method's signature, if available.
    *
    * @param	string	$operation	The operation to be invoked
    * @param	array	$request	The array of parameter values
    * @return	boolean	Whether the operation was found
    * @access   private
    */
    public function verify_method($operation, $request)
    {
        if (isset($this->wsdl) && is_object($this->wsdl)) {
            if ($this->wsdl->getOperationData($operation)) {
                return true;
            }
        } elseif (isset($this->operations[$operation])) {
            return true;
        }
        return false;
    }

    /**
    * processes SOAP message received from client
    *
    * @param	array	$headers	The HTTP headers
    * @param	string	$data		unprocessed request data from client
    * @return	mixed	value of the message, decoded into a PHP type
    * @access   private
    */
    public function parseRequest($headers, $data)
    {
        $this->debug('Entering parseRequest() for data of length ' . strlen($data) . ' headers:');
        $this->appendDebug($this->varDump($headers));
        if (!isset($headers['content-type'])) {
            $this->setError('Request not of type text/xml (no content-type header)');
            return false;
        }
        if (!strstr($headers['content-type'], 'text/xml')) {
            $this->setError('Request not of type text/xml');
            return false;
        }
        if (strpos($headers['content-type'], '=')) {
            $enc = str_replace('"', '', substr(strstr($headers["content-type"], '='), 1));
            $this->debug('Got response encoding: ' . $enc);
            if (preg_match('/^(ISO-8859-1|US-ASCII|UTF-8)$/i', $enc)) {
                $this->xml_encoding = strtoupper($enc);
            } else {
                $this->xml_encoding = 'US-ASCII';
            }
        } else {
            // should be US-ASCII for HTTP 1.0 or ISO-8859-1 for HTTP 1.1
            $this->xml_encoding = 'ISO-8859-1';
        }
        $this->debug('Use encoding: ' . $this->xml_encoding . ' when creating nusoap_parser');
        // parse response, get soap parser obj
        $parser = new nusoap_parser($data, $this->xml_encoding, '', $this->decode_utf8);
        // parser debug
        $this->debug("parser debug: \n".$parser->getDebug());
        // if fault occurred during message parsing
        if ($err = $parser->getError()) {
            $this->result = 'fault: error in msg parsing: '.$err;
            $this->fault('SOAP-ENV:Client', "error in msg parsing:\n".$err);
        // else successfully parsed request into soapval object
        } else {
            // get/set methodname
            $this->methodURI = $parser->root_struct_namespace;
            $this->methodname = $parser->root_struct_name;
            $this->debug('methodname: '.$this->methodname.' methodURI: '.$this->methodURI);
            $this->debug('calling parser->get_soapbody()');
            $this->methodparams = $parser->get_soapbody();
            // get SOAP headers
            $this->requestHeaders = $parser->getHeaders();
            // get SOAP Header
            $this->requestHeader = $parser->get_soapheader();
            // add document for doclit support
            $this->document = $parser->document;
        }
    }

    /**
    * gets the HTTP body for the current response.
    *
    * @param string $soapmsg The SOAP payload
    * @return string The HTTP body, which includes the SOAP payload
    * @access private
    */
    public function getHTTPBody($soapmsg)
    {
        return $soapmsg;
    }
    
    /**
    * gets the HTTP content type for the current response.
    *
    * Note: getHTTPBody must be called before this.
    *
    * @return string the HTTP content type for the current response.
    * @access private
    */
    public function getHTTPContentType()
    {
        return 'text/xml';
    }
    
    /**
    * gets the HTTP content type charset for the current response.
    * returns false for non-text content types.
    *
    * Note: getHTTPBody must be called before this.
    *
    * @return string the HTTP content type charset for the current response.
    * @access private
    */
    public function getHTTPContentTypeCharset()
    {
        return $this->soap_defencoding;
    }

    /**
    * add a method to the dispatch map (this has been replaced by the register method)
    *
    * @param    string $methodname
    * @param    string $in array of input values
    * @param    string $out array of output values
    * @access   public
    * @deprecated
    */
    public function add_to_map($methodname, $in, $out)
    {
        $this->operations[$methodname] = array('name' => $methodname,'in' => $in,'out' => $out);
    }

    /**
    * register a service function with the server
    *
    * @param    string $name the name of the PHP function, class.method or class..method
    * @param    array $in assoc array of input values: key = param name, value = param type
    * @param    array $out assoc array of output values: key = param name, value = param type
    * @param	mixed $namespace the element namespace for the method or false
    * @param	mixed $soapaction the soapaction for the method or false
    * @param	mixed $style optional (rpc|document) or false Note: when 'document' is specified, parameter and return wrappers are created for you automatically
    * @param	mixed $use optional (encoded|literal) or false
    * @param	string $documentation optional Description to include in WSDL
    * @param	string $encodingStyle optional (usually 'http://schemas.xmlsoap.org/soap/encoding/' for encoded)
    * @access   public
    */
    public function register($name, $in=array(), $out=array(), $namespace=false, $soapaction=false, $style=false, $use=false, $documentation='', $encodingStyle='')
    {
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // global $HTTP_SERVER_VARS;
        global $_SERVER;
        // End STIC Custom

        if ($this->externalWSDLURL) {
            die('You cannot bind to an external WSDL file, and register methods outside of it! Please choose either WSDL or no WSDL.');
        }
        if (! $name) {
            die('You must specify a name when you register an operation');
        }
        if (!is_array($in)) {
            die('You must provide an array for operation inputs');
        }
        if (!is_array($out)) {
            die('You must provide an array for operation outputs');
        }
        if (false == $namespace) {
        }
        if (false == $soapaction) {
            if (isset($_SERVER)) {
                $SERVER_NAME = $_SERVER['SERVER_NAME'];
                $SCRIPT_NAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
            // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
            // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
            //     $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : (isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off');
            // } elseif (isset($HTTP_SERVER_VARS)) {
            //     $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
            //     $SCRIPT_NAME = isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME'];
            //     $HTTPS = isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off';
                $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off');
            } elseif (isset($_SERVER)) {
                $SERVER_NAME = $_SERVER['SERVER_NAME'];
                $SCRIPT_NAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
                $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off';
            // End STIC Custom
            } else {
                $this->setError("Neither _SERVER nor HTTP_SERVER_VARS is available");
            }
            if ($HTTPS == '1' || $HTTPS == 'on') {
                $SCHEME = 'https';
            } else {
                $SCHEME = 'http';
            }
            $soapaction = "$SCHEME://$SERVER_NAME$SCRIPT_NAME/$name";
        }
        if (false == $style) {
            $style = "rpc";
        }
        if (false == $use) {
            $use = "encoded";
        }
        if ($use == 'encoded' && $encodingStyle == '') {
            $encodingStyle = 'http://schemas.xmlsoap.org/soap/encoding/';
        }

        $this->operations[$name] = array(
        'name' => $name,
        'in' => $in,
        'out' => $out,
        'namespace' => $namespace,
        'soapaction' => $soapaction,
        'style' => $style);
        if ($this->wsdl) {
            $this->wsdl->addOperation($name, $in, $out, $namespace, $soapaction, $style, $use, $documentation, $encodingStyle);
        }
        return true;
    }

    /**
    * Specify a fault to be returned to the client.
    * This also acts as a flag to the server that a fault has occurred.
    *
    * @param	string $faultcode
    * @param	string $faultstring
    * @param	string $faultactor
    * @param	string $faultdetail
    * @access   public
    */
    public function fault($faultcode, $faultstring, $faultactor='', $faultdetail='')
    {
        if ($faultdetail == '' && $this->debug_flag) {
            $faultdetail = $this->getDebug();
        }
        $this->fault = new nusoap_fault($faultcode, $faultactor, $faultstring, $faultdetail);
        $this->fault->soap_defencoding = $this->soap_defencoding;
    }

    /**
    * Sets up wsdl object.
    * Acts as a flag to enable internal WSDL generation
    *
    * @param string $serviceName, name of the service
    * @param mixed $namespace optional 'tns' service namespace or false
    * @param mixed $endpoint optional URL of service endpoint or false
    * @param string $style optional (rpc|document) WSDL style (also specified by operation)
    * @param string $transport optional SOAP transport
    * @param mixed $schemaTargetNamespace optional 'types' targetNamespace for service schema or false
    */
    public function configureWSDL($serviceName, $namespace = false, $endpoint = false, $style='rpc', $transport = 'http://schemas.xmlsoap.org/soap/http', $schemaTargetNamespace = false)
    {
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // global $HTTP_SERVER_VARS;
        global $_SERVER;
        // End STIC Custom

        if (isset($_SERVER)) {
            $SERVER_NAME = $_SERVER['SERVER_NAME'];
            $SERVER_PORT = $_SERVER['SERVER_PORT'];
            $SCRIPT_NAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        // STIC Custom 20241113 JBL - Change old var $HTTP_SERVER_VARS to $_SERVER
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        //     $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : (isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off');
        // } elseif (isset($HTTP_SERVER_VARS)) {
        //     $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
        //     $SERVER_PORT = $HTTP_SERVER_VARS['SERVER_PORT'];
        //     $SCRIPT_NAME = isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME'];
        //     $HTTPS = isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off';
            $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off');
        } elseif (isset($_SERVER)) {
            $SERVER_NAME = $_SERVER['SERVER_NAME'];
            $SERVER_PORT = $_SERVER['SERVER_PORT'];
            $SCRIPT_NAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
            $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off';
        // End STIC Custom
        } else {
            $this->setError("Neither _SERVER nor HTTP_SERVER_VARS is available");
        }
        // If server name has port number attached then strip it (else port number gets duplicated in WSDL output) (occurred using lighttpd and FastCGI)
        $colon = strpos($SERVER_NAME, ":");
        if ($colon) {
            $SERVER_NAME = substr($SERVER_NAME, 0, $colon);
        }
        if ($SERVER_PORT == 80) {
            $SERVER_PORT = '';
        } else {
            $SERVER_PORT = ':' . $SERVER_PORT;
        }
        if (false == $namespace) {
            $namespace = "http://$SERVER_NAME/soap/$serviceName";
        }
        
        if (false == $endpoint) {
            if ($HTTPS == '1' || $HTTPS == 'on') {
                $SCHEME = 'https';
            } else {
                $SCHEME = 'http';
            }
            $endpoint = "$SCHEME://$SERVER_NAME$SERVER_PORT$SCRIPT_NAME";
        }
        
        if (false == $schemaTargetNamespace) {
            $schemaTargetNamespace = $namespace;
        }
        
        $this->wsdl = new wsdl;
        $this->wsdl->serviceName = $serviceName;
        $this->wsdl->endpoint = $endpoint;
        $this->wsdl->namespaces['tns'] = $namespace;
        $this->wsdl->namespaces['soap'] = 'http://schemas.xmlsoap.org/wsdl/soap/';
        $this->wsdl->namespaces['wsdl'] = 'http://schemas.xmlsoap.org/wsdl/';
        if ($schemaTargetNamespace != $namespace) {
            $this->wsdl->namespaces['types'] = $schemaTargetNamespace;
        }
        $this->wsdl->schemas[$schemaTargetNamespace][0] = new nusoap_xmlschema('', '', $this->wsdl->namespaces);
        if ($style == 'document') {
            $this->wsdl->schemas[$schemaTargetNamespace][0]->schemaInfo['elementFormDefault'] = 'qualified';
        }
        $this->wsdl->schemas[$schemaTargetNamespace][0]->schemaTargetNamespace = $schemaTargetNamespace;
        $this->wsdl->schemas[$schemaTargetNamespace][0]->imports['http://schemas.xmlsoap.org/soap/encoding/'][0] = array('location' => '', 'loaded' => true);
        $this->wsdl->schemas[$schemaTargetNamespace][0]->imports['http://schemas.xmlsoap.org/wsdl/'][0] = array('location' => '', 'loaded' => true);
        $this->wsdl->bindings[$serviceName.'Binding'] = array(
            'name'=>$serviceName.'Binding',
            'style'=>$style,
            'transport'=>$transport,
            'portType'=>$serviceName.'PortType');
        $this->wsdl->ports[$serviceName.'Port'] = array(
            'binding'=>$serviceName.'Binding',
            'location'=>$endpoint,
            'bindingType'=>'http://schemas.xmlsoap.org/wsdl/soap/');
    }
}

/**
 * Backward compatibility
 */
class soap_server extends nusoap_server
{
}
