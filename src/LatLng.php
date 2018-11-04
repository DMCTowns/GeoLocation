<?php
/**
 * Geolocation LatLng class
 * @author Diccon Towns <diccon.towns@gmail.com>
 */

namespace DMCTowns\GeoLocation;

/**
 * Geolocation point
 */
class LatLng{

	/**
	 * Point's latitude
	 * @var float value in degrees
	 */
	public $lat;

	/**
	 * Point's longitude
	 * @var float value in degrees
	 */
	public $lng;

	/**
	 * Constructor
	 * @param float $lat
	 * @param float $lng
	 */
	public function __construct($lat=null,$lng=null){
		$this->lat = $lat;
		$this->lng = $lng;
	}

	/**
	 * Returns point's latitude
	 * @return float
	 */
	public function getLatitude(){
		return $this->lat;
	}

	/**
	 * Returns point's longitude
	 * @return float
	 */
	public function getLongitude(){
		return $this->lng;
	}


	/**
	 * Returns distance from supplied argument(s)
	 * @param LatLng $latLng
	 * @param string $unit (default m for miles)
	 * @return float
	 */
	public function getDistance($latLng, $unit='m'){

		$lat2 = $latLng->getLatitude();
		$lng2 = $latLng->getLongitude();
		// used internally, this function actually performs that calculation to
		// determine the mileage between 2 points defined by lattitude and
		// longitude coordinates.  This calculation is based on the code found
		// at http://www.cryptnet.net/fsp/zipdy/

		// Convert lattitude/longitude (degrees) to radians for calculations
		$lat1 = deg2rad($this->lat);
		$lng1 = deg2rad($this->lng);
		$lat2 = deg2rad($lat2);
		$lng2 = deg2rad($lng2);

		// Find the deltas
		$delta_lat = $lat2 - $lat1;
		$delta_lon = $lng2 - $lng1;

		// Find the Great Circle distance
		$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
		$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

		return ($unit == "k" || $unit == "km") ? $distance*1.60934 : $distance;

	}

	/**
	 * Moves point by distance and returns new point
	 * @param float $distance in miles, kilometres or nautical miles
	 * @param float $direction in degrees
	 * @param string $unit
	 * @return LatLng with new coordinates
	 *
	 * Thanks to: http://williams.best.vwh.net/avform.htm
	 */
	public function move($distance,$direction,$unit="m"){

		// convert distance to nautical miles

		switch($unit){
			case "m":
			case "miles":
				$distance *= 0.868976242;
				break;
			case "k":
			case "km":
			case "kilometres":
			case "kilometers":
				$distance *= 0.539956803;
				break;
		}

		// convert distance to radians

		$d = (pi()/(180*60))*$distance;

		// convert direction to radians

		$tc = deg2rad($direction);

		// convert start point to radians

		$lat1 = deg2rad($this->lat);
		$lng1 = deg2rad($this->lng);

		// calculations

		$lat = asin(sin($lat1)*cos($d)+cos($lat1)*sin($d)*cos($tc));
 		$dlng = atan2(sin($tc)*sin($d)*cos($lat1), cos($d)-sin($lat1)*sin($lat));
		$lng = fmod(($lng1+$dlng+pi()), (2*pi())) - pi();

		// convert back to degrees
		$lat = rad2deg($lat);
		$lng = rad2deg($lng);

		return new LatLng($lat,$lng);

	}

}
?>