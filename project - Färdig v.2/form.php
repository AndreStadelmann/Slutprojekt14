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
				<a href="http://localhost/project/form.php" id="createSummary">
					<p><h4>Skapa ny sammanfattning</h4></p>
				</a>
			</div>
		</header>

		<nav>
			<ul>
				<a href="http://localhost/project/index.php?"><li>Hem</li></a>
				<?php
					if ($pdo) {
						foreach ($pdo->query("SELECT * FROM subject GROUP BY name ORDER BY name") as $row) 
						{
							echo "<a href=\"http://localhost/project/index.php?subject={$row['ID']}\"><li>{$row["name"]}</li></a>";
						}
					}
				?>
			</ul>
		</nav>

		<div class="container" id="formContainer">
			<?php
				if (!empty($_GET['done'])) {
					echo 'Formulär sparat!';
				}
			?>

			<form method="post">
				<?php

				 	if (empty($_POST)) {
				 		$step = 0;
				 	}
				 	else {
				 		$step = filter_input(INPUT_POST, "step", FILTER_VALIDATE_INT);
				 	}

				 	if ($step==1 && !empty($_POST["subject"])) {
				 		$subject = filter_input(INPUT_POST, "subject", FILTER_VALIDATE_INT);

						$statement = $pdo->prepare("SELECT * FROM class WHERE subject_ID=? ORDER BY name");
						$statement->bindParam(1, $subject);

						echo "<form method='post'> <input name='step' value='2' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
				 		 <p> <label for='class'> Välj kurs: </label> 
				 		 <select name='class'>  <option selected='selected'> </option>";

						if ($statement->execute()) {
							while($row = $statement->fetch()) {
								echo "<option value=\"{$row['ID']}\"> {$row["name"]} </option>";
							}
						}

						echo " </select> </p><p> <input type='button' value='Back' onClick='history.go(-1);return true;'>
						<input class='button' type='submit' value='Submit'> </p> </form>";
				 	}
				 	elseif ($step==2 && !empty($_POST["class"])) {
				 		$class = filter_input(INPUT_POST, "class", FILTER_VALIDATE_INT);

						$statement = $pdo->prepare("SELECT * FROM part WHERE class_ID=? ORDER BY name");
						$statement->bindParam(1, $class);

						echo "<form method='post'> <input name='step' value='3' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
						<input name='class' value='{$_POST['class']}' type='hidden'>

				 		<p> <label for='part'> Välj delmoment: </label> 
				 		<select name='part'>  <option selected='selected'> </option>";

						if ($statement->execute()) {
							while($row = $statement->fetch()) {
								echo "<option value=\"{$row['ID']}\"> {$row["name"]} </option>";
							}
						}

						echo " </select> </p><p> <input type='button' value='Back' onClick='history.go(-1);return true;'>
						<input class='button' type='submit' value='Submit'> </p> </form>";
				 	}
				 	elseif ($step==3 && !empty($_POST["part"])) {

						echo "<form method='post'> <input name='step' value='4' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
						<input name='class' value='{$_POST['class']}' type='hidden'>
						<input name='part' value='{$_POST['part']}' type='hidden'>

				 		<p> <label for='summary'> Namnge din sammanfattning: </label> 
				 		<input class='inputText' type='text' name='summary'></input>

						</p><p> <input type='button' value='Back' onClick='history.go(-1);return true;'>
						<input class='button' type='submit' value='Submit'> </p> </form>";
				 	}
				 	elseif ($step==4 && !empty($_POST["summary"])) {
				 		echo "<form method='post'>";

				 		for ($i=1; $i > 0;) { 
				 			if (!empty($_POST["concept{$i}"])) {
				 				echo "<input name='concept{$i}' value='" . $_POST['concept' . $i] . "' type='hidden'>
				 				<input name='explanation{$i}' value='" . $_POST['explanation' . $i] . "' type='hidden'>";
				 				$i++;
				 			}
				 			else {
				 				$concepts = $i;
				 				$i = 0;
				 			}
				 		}
						echo "<input name='step' value='4' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
						<input name='class' value='{$_POST['class']}' type='hidden'>
						<input name='part' value='{$_POST['part']}' type='hidden'>
						<input name='summary' value='{$_POST['summary']}' type='hidden'>

				 		<p> <label for='concept{$concepts}'> Begrepp: </label> 
				 		<input class='inputText' type='text' name='concept{$concepts}'></input> </p>

				 		<p id='explanationLabel'> <label for='explanation{$concepts}'> Förklaring: </label>
				 		<textarea name='explanation{$concepts}' maxwidth='13.8em'> </textarea> </p>

						<p> <input class='button4' type='button' value='Back' onClick='history.go(-1);return true;'> 
						<input class='button4' type='submit' value='Submit'> </p> </form>";

						// Förhandsvisningsformuläret

						$subject = getSubjectFromID($_POST['subject'], $pdo);
						$class = getClassFromID($_POST['class'], $pdo);
						$part = getPartFromID($_POST['part'], $pdo);


						echo "<div class='form'> 
						<p> Ämne: {$subject} </p>
						<p> Kurs: {$class} </p>
						<p> Delmoment: {$part} </p>
						<p> Sammanfattnings namn: {$_POST['summary']} </p>";

						for ($i=1; $i > 0;) { 
				 			if (!empty($_POST["concept{$i}"])) {
				 				echo "<p>" . $_POST['concept' . $i] . "</p>";
				 				$i++;
				 			}
				 			else {
				 				$i = 0;
				 			}
				 		}

						echo "</div> <form id='preview' method='post'>";


						for ($i=1; $i > 0;) { 
				 			if (!empty($_POST["concept{$i}"])) {
				 				echo "<input name='concept{$i}' value='" . $_POST['concept' . $i] . "' type='hidden'>
				 				<input name='explanation{$i}' value='" . $_POST['explanation' . $i] . "' type='hidden'>";
				 				$i++;
				 			}
				 			else {
				 				$i = 0;
				 			}
				 		}

						echo "<input name='step' value='5' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
						<input name='class' value='{$_POST['class']}' type='hidden'>
						<input name='part' value='{$_POST['part']}' type='hidden'>
						<input name='summary' value='{$_POST['summary']}' type='hidden'>
						<p> <input class='button' type='submit' value='Förhandsvisa'> </p>
						</form>";
						
				 	}
				 	elseif ($step==5) { // Förhandsvisning och spara
				 		$subject = getSubjectFromID($_POST['subject'], $pdo);
						$class = getClassFromID($_POST['class'], $pdo);
						$part = getPartFromID($_POST['part'], $pdo);


						echo "<div> 
						<p> Ämne: {$subject} </p>
						<p> Kurs: {$class} </p>
						<p> Delmoment: {$part} </p>
						<p> Sammanfattnings namn: {$_POST['summary']} </p> </div>";

						for ($i=1; $i > 0;) { 
				 			if (!empty($_POST["concept{$i}"])) {
				 				echo "<p>" . $_POST['concept' . $i] . "</p>" . 
				 				"<p>" . $_POST['explanation' . $i] . "</p>";
				 				$i++;
				 			}
				 			else {
				 				$i = 0;
				 			}
				 		}

				 		echo "<form method='post'>";

						for ($i=1; $i > 0;) { 
				 			if (!empty($_POST["concept{$i}"])) {
				 				echo "<input name='concept{$i}' value='" . $_POST['concept' . $i] . "' type='hidden'>
				 				<input name='explanation{$i}' value='" . $_POST['explanation' . $i] . "' type='hidden'>";
				 				$i++;
				 			}
				 			else {
				 				$i = 0;
				 			}
				 		}

						echo "<input name='step' value='7' type='hidden'>
						<input name='subject' value='{$_POST['subject']}' type='hidden'>
						<input name='class' value='{$_POST['class']}' type='hidden'>
						<input name='part' value='{$_POST['part']}' type='hidden'>
						<input name='summary' value='{$_POST['summary']}' type='hidden'>
						<p> <input type='button' value='Back' onClick='history.go(-1);return true;'> <input class='button' type='submit' value='Spara'> </p>
						</form> ";

				 	}
				 	elseif ($step==7) {
				 		echo inputSummaryToDB($pdo);
				 		header('location: http://localhost/project/form.php?done=1');
				 	}
				 	else {
				 		$step = 0;
				 		//echo "Något blev fel";
				 	}

					if ($step==0) {
				 		echo "<form method='post'>
				 		<input name='step' value='1' type='hidden' >
				 		<p> <label for='subject'> Välj ämne: </label> 
				 		<select name='subject'>  <option selected='selected'> </option>";

				 		if ($pdo) {
							foreach ($pdo->query("SELECT * FROM subject GROUP BY name ORDER BY name") as $row) 
							{
								echo "<option value=\"{$row['ID']}\"> {$row["name"]} </option>";
							}
						}

				 		echo " </select> </p><p> <input class='button' type='submit' value='Submit'> </p> </form>";
				 	}
				?>
			</form>
		</div>



	</body>
</htlm>