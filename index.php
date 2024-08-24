<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Conti</title>

	<link href="style.css" rel="stylesheet">
</head>

<body>
	<?php
	include_once("db.php");
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>

	<form method="post" id="form">
		<h2>Pagante</h2>
		<select id="pagante" name="pagante" required>
			<?php
			$res = $db->query("SELECT * FROM nomi");
			while ($row = $res->fetch()) {
				echo "<option value='$row[id]'>$row[nome]</option>";
			}
			?>
		</select>
		<br>
		<h2>Soggetti</h2>
		<?php
		$res = $db->query("SELECT * FROM nomi");
		if ($res->rowCount() > 2)
			echo "<button type='button' id='btnAll'>Tutti</button><br/><br/>";
		while ($row = $res->fetch()) {
			echo "<input type='checkbox' name='soggetto_$row[id]' id='soggetto_$row[id]'><label for='soggetto_$row[id]'>$row[nome]</label><br/>";
		}
		?>
		<br><br>
		<label for="importo">Importo (€)</label>
		<input type="number" id="importo" name="importo" step="0.01" min="0" value="0.0" required>
		<br><br>

		<label for="causale">Causale</label>
		<input type="text" id="causale" name="causale" />
		<br><br>

		<button type="submit">Salva</button>
	</form>

	<br>

	<a href="riepilogoTransazioni.php">
		<h2>Riepilogo Transazioni</h2>
	</a><a href="riepilogoConti.php">
		<h2>Riepilogo Conti</h2>
	</a>
</body>
<script>
	$(document).ready(function() {
		$("#btnAll").click(function() {
			$("input[type=checkbox]").prop("checked", "true")
		})

		$("#form").submit(function(e) {
			e.preventDefault();

			var inputs = $(this).find("input, select, button, textarea");
			var serializedData = $(this).serialize();
			inputs.prop("disabled", true);

			$.ajax({
				type: 'POST',
				url: "r_salvaTransazione.php",
				data: serializedData,
				dataType: "html",
				cache: false,
				complete: function(r, ts) {
					if (ts === "success")
						location.reload()

					inputs.prop("disabled", false);
				},
				error: function() {
					console.log("Errore")
					alert("La richiesta non è andata a buon fine, riprovare")
				}
			})
		})
	})
</script>

</html>
