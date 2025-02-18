<?php

/**
 * Car Model 
 */

class CarModel
{
	

	function getBycarID($car_id)
	{
		
		$wsdl = 'https://dgamim.galgalim.co.il/GetCars.asmx?WSDL&v=4';
		//$client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors
		 
		$options = array(
          'trace' => 1,  // For debugging purposes
          'exceptions' => true,
          'stream_context' => stream_context_create(array(
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
          )
         ))
        );

       $client = new SoapClient($wsdl, $options);

		// web service input param
		$request_param = array(
			'userName' => 'dgamim',
			'password' => '123',
			'CarID' => $car_id,
		);

		try {
          $responce_param = $client->GetCarByID($request_param);
          return json_encode($responce_param);
        } catch (SoapFault $e) {
          //return "<h2>SOAP Fault</h2>";
          return $e->getMessage(); // Output the SOAP fault message for debugging.
        } catch (Exception $e) {
          //echo "<h2>Exception Error</h2>";
          return $e->getMessage(); // Output the general exception message for debugging.
        }
		
	}
	
	


}
