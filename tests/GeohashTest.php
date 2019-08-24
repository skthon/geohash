<?php

use PHPUnit\Framework\TestCase;
use Sk\Geohash\Geohash;
 
class GeohashTest extends TestCase
{
    /**
     * @dataProvider encodeProvider
     */
    public function testEncode($lat, $lon, $hash, $l)
    {
        $geohash =  new Geohash();
        $actual = $geohash->encode($lat, $lon, $l);
        $this->assertSame($hash, $actual);
    }

    public function encodeProvider()
    {
        return array(
            array(17.57812500, 78.04687500, "tep",  3),
            array(17.31445313, 78.57421875, "tepf", 4),
            array(17.38037109, 78.42041016,  "tepfb", 5),
            array(17.41058350, 78.46984863,  "tepg19", 6),
            array(17.41813660, 78.47328186,  "tepg1dy", 7),
            array(17.41822243, 78.47311020,  "tepg1dyk", 8),
            array(17.38502741, 78.48673582,  "tepffhb71", 9),
            array(17.38500863, 78.48670900,  "tepffhb70b", 10),
            array(17.38503210, 78.48672040,  "tepffhb71hu", 11),
            array(17.38503202, 78.48672057,  "tepffhb71hue", 12)
        );
    }

    /**
     * @dataProvider decodeProvider
     */
    public function testDecode($lat, $lon, $hash)
    {
        $geohash = new Geohash();
        $actual = $geohash->decode($hash);
        $this->assertEquals($actual[0], $lat);
        $this->assertEquals($actual[1], $lon);
    }

    public function decodeProvider()
    {
        return array(
            array(17.60000000, 78.00000000, "tep"),
            array(17.31000000, 78.57000000, "tepf"),
            array(17.38000000, 78.42000000, "tepfb"),
            array(17.41060000, 78.46980000, "tepg19"),
            array(17.41608000, 78.46985000, "tepg1dy"),
            array(17.41813700, 78.47328200, "tepg1dyk"),
            array(17.38502740, 78.48673580, "tepffhb71"),
            array(17.38500863, 78.48670900, "tepffhb70b"),
            array(17.385032102, 78.486720398, "tepffhb71hu"),
            array(17.3850320186, 78.48672056569, "tepffhb71hue")
        );
    }

    public function testGetNeighbors() {
        $geohash = new Geohash();
        list($lat1, $lon1) = [25.813646, -80.133761];
        $hash = $geohash->encode($lat1, $lon1, 7);
        $neighbors = $geohash->getNeighbors($hash);
        $this->assertEquals('dhx4be0', $hash);
        $this->assertEquals([
            'North' => 'dhx4be2',
            'East'=> 'dhx4be1',
            'South' => 'dhx4bdb',
            'West' => 'dhx4b7p',
            'NorthEast' => 'dhx4be3',
            'SouthEast' => 'dhx4bdc',
            'SouthWest' => 'dhx4b6z',
            'NorthWest' => 'dhx4b7r',
        ], $neighbors);
    }
}