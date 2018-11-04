<?php
/**
 * LatLngBounds
 * @author Diccon Towns <diccon.towns@gmail.com>
 */

namespace DMCTowns\GeoLocation;

/**
 * Class to handle lat lng bounds
 */
class LatLngBounds{

	/**
	 * South West
	 * @var \Reapit\GeoLocation\LatLng $_southWest
	 */
	protected $_southWest;

	/**
	 * North East
	 * @var \Reapit\GeoLocation\LatLng $_northEast
	 */
	protected $_northEast;

	/**
	 * Constructor
	 * @param \Reapit\GeoLocation\LatLng $sw
	 * @param \Reapit\GeoLocation\LatLng $ne
	 */
	public function __construct($sw=null, $ne=null){
		if($sw && $ne){
			$this->setBounds($sw, $ne);
		}
	}

	/**
	 * Sets bounds
	 * @param \Reapit\GeoLocation\LatLng $sw
	 * @param \Reapit\GeoLocation\LatLng $ne
	 */
	public function setBounds($sw, $ne){
		$this->_southWest = $sw;
		$this->_northEast = $ne;
	}

	/**
	 * Returns South West
	 * @return \Reapit\GeoLocation\LatLng
	 */
	public function getSouthWest(){
		return $this->_southWest;
	}

	/**
	 * Returns North East
	 * @return \Reapit\GeoLocation\LatLng
	 */
	public function getNorthEast(){
		return $this->_northEast;
	}
}
