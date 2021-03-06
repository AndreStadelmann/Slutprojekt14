<?php
	require_once 'includes/connect.php';
	require_once 'includes/functions.php';

	$subjectsArray = array();
	$liHrefID = array();
	$liValue = array();
	$ul2 = array();
	$subUl = '';
	$explanation = "";
	$link = "id='linkTrue'";
	if ($pdo) {
		foreach ($pdo->query("SELECT * FROM subject GROUP BY name ORDER BY name") as $holder) 
		{
			array_push($subjectsArray, $holder);
		}
	}

?>
<?php
				if (!empty($_GET)) {
					if (!empty($_GET["subject"])) { // Show class if subject is selected

						$subject = getSubjectFromID($_GET['subject'], $pdo);
						$rubrik = $subject;

						$subject = filter_input(INPUT_GET, "subject", FILTER_VALIDATE_INT);

						$statement = $pdo->prepare("SELECT * FROM class WHERE subject_ID=? ORDER BY name");
						$statement->bindParam(1, $subject);

						if ($statement->execute()) {
							while($row = $statement->fetch()) {
								array_push($liHrefID, "?subject={$_GET['subject']}&class={$row['ID']}");
								array_push($liValue, $row['name']);

								if (!empty($_GET["class"]) && $_GET["class"] == $row['ID'] ) { // If class is selected show parts of the class
									

											// fixa relationer och lägg in matte kapitel i databasen!!!
									$class = filter_input(INPUT_GET, "class", FILTER_VALIDATE_INT);

									$statment2 = $pdo->prepare("SELECT * FROM part WHERE class_ID=? ORDER BY name");
									$statment2->bindParam(1, $class);

									if ($statment2->execute()) {
										while ($row2 = $statment2->fetch()) {
											//echo ;
											$subUl .= "<a href=\"?part={$row2['ID']}\"><li> <h4> {$row2['name']} </h4> </li></a>";
										}
										array_push($ul2, $subUl);
									}
									
								}
								else {
									array_push($ul2, "");
								}
							}
						}
						else {

							echo "FAIL TO DO STATEMENT";
						}
					}
					// Visa alla sammanfattningar som har med kapitlet att göra
					if (!empty($_GET["part"])) {

						$part = getPartFromID($_GET['part'], $pdo);
						$rubrik = $part;

						$part = filter_input(INPUT_GET, "part", FILTER_VALIDATE_INT);
						$statement = $pdo->prepare("SELECT * FROM summary WHERE part_ID=? ORDER BY name");
						$statement->bindParam(1, $part);

						if ($statement->execute()) {
							while ($row = $statement->fetch()) {
								//echo "<li><a href=\> <h4> {$row['name']} </h4> </a></li> ";
								array_push($liHrefID, "?summary={$row['ID']}");
								array_push($liValue, $row['name']);
							}
						}
					}

					if (!empty($_GET["summary"])) {
						$summary = filter_input(INPUT_GET, "summary", FILTER_VALIDATE_INT);						
						$statement = $pdo->prepare("SELECT * FROM summary WHERE ID=?");
						$statement->bindParam(1, $summary);

						if ($statement->execute()) {
							$summaryName = $statement->fetch();
							$rubrik = $summaryName['name'];
						}
						else {
							$rubrik = "ERROR 404";
						}

						$statement = $pdo->prepare("SELECT * FROM concepts WHERE summary_ID=? ORDER BY concept");
						$statement->bindParam(1, $summary);

						if ($statement->execute()) {
							while ($row = $statement->fetch()) {
								//echo "<p> <li> <h4> {$row['concept']}: </h4> </li> ";
								array_push($liValue, $row['concept']);
								$explanation = "<li> {$row['explanation']} </li> </p>";
							}
						}
						$link = "id=linkFalse";
					}
					

				}
				else {
					$rubrik = "Senaste sammanfattningar";
					$statement = $pdo->prepare("SELECT * FROM summary ORDER BY date desc");
					if ($statement->execute()) {
						for ($i=0; $i < 5; $i++) { 
							$row = $statement->fetch();
							//echo "<li><a href=\"?summary={$row['ID']}\"> <h4> {$row['name']} </h4> </a></li>";
							array_push($liHrefID, "?summary={$row['ID']}");
							array_push($liValue, $row['name']);
						}
					}
				}

			?>






<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="includes/javascript.js"></script>
		<title>Sammanfattningar</title>
	</head>

	<body onLoad="onLoad();">

		<header>

			<div class="header">
				<img id='jim' src="images/jim.png"></img>
				<a href="http://localhost/project/form.php" id="createSummary">
					<p><h4>Skapa ny sammanfattning</h4></p>
				</a>
			</div>
		</header>

		<nav>
			<ul>
				<a href="?"><li>Hem</li></a>
				<?php
					foreach ($subjectsArray as $holder) 
					{
						echo "<a href=\"?subject={$holder['ID']}\"><li>{$holder["name"]}</li></a>";
					}
				?>
			</ul>
		</nav>

		<div class="container">
			<h2>
				<?php
					echo $rubrik;
				?>
			</h2>
			<ul>
				<?php
					while (count($liValue) > 0) {
						$holder = array_shift($liHrefID);
						$holder1 = array_shift($liValue);
						$holder2 = array_shift($ul2);
						echo "<li><a {$link} href='{$holder}'><h4>$holder1</h4></a></li>";
						echo "<ul id='selectedClass'>{$holder2}</ul>";
						echo $explanation;
					}
				?>
			</ul>
		</div>



	</body>
</htlm>