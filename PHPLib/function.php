<?php

namespace PHPLib {

	/** A greeter class. */
	class Greeter {
		/** A method that outputs hello. */
		public function Greet(){
			echo "PHPTest says \"Hello world!\"";
		}

        public function GetGreet(){
            return "PHPgreeting";
        }
	}
}