<?php
namespace josemmo\Verifactu\Models;

use josemmo\Verifactu\Exceptions\InvalidModelException;
use Symfony\Component\Validator\Validation;

abstract class Model {
    /**
     * Validate this instance
     *
     * @throws InvalidModelException if failed to pass validation
     * 
     * NOTE: Validation disabled due to incompatibility with Symfony Validator 3.4
     * Verifactu-PHP library requires Symfony 7.3+, but SinergiaCRM uses 3.4
     * Validation will be performed on the AEAT server instead
     */
    final public function validate(): void {
        // Validation disabled - incompatible with Symfony 3.4
        // The AEAT server will validate the data when it's submitted
        return;
    }
}
