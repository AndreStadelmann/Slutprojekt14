<?php
	require_once 'includes/connect.php';
	require_once 'includes/functions.php';
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
					if ($pdo) {
						foreach ($pdo->query("SELECT * FROM subject GROUP BY name ORDER BY name") as $row) 
						{
							echo "<a href=\"?subject={$row['ID']}\"><li>{$row["name"]}</li></a>";
						}
					}
				?>
			</ul>
		</nav>

		<div class="container">
			<?php
				if (!empty($_GET)) {
					if (!empty($_GET["subject"])) { // Show class if subject is selected

						$subject = getSubjectFromID($_GET['subject'], $pdo);
						echo "<h2>{$subject}</h2>";

						$subject = filter_input(INPUT_GET, "subject", FILTER_VALIDATE_INT);

						$statement = $pdo->prepare("SELECT * FROM class WHERE subject_ID=? ORDER BY name");
						$statement->bindParam(1, $subject);

						if ($statement->execute()) {
							echo "<ul>";
							while($row = $statement->fetch()) {
								echo "<a href=\"?subject={$_GET['subject']}&class={$row['ID']}\"><li> <h4> {$row['name']} </h4> </li></a> ";

								if (!empty($_GET["class"]) && $_GET["class"] == $row['ID'] ) { // If class is selected show parts of the class
									

											// fixa relationer och lägg in matte kapitel i databasen!!!
									$class = filter_input(INPUT_GET, "class", FILTER_VALIDATE_INT);

									$statment2 = $pdo->prepare("SELECT * FROM part WHERE class_ID=? ORDER BY name");
									$statment2->bindParam(1, $class);

									if ($statment2->execute()) {
										echo "<ul id=selectedClass>";
										while ($row2 = $statment2->fetch()) {
											echo "<a href=\"?part={$row2['ID']}\"><li> <h4> {$row2['name']} </h4> </li></a> ";
										}
										echo "</ul>";
									}
								}
							}
							echo "</ul>";
						}
						else {

							echo "FAIL TO DO STATEMENT";
						}
					}
					// Visa alla sammanfattningar som har med kapitlet att göra
					if (!empty($_GET["part"])) {

						$part = getPartFromID($_GET['part'], $pdo);
						echo "<h2>{$part}</h2>";

						$part = filter_input(INPUT_GET, "part", FILTER_VALIDATE_INT);
						$statement = $pdo->prepare("SELECT * FROM summary WHERE part_ID=? ORDER BY name");
						$statement->bindParam(1, $part);

						if ($statement->execute()) {
							echo "<ul>";
							while ($row = $statement->fetch()) {
								echo "<li><a href=\"?summary={$row['ID']}\"> <h4> {$row['name']} </h4> </a></li> ";
							}
							echo "</ul>";
						}
					}

					if (!empty($_GET["summary"])) {
						$summary = filter_input(INPUT_GET, "summary", FILTER_VALIDATE_INT);						
						$statement = $pdo->prepare("SELECT * FROM summary WHERE ID=?");
						$statement->bindParam(1, $summary);

						if ($statement->execute()) {
							$summaryName = $statement->fetch();
							echo "<h2>{$summaryName['name']}</h2>";
						}


						$statement = $pdo->prepare("SELECT * FROM concepts WHERE summary_ID=? ORDER BY concept");
						$statement->bindParam(1, $summary);



						if ($statement->execute()) {
							echo "<ul>";
							while ($row = $statement->fetch()) {
								echo "<p> <li> <h4> {$row['concept']}: </h4> </li> ";
								echo "<li> {$row['explanation']} </li> </p>";
							}
							echo "</ul>";
						}
					}
					

				}
				else {
					echo "<h2>Senaste sammanfattningar</h2>";
					$statement = $pdo->prepare("SELECT * FROM summary ORDER BY date desc");
					if ($statement->execute()) {
						for ($i=0; $i < 5; $i++) { 
							$row = $statement->fetch();
							echo "<li><a href=\"?summary={$row['ID']}\"> <h4> {$row['name']} </h4> </a></li>";
						}
					}
				}

			?>
		</div>



	</body>
</htlm>