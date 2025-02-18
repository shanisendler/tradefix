<?php

/**
 * Car Controller
 *
 */

class Car
{

	function getCarByID($car_id)
	{

		global $car;

        echo $car->getBycarID($car_id);
		
	}


	
}
