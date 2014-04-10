Fellowship One API PHP5 Client Library
=======================================


A PHP client library for the [FellowshipOne](http://developer.fellowshipone.com/) API.

## Prerequisites

   * PHP 5.3 or above
   * PECL OAuth

## Usage
### Installing via [Composer](https://getcomposer.org/)
```bash
$ php composer.phar require fellowshipone/f1api-php
```

### Making API Calls
#### Initialize the client object 2nd Party
```php
//Example settings, not real
$settings = array(
	'key'=>'777',
    'secret'=>'98rest72-d052-435e-hca9-a5fr9api43f5',
    'username'=>'api',
    'password'=>'Pa$$word',
    'baseUrl'=>'https://churchcode.fellowshiponeapi.com',
	);
$f1 = new API($settings);
$f1->login2ndParty($settings['username'], $settings['password']);
```
#### Initialize the client object 3rd Party
```php
//Example settings, not real
$settings = array(
	'key'=>'777',
    'secret'=>'98rest72-d052-435e-hca9-a5fr9api43f5',
    'baseUrl'=>'https://churchcode.fellowshiponeapi.com',
	);

$f1 = new API($settings);
$f1->login3rdParty($callback = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}");
```

#### People Realm calls
```php
// To see a list of all the available methods with info on what they do:
print_r($f1->paths());
// Get a list of denominations
$request = $f1->denominations()->list()->get();

// Example of Search
$request = $f1->people()->search(array(
 		'lastUpdatedDate' => '2014-04-01',
 		'include'=> 'communications',
 	))->get();

// Example of creating a household and person
// First Create a Household
$model = $f1->households_new()->get();
$model['household']['householdName'] = "John Smith";
$model['household']['householdSortName'] = "Smith";
$model['household']['householdFirstName'] = "John";
$f1->households_create($model)->post();

//grab the household id
$householdId = $f1->response['household']['@id'];

// now create a person in the household
$model = $f1->people_new()->get();
$model['person']['@householdID'] = $householdId;
$model['person']['firstName'] = "John";
$model['person']['lastName'] = "Smith";
$model['person']['householdMemberType']['@id'] = "1"; // head
$model['person']['status']['@id'] = "110"; // new from website
$request = $f1->people_create($model)->post();
```

## Contributing
With special thanks to [Dan Boorn](https://github.com/deboorn) for his major contributions to this library.  This is an open source project and we would love involvemnet from our Developer Community.  Hit us up with pull requests.

## More help

   * [API Documentation](http://developer.fellowshipone.com/docs/)
   * [Developer Forum](http://developer.fellowshipone.com/forum/)
