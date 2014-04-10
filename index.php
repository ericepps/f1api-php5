<?php 

namespace F1;
session_start();
require_once('src/fellowshipone/api.php');

/**
 * Add your settings here.  Key and secret found in Fellowship One Admin->Application Keys
 * Make sure the portal user account is linked to a person record in F1
 * Some churches create a person record called "API User" and link a portal account to this record for dedicated api usage.
 *
 */
$settings = array(
	'key'=>'',
	'secret'=>'',
	'username'=>'',
	'password'=>'',
	'baseUrl'=>'https://mychurchcode.fellowshiponeapi.com',
	);
 
// Instantiate the API class
$f1 = new API($settings);
// Login to the API with credentials
$f1->login2ndParty($settings['username'], $settings['password']);

echo "<pre>";

// To see your access token..
//print_r($_SESSION);

// To see a list of all the available methods with info on what they do:
// print_r($f1->paths());

// Example of Search
$r = $f1->people()->search(array(
		'lastUpdatedDate' => '2014-03-01',
		'include'=> 'communications',
	))->get();
print_r($r);

// See Readme for other examples.

?>