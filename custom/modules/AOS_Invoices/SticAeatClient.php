<?php

use josemmo\Verifactu\Services\AeatClient;

class SticAeatClient extends AeatClient {

    /**
     * Get base URI of web service
     * Override to support different environments for Entity Seal vs Personal Certificates
     *
     * @return string Base URI
     */
    protected function getBaseUri(): string {
        if ($this->isProduction) {
            return 'https://www1.agenciatributaria.gob.es';
        }

        // Development environment logic
        if ($this->isEntitySealCertificate()) {
            $GLOBALS['log']->debug("SticAeatClient: Detected Entity Seal Certificate. Using prewww10.aeat.es");
            return 'https://prewww10.aeat.es';
        }

        $GLOBALS['log']->debug("SticAeatClient: Detected Personal/Representative Certificate. Using prewww1.aeat.es");
        return 'https://prewww1.aeat.es';
    }

    /**
     * Get the endpoint URL that will be used
     * 
     * @return string
     */
    public function getEndpointUrl(): string {
        return $this->getBaseUri();
    }

    /**
     * Detect if the current certificate is an Entity Seal (Sello de Entidad)
     *
     * @return bool
     */
    private function isEntitySealCertificate(): bool {
        if (empty($this->certificatePath) || !file_exists($this->certificatePath)) {
            return false;
        }

        $content = file_get_contents($this->certificatePath);
        
        // Try to parse as X.509 (PEM)
        $cert = openssl_x509_parse($content);
        
        // If failed, try reading as P12 (just in case, though SticUtils sends PEM)
        if (!$cert && !empty($this->certificatePassword)) {
             $certs = [];
             if (openssl_pkcs12_read($content, $certs, $this->certificatePassword)) {
                 $cert = openssl_x509_parse($certs['cert']);
             }
        }

        if (!$cert) {
            $GLOBALS['log']->warn("SticAeatClient: Could not parse certificate to detect type. Content length: " . strlen($content));
            $GLOBALS['log']->warn("SticAeatClient: Cert Header: " . substr($content, 0, 100));
            return false;
        }

        $subject = $cert['subject'];
        
        // Debug
        $GLOBALS['log']->debug("SticAeatClient: Certificate Subject: " . json_encode($subject));

        // Logic to detect Entity Seal (Sello de Entidad)
        
        // 1. Check for "CSE" (Certificado Sello Electrónico) in Common Name
        if (isset($subject['CN']) && stripos($subject['CN'], 'CSE ') !== false) {
             $GLOBALS['log']->debug("SticAeatClient: Detected Seal via CN (CSE)");
             return true;
        }
        
        // 2. Check for "Sello" in Common Name or Organizational Unit
        if (isset($subject['OU']) && stripos($subject['OU'], 'Sello') !== false) {
            $GLOBALS['log']->debug("SticAeatClient: Detected Seal via OU (Sello)");
            return true;
        }
        if (isset($subject['CN']) && stripos($subject['CN'], 'Sello') !== false) {
            $GLOBALS['log']->debug("SticAeatClient: Detected Seal via CN (Sello)");
            return true;
        }

        // 3. Heuristic: Organization present, but NO personal name (Given Name / Surname)
        // Personal certificates (including Representative) usually have GN/SN or givenName/surname
        $hasOrganization = isset($subject['O']) || isset($subject['organizationIdentifier']);
        $hasPersonName = isset($subject['givenName']) || isset($subject['surname']) || isset($subject['GN']) || isset($subject['SN']);

        if ($hasOrganization && !$hasPersonName) {
            $GLOBALS['log']->debug("SticAeatClient: Detected Seal via Heuristic (Org without Person Name)");
            return true;
        }

        return false;
    }
}
