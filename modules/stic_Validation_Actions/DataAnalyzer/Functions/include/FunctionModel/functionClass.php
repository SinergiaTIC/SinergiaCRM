<?php

/**
 * Model class for generation of validation action functions
 */
class functionClass extends DataCheckFunction {

    /**
     * Implements the abstract function doAction
     * Perform the action defined in the function
     * @param $records Set of records on which the validation action is to be applied
     * @param $actionBean stic_Validation_Actions Bean of the action the function is running on.
     * @return boolean It will return true on success and false on error.
     */
    public function doAction($records, stic_Validation_Actions $actionBean) {

        // Do something here if it went well it returns true, if it doesn't return false
        return (1 == 1);
    }

}