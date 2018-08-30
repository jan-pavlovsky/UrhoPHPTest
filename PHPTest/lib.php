<?php

namespace Library {

	/** A greeter class. */
	class Greeter {
		/** A method that outputs hello. */
		public function Greet(){
			echo "PHPTest says \"Hello world!\"";
		}
		
		public function GetGreet() {
			return "A new changed PHPGreet!!!";
		}
	}

	class CameraControl {
		public $touchSensitivity = 2.0;

		public $ahoj = 0;

		public function rekni() {
			return "aa";
		}
	}
}