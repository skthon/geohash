<?php

namespace ImSaikiran\Geohash;

/**
 * @author  SaikiranCh <saikiranchavan@gmail.com>
 * @class   Geohash
 * @description  Algorithm to encode geographic coordinates to a string of letters and digits
 */
class Geohash
{
    /**
     * Used for decoding the hash from base32
     */
    protected $base32Mapping = "0123456789bcdefghjkmnpqrstuvwxyz";

    /**
     * Decodes geohash from base 32
     * @param  string  Geohash
     * @return string  Binary String
     */
     public function characterMapping($geohash) {
        $geohashArray = array();

        for ($i=0; $i < strlen($geohash); $i++) {
            $geohashArray[] = $geohash[$i];
        }

        $r = "";
        foreach($geohashArray as $g) {
            if (($position = stripos($this->base32Mapping, $g)) !== FALSE) {
                $r .= str_pad(decbin($position), 5, "0", STR_PAD_LEFT);
            } else {
                $r .= "00000";  
            }
        }
        return $r;
     }

    /**
     * Encode the latitude and longitude into a hashed string
     * @param  float   Latitude
     * @param  float   longitude
     * @param  int     GeohashLength
     * @return string  Hashed string obtained from the coordinates
     */
    public function encode($latitude, $Longitude, $geohashLength = 5) {
        // Get latitude and longitude bits length from $geohashLength
        if ($geohashLength%2 == 0) {
            $latBitsLength = $lonBitsLength = ($geohashLength/2) * 5;
        } else {
            $latBitsLength = (ceil($geohashLength/2)*5) - 3;
            $lonBitsLength = $latBitsLength + 1;
        }

        // Get the latitude and longitude binary bits
        $binaryString = "";
        $latbits = $this->getBits($latitude, -90, 90, $latBitsLength);
        $lonbits = $this->getBits($Longitude, -180, 180, $lonBitsLength);
        $binaryLength = strlen($latbits) + strlen($lonbits);

        // Combine the lat and lon bits and get the binaryString
        for ($i=1 ; $i < $binaryLength+1; $i++) {
            if ($i%2 == 0) {
                $pos = (int)($i-2)/2;
                $binaryString .= $latbits[$pos];
            } else {
                $pos = (int)floor($i/2);
                $binaryString .= $lonbits[$pos];
            }
        }

        // Convert the binary to hash
        $hash = "";
        for ($i=0; $i< strlen($binaryString); $i+=5) {
            $n = bindec(substr($binaryString,$i,5));
            $hash = $hash.$this->base32Mapping[$n];
        }
        return $hash;
    }

    /**
     * Decode the Geohash into geographic coordinates
     * @param   string  $hash
     * @param   double  Percentage error
     * @return  mixed   Array of Latitude and Longitude
     */
    public function decode($hash, $error = false) {
        $hashlength = strlen($hash);
        $latlonbits = base_convert($hash, 32, 2);
        $binaryLength = strlen($latlonbits);
        $latbits = "";
        $lonbits = "";

        $latlonbits = $this->characterMapping($hash);

        // Even bits take latitude Code
        // Odd bits take longitude Code
        for ($i = 0; $i < $binaryLength; $i++) {
            ($i % 2 == 0) ? ($lonbits .= $latlonbits[$i]) : ($latbits .= $latlonbits[$i]);
        }

        // Get the Coordinates
        $latitude = $this->getCoordinate(-90, 90, $latbits);
        $longitude = $this->getCoordinate(-180, 180, $lonbits);
        return array($latitude, $longitude);
    }

    /**
     * Get the Geographic Coordinate from the binaryString
     * @param  float   $min
     * @param  float   $max
     * @param  string  $binaryString
     * @return float   $coordinate   
     */
    public function getCoordinate($min, $max, $binaryString) {
        $error = 0;
        for ($i=0; $i < strlen($binaryString); $i++) { 
            $mid = ($min + $max)/2 ;
            if ($binaryString[$i] == 1){
                $min = $mid ;
            } elseif ($binaryString[$i] == 0) {
                $max = $mid;
            }
            $value = ($min + $max)/2;
            $error = $value - $error;
        }
        return $value;
    }

    /**
     * Convert coordinate into binary string according to required bits length
     * @param  float   Coordinate
     * @param  int     minimum value
     * @param  int     maximum value
     * @param  int     bitslength
     * @return string  binary string for the given coordinate
     */
    public function getBits($coordinate, $min, $max, $bitsLength) {
        $binaryString = "";
        $i = 0;
        while ($bitsLength > $i) {
            $mid = ($min+$max)/2;
            if ($coordinate > $mid) {
                $binaryString .= "1";
                $min = $mid;
            } else {
                $binaryString .= "0";
                $max = $mid;
            }
            $i++;
        }
        return $binaryString;
    }
}
