<?php

require_once('stic_IncorporaView.php');

class Stic_IncorporaViewSyncoptions extends stic_IncorporaView {

	function preDisplay() {
	    parent::preDisplay();
		$this->tpl = "syncoptions.tpl";
	}
}

