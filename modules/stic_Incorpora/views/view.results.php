<?php

require_once('stic_IncorporaView.php');

class Stic_IncorporaViewResults extends stic_IncorporaView {

	function preDisplay() {
	    parent::preDisplay();
	    $this->tpl = "results.tpl";
	}
}

