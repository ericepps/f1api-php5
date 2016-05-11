<?php
namespace F1;
session_start();
require_once('../src/fellowshipone/api.php');
//ini_set('display_errors','1');
$clean['isSubmitted'] = 'N';

foreach($_POST as $key=>$value) {
    if (is_array($value)) {
        foreach($value as $key2=>$value2) {
            $clean[$key][$key2]=htmlentities($value2);
        }
    } else {
        $clean[$key]=htmlentities($value);
    }
}
function postByEnvNo($settings, $envNo, $amount, $postDate, $contType, $fund) {
	$f1 = new API($settings);
	$f1->login2ndParty($settings['username'], $settings['password']);
	$request = $f1->people()->search(array('memberEnvNo' => $envNo,))->get();
	$householdID = $request['results']['person'][0]['@householdID'];
	$model = $f1->contributionreceipts_new()->get();
	$model['contributionReceipt']['fund']['@id'] = $fund;
	$model['contributionReceipt']['household']['@id'] = $householdID;
	$model['contributionReceipt']['receivedDate'] = $postDate;
	$model['contributionReceipt']['amount'] = $amount;
	$model['contributionReceipt']['contributionType']['@id'] = $contType;
	$create = $f1->contributionreceipts_create($model)->post();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Contributions by Envelope Number</title>
</head>

<body>

<?php


if($clean['isSubmitted'] == 'Y') {

	$settings = array(
		'key'=>'{api key}',
		'secret'=>'{api secret}',
		'username'=>'{f1 user}',
		'password'=>'{f1 user password}',
		'baseUrl'=>'https://{chruchcode/churchcode.staging}.fellowshiponeapi.com',
		);
	
	$fund = $clean['fundID'];
	$contType = $clean['contTypeID'];
	$postDate = $clean['postDate'] . 'T00:00:00';
	
	ini_set('auto_detect_line_endings',true);
	$filename = $_FILES['csvFile']['tmp_name'];
	$handle = fopen("$filename", "r");
	$totalAmt = 0;
	echo '<table><thead><tr><th>Env</th><th>Amt</th></tr></thead><tbody>';
	while (($data = fgetcsv($handle, 100, ",")) !== false) {
		postByEnvNo($settings, $data[0], $data[1], $postDate, $contType, $fund);
		$totalAmt = $totalAmt + $data[1];
		echo '<tr><td>' . $data[0] . '</td><td>' . $data[1] . '</td></tr>';
	}
	echo '</tbody><tfoot><tr><th>Total</th><td>'.$totalAmt.'</td></tr></tfoot></table>';
	fclose($handle);
}

?>
<form action="#" enctype="multipart/form-data" method="post">
	<input type="hidden" name="isSubmitted" value="Y" />
	<ol>
    <li><label for="postDate">Post Date: <input type="date" value="" id="postDate" name="postDate" /></label></li>
    <li><label for="fundID">Fund: <select id="fundID" name="fundID"><option value="176146">Tithe / General Fund</option></select></label></li>
    <li><label for="contTypeID">Fund: <select id="contTypeID" name="contTypeID"><option value="2">Cash</option></select></label></li>
    <li><label for="csvFile">CSV File: <input type="file" value="" id="csvFile" name="csvFile" /></label><br>
<small>(column 1 - envelope number, column 2 amound, no header row)</small></li>
    </ol>
    <p><input type="submit" value=" Upload and Process "/></p>
</form>
<script type="text/javascript">
document.getElementById('postDate').valueAsDate = new Date();

</script>
</body>
</html>