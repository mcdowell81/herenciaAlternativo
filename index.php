<?php

include 'api.php';

$api = new Api();

$corleones = $api->createFamily('Corleone');

$AHoldings = [
	MONEYHOLDING => '400',
	GROUNDHOLDING => '200',
	PROPERTYHOLDING => '3'
];

$api->addMemberToFamily($corleones, "A", $AHoldings, null, DateTime::createFromFormat('Y-m-d H:i:s', '1916-06-12 03:15:00'));
$api->addMemberToFamily($corleones, "B", [], "A", DateTime::createFromFormat('Y-m-d H:i:s', '1958-03-12 13:00:00'));
$api->addMemberToFamily($corleones, "C", [], "A", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "D", [], "B", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "E", [], "B", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "F", [], "B", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "G", [], "C", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "H", [], "C", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "I", [], "D", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));
$api->addMemberToFamily($corleones, "J", [], "D", DateTime::createFromFormat('Y-m-d H:i:s', '1981-06-12 03:25:00'));

$now = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));

//$heritageAmountByName = $api->getHeritageByName('Carlo', $corleones, $now);
$heritageAmount = $api->getHeritage($corleones, $now); ?>

<h3>AMOUNT: <?= $heritageAmount ?> €</h3>

<h3>MEMBERS</h3>

<?php
$members = $api->getFamilyMembers($corleones);
foreach ($members as $member) {
	echo "<br> - ";
	echo $member->getName() . "->";
	print_r($member->getHoldings());
}