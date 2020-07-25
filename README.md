Geohash
-------

Geohash is a php module that provides below functions.
* Encoding geographic location into short string of letters and digits.
* Decoding string of letters and digits to latitude and longitude.
* Identifying the neighbouring geohashes of a provided geohash.

Installation
-------

The easiest way to install PHP Geohash is with [composer](https://getcomposer.org). Find it on [Packagist](https://packagist.org/packages/saikiran/geohash).
~~~
$ composer require saikiran/geohash
~~~

Usage
-----

Encode co-ordinates
~~~
use Sk\Geohash\Geohash;

$g = new Geohash();
echo $g->encode(17.38000000, 78.42000000, 5);
~~~
The result is
~~~
tepfb
~~~

Decode geohash into geographical coordinates
~~~
use Sk\Geohash\Geohash;

$g = new Geohash();
$coordinates = $g->decode("tepfb", 5);
echo "latitude : " . $coordinates[0] . ",  longitude : " . $coordinates[1];
~~~
The result is
~~~
latitude : 17.38,  longitude : 78.42
~~~

Get geohashes of 8 neighbors of a geohash
~~~
use Sk\Geohash\Geohash;

$g = new Geohash();
$hash = $g->encode(25.813646, -80.133761, 7);
$neighbors = $g->getNeighbors($hash);
echo "Hash: $hash\n";
echo "Neighbors: " . json_encode($neighbors) . "\n";
~~~
The result is
~~~
Hash: 
Neighbors: {"North":"dhx4be2","East":"dhx4be1","South":"dhx4bdb","West":"dhx4b7p","NorthEast":"dhx4be3","SouthEast":"dhx4bdc","SouthWest":"dhx4b6z","NorthWest":"dhx4b7r"}
~~~

Running the unit tests
-------
Go to this directory from your project folder
~~~
$ cd vendor/saikiran/geohash
~~~
Then run these two commands
~~~
$ composer install
$ vendor/bin/phpunit tests
~~~

See these resources online:
-------

* http://geohash.org
* http://en.wikipedia.org/wiki/Geohash
