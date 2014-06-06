<?php

function getSubjectFromID($ID, $pdo) {
	$statement = $pdo->prepare("SELECT name FROM subject WHERE ID=?");
	$statement->bindParam(1, $ID);
	if ($statement->execute()) {
		if ($row = $statement->fetch()) {
			return $row['name'];
		}
	}
}
function getClassFromID($ID, $pdo) {
	$statement = $pdo->prepare("SELECT name FROM class WHERE ID=?");
	$statement->bindParam(1, $ID);
	if ($statement->execute()) {
		if ($row = $statement->fetch()) {
			return $row['name'];
		}
	}
}
function getPartFromID($ID, $pdo) {
	$statement = $pdo->prepare("SELECT name FROM part WHERE ID=?");
	$statement->bindParam(1, $ID);
	if ($statement->execute()) {
		if ($row = $statement->fetch()) {
			return $row['name'];
		}
	}
}
function inputSummaryToDB($pdo) {
	$summaryName = htmlentities($_POST["summary"], ENT_QUOTES, 'UTF-8');
	$part = filter_input(INPUT_POST, "part", FILTER_VALIDATE_INT);
	$conceptArr = array();
	$explanationArr = array();
	for ($i=1; $i > 0;) {
		if (!empty($_POST["concept{$i}"])) {

			$concept = htmlentities($_POST["concept{$i}"], ENT_QUOTES, 'UTF-8');
			array_push($conceptArr, $concept);
			$explanation = htmlentities($_POST["explanation{$i}"], ENT_QUOTES, 'UTF-8');
			array_push($explanationArr, $explanation);

			$i++;
		}
		else {
			$i = 0;
		}
	}

	$statement = $pdo->prepare("INSERT INTO summary (name, part_ID, date) VALUES (?, ?, NOW())");
	$statement->bindParam(1, $summaryName);
	$statement->bindParam(2, $part);
	$statement->execute();

	$statement = $pdo->prepare("SELECT ID FROM summary WHERE name=? ORDER BY ID");
	$statement->bindParam(1, $summaryName);
	$statement->execute();

	$all_results = $statement->fetchAll();
	$lenght = count($all_results);
	$last_result = $all_results[$lenght - 1];

	foreach ($conceptArr as $concept) {
		$exRow = array_shift($explanationArr);
		$statement = $pdo->prepare("INSERT INTO concepts (concept, explanation, summary_id) VALUES (?, ?, ?)");
		$statement->bindParam(1, $concept);
		$statement->bindParam(2, $exRow);
		$statement->bindParam(3, $last_result['ID']);
		$statement->execute();
	}

}


















?>