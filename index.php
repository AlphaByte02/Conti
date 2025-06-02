<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Conti</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>

	<div class="container w-50">
		<form method="post" id="form">
			<h2>Pagante</h2>
			<select class="form-select w-50 mx-auto" id="pagante" name="pagante" required>
				<?php
				include_once("db.php");

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
			?>
			<?php if ($res->rowCount() > 2) : ?>
				<button type="button" class="btn btn-secondary" id="btnAll">Tutti</button><br/><br/>
			<?php endif; ?>
			<?php while ($row = $res->fetch()) : ?>
			<div class="d-block">
				<input class="form-check-input" type="checkbox" name="soggetto_<?= $row["id"] ?>" id="soggetto_<?= $row["id"] ?>">
				<label class="form-check-label" for="soggetto_<?= $row["id"] ?>"><?= $row["nome"] ?></label>
			</div>
			<?php endwhile; ?>
			<br/>

			<label for="importo" class="form-label">Importo (€)</label>
			<input type="number" class="form-control w-50 mx-auto" id="importo" name="importo" step="0.01" min="0" value="0.0" required>

			<br/>

			<label for="causale" class="form-label">Causale</label>
			<input type="text" class="form-control w-50 mx-auto" id="causale" name="causale">

			<br/><br/>

			<button type="submit" class="btn btn-primary">Salva</button>
		</form>

		<br>

		<a href="riepilogoTransazioni.php">
			<h2>Riepilogo Transazioni</h2>
		</a><a href="riepilogoConti.php">
			<h2>Riepilogo Conti</h2>
		</a>
	</div>
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
					if (ts === "success") {
						location.reload()
					}

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
