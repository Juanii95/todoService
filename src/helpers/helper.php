<?php 

function validateDate( $date ) {
	
	if( empty( $date ) ) {
		throw new Exception('Empty date'); 
	}
	if (DateTime::createFromFormat('Y-m-d G:i:s', $date) !== FALSE) {
  		// it's a date
		return new DateTime( $date );
	}

	throw new Exception('Invalid date format'); 
}