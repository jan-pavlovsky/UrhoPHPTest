<?php

include("./App.php");


/** A greeter class. */
class Greeter {

	public function __construct($ll) {
		echo $ll;
	}

	/** A method that outputs hello. */
	public function Greet(){
		echo "PHPLib says \"Hello world!\"";
	}
}
