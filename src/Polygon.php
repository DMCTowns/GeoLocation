<?php
/**
 * Geolocation Polygon class
 * @author Diccon Towns <diccon.towns@gmail.com>
 */

namespace DMCTowns\GeoLocation;

/**
 * Geolocation Polygon
 */
class Polygon{

	/**
	 * Array of points
	 * @var array $_points
	 */
	protected $_points;

	/**
	 * Northernmost point
	 * @var float $_north
	 */
	protected $_north = null;

	/**
	 * Southernmost point
	 * @var float $_south
	 */
	protected $_south = null;

	/**
	 * Westernmost point
	 * @var float $_west
	 */
	protected $_west = null;

	/**
	 * Easternmost point
	 * @var flaot $_east
	 */
	protected $_east = null;

	/**
	 * Constructor
	 * @param mixed $points
	 */
	public function __construct($points = null){
		if($points){
			$this->setPoints($points);
		}
	}

	/**
	 * Sets points
	 * @param mixed $points
	 */
	public function setPoints($points){
		if(is_string($points)){
			$this->_points = array();
			$points = trim($points);
			if(stristr($points, ' ')){// points are separated by space
				$points = explode(',',$points);
				for($i=0; $i<count($points); $i++){
					$point = explode(' ', trim($points[$i]));
					if(!isset($point[1])){
						continue;
					}
					$this->_points[] = new \Reapit\GeoLocation\LatLng($point[0], $point[1]);
					$this->_north = ($this->_north === null) ? $point[0] : max($this->_north, $point[0]);
					$this->_south = ($this->_south === null) ? $point[0] : min($this->_south, $point[0]);
					$this->_east = ($this->_east === null) ? $point[1] : max($this->_east, $point[1]);
					$this->_west = ($this->_west === null) ? $point[1] : min($this->_west, $point[1]);
				}
			}else if(stristr($points, ';')){// points are separated by semi-colon
				$points = trim($points, ';');
				$points = explode(';',$points);
				for($i=0; $i<count($points); $i++){
					$point = explode(',', trim($points[$i]));
					if(!isset($point[1])){
						continue;
					}
					$this->_points[] = new \Reapit\GeoLocation\LatLng($point[0], $point[1]);
					$this->_north = ($this->_north === null) ? $point[0] : max($this->_north, $point[0]);
					$this->_south = ($this->_south === null) ? $point[0] : min($this->_south, $point[0]);
					$this->_east = ($this->_east === null) ? $point[1] : max($this->_east, $point[1]);
					$this->_west = ($this->_west === null) ? $point[1] : min($this->_west, $point[1]);
				}
			}else{// points are separated by commas
				$points = explode(',',$points);
				for($i=0; $i<count($points); $i+=2){
					if(isset($points[$i+1])){
						$this->_points[] = new \Reapit\GeoLocation\LatLng(trim($points[$i]), trim($points[$i+1]));
						$this->_north = ($this->_north === null) ? $point[$i] : max($this->_north, $point[$i]);
						$this->_south = ($this->_south === null) ? $point[$i] : min($this->_south, $point[$i]);
						$this->_east = ($this->_east === null) ? $point[$i+1] : max($this->_east, $point[$i+1]);
						$this->_west = ($this->_west === null) ? $point[$i+1] : min($this->_west, $point[$i+1]);
					}
				}
			}
		}else if(is_array($points)){
			$this->_points = $points;
		}
	}

	/**
	 * Closes polygon if not already done so. Returns true if successfully closed
	 * @return boolean
	 */
	public function close(){
		if(is_array($this->_points) && count($this->_points) > 1){
			$start = $this->_points[0];
			$end = end($this->_points);
			if($start->getLatitude() != $end->getLatitude() || $start->getLongitude() != $end->getLongitude()){
				$this->_points[] = new \Reapit\GeoLocation\LatLng($start->getLatitude(), $start->getLongitude());
			}
			reset($this->_points);
			return true;
		}
		return false;
	}

	/**
	 * Returns points
	 * @param array of \Reapit\GeoLocation\Point
	 */
	public function getPoints(){
		return $this->_points;
	}

	/**
	 * Returns true if polygon contains supplied point
	 * @param \Reapit\GeoLocation\LatLng $point
	 * @return boolean
	 */
	public function contains($point){
		if($points = $this->getPoints()){
		    $j=0;
		    $oddNodes = false;
		    $x = $point->getLongitude();
		    $y = $point->getLatitude();
			$numPoints = count($points);
		    for ($i=0; $i < $numPoints; $i++) {
			  $j++;
			  if ($j == $numPoints) {$j = 0;}
			  if ((($points[$i]->getLatitude() < $y) && ($points[$j]->getLatitude() >= $y))
			  || (($points[$j]->getLatitude() < $y) && ($points[$i]->getLatitude() >= $y))) {
			    if ( $points[$i]->getLongitude() + ($y - $points[$i]->getLatitude())
			    /  ($points[$j]->getLatitude()-$points[$i]->getLatitude())
			    *  ($points[$j]->getLongitude() - $points[$i]->getLongitude())<$x ) {
			      $oddNodes = !$oddNodes;
			    }
			  }
		    }
		    return $oddNodes;
		}
		return false;
	}

	/**
	 * Returns bounding box
	 * @return \Reapit\GeoLocation\LatLngBounds
	 */
	public function getBoundingBox(){
		if($this->_north === null && $this->_points && count($this->_points)){
			foreach($this->_points as $point){
				$this->_north = ($this->_north === null) ? $point->getLatitude() : max($this->_north, $point->getLatitude());
				$this->_south = ($this->_south === null) ? $point->getLatitude() : min($this->_south, $point->getLatitude());
				$this->_east = ($this->_east === null) ? $point->getLongitude() : max($this->_east, $point->getLongitude());
				$this->_west = ($this->_west === null) ? $point->getLongitude() : min($this->_west, $point->getLongitude());
			}
		}
		if($this->_north !== null){
			$ne = new \Reapit\GeoLocation\LatLng($this->_north, $this->_east);
			$sw = new \Reapit\GeoLocation\LatLng($this->_south, $this->_west);
			return new \Reapit\GeoLocation\LatLngBounds($sw, $ne);
		}
		return null;
	}

		/**
	 * Returns points as string
	 * @return string
	 */
	public function __toString(){
		if(is_array($this->_points) && count($this->_points)){
			$points = array();
			foreach($this->_points as $point){
				$points[] = $point->getLatitude() . ' ' . $point->getLongitude();
			}
			return implode(',', $points);
		}
		return '';
	}

}
?>