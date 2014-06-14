<!doctype html>
<html>
<head>
<title>Directions to Nearest Deposit Location</title>
</head>
<body>

	<h2>Kindergarten to College: Nearest Deposit Location</h2>

	<?php
	
	// code reference: http://www.nmcmahon.co.uk/using-the-google-maps-directions-api-to-calculate-distances/
	
	$origin_address = "901 Mission St";

	if (($_POST["address"] != "") || ($_GET["address"] != "")) {
	    $origin_address = $_GET["address"];
	}
	    
    
	$city_state = "San Francisco, CA";
	
	$destination_address = ["444 Castro St",
				"1399 Post St.",
				"845 Grant Ave",
				"1000 Grant Ave",
				"4638 Mission St",
				"2895 Diamond St",
				"99 Post St",
				"245 Market St",
				"260 California St",
				"590 Market St",
				"451 Montgomery St",
				"701 Irving St",
				"2499 Ocean Ave",
				"2198 Chestnut St",
				"580 Green St",
				"6100 Geary Blvd",
				"2000 Irving St",
				"2400 19th Ave",
				"350 Rhode Island St Ste 140",
				"3296 Sacramento St",
				"4455 Geary Blvd",
				"1900 Noriega St",
				"3146 20th Ave",
				"1801 Van Ness Ave",
				"130 W. Portal",
				];

	$shortest_distance = 0;
	$shortest_distance_text = "";
	$shortest_distance_destination = "";
	
	print('<p><strong>Starting Location:</strong> ' . $origin_address . " " . $city_state . '</p>');

	$all_deposit_locations_text = "";

	for ($i=0; $i<sizeof($destination_address); $i++) {
		$distance_obj = getDistance($origin_address . " " . $city_state, $destination_address[$i] . " " . $city_state, $all_deposit_locations_text);
		
		
		if (!is_null($distance_obj) and ($shortest_distance==0 or $distance_obj->value<$shortest_distance)) {
			$shortest_distance = $distance_obj->value;
			$shortest_distance_text  = $distance_obj->text;
			$shortest_distance_destination = $destination_address[$i] . " " . $city_state;
		}

	}
	

	print('<p><strong>Nearest Deposit Location:</strong> ' . $shortest_distance_destination . ' </p>');
	print('<p><strong>Distance to Nearest Deposit Location:</strong> ' . $shortest_distance_text . ' </p>');

	print('<a href=\'https://mapsengine.google.com/map/edit?mid=zO3bXRpP14Nk.kO9ietuYm-Nk\'>Map of All Deposit Locations</a>');
	
	//print('<hr>'.$all_deposit_locations_text);

	
	
	function getDistance($origin, $destination, $all_deposit_locations_text) {
		// Our parameters
		$params = array(
			'origin'	=> $origin,
			'destination'	=> $destination,
			'mode'          => 'walking', // https://developers.google.com/maps/documentation/directions/#TravelModes
			'units'		=> 'imperial' // display in miles
		);
		
		// Join parameters into URL string
		foreach($params as $var => $val){
			$params_string .= '&' . $var . '=' . urlencode($val);  
		}
		
		// Request URL
		$url = "http://maps.googleapis.com/maps/api/directions/json?".ltrim($params_string, '&');
		
		// Make our API request
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($curl);
		curl_close($curl);
		
		// Parse the JSON response
		$directions = json_decode($return);

		$all_deposit_locations_text = $all_deposit_locations_text . '<p><strong>Deposit Location:</strong> ' . $params['destination'] . '</p>\n';
		$all_deposit_locations_text = $all_deposit_locations_text . '<p><strong>Distance to Deposit Location:</strong> ' . $directions->routes[0]->legs[0]->distance->text . '</p>\n';
		
/*		print('<p><strong>Deposit Location:</strong> ' . $params['destination'] . '</p>');
	
		// Show the total distance
		print('<p><strong>Distance to Deposit Location:</strong> ' . $directions->routes[0]->legs[0]->distance->text . '</p>');
		*/
		// Loop through each step
		/*print('<ol>');
		foreach($directions->routes[0]->legs[0]->steps as $step) {
			print('<li>'.$step->html_instructions.'</li>');
		}
		print('</ol>');*/
		
		sleep(1); // pause to avoid hiccups with Google API. May be able to remove this in the future (e.g. with upgraded access to the Google API)
		
		// output reference: https://developers.google.com/maps/documentation/directions/#DirectionsResponses
		return $directions->routes[0]->legs[0]->distance;
	}

	
	?>
    
</body>
</html>