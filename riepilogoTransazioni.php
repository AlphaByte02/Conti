<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Riepilogo Transazioni</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<h1>Riepilogo Transazioni</h1>
	<a href="index.php">
		<h4>Inserisci altri dati</h4>
	</a>
	<a href="riepilogoConti.php">
		<h4>Guarda il Riepilogo dei Conti</h4>
	</a>
	<?php
	include_once("db.php");

	$query = "SELECT
				t.id AS id,
				s.nome AS pagante,
				t.importo AS importo,
				t.causale AS causale,
				CONCAT(COUNT(ts.id_soggetto), '/', max_persone.tot) AS numero_persone
			FROM
				transazioni t
			JOIN
				soggetti ts ON t.id = ts.id_transazione
			JOIN
				nomi s ON t.id_pagante = s.id
			CROSS JOIN (SELECT COUNT(*) AS tot FROM nomi) AS max_persone
			GROUP BY
				t.id, s.nome, t.importo, t.causale, max_persone.tot
			ORDER BY
				t.id";


	$somma = 0;

	$res = $db->query($query);
	?>

	<div class="container w-50">
	<?php if ($res != null && $res->rowCount() > 0) : ?>
		<table class="table table-bordered">
			<thead><tr><th>ID Transazione</th><th>Pagante</th><th>N°</th><th>Importo (€)</th><th>Causale</th></tr></thead>
			<tbody>
				<?php while ($trans = $res->fetch()) : ?>
				<tr>
					<td><?= $trans["id"] ?></td>
					<td><?= $trans["pagante"] ?></td>
					<td><?= $trans["numero_persone"] ?></td>
					<td><?= sprintf('%0.2f', $trans["importo"]) ?></td>
					<td><?= empty($trans["causale"]) ? "-" : $trans["causale"] ?></td>
				</tr>
				<?php
					if ($trans["numero_persone"] > 1) {
						$somma += $trans["importo"];
					}
				?>
				<?php endwhile; ?>
				<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
				<tr><td colspan='2'>Somma</td><td>-</td><td> <?= sprintf('%0.2f', $somma) ?> </td><td>-</td></tr>
			</tbody>
		</table>
		<? else: ?>
		<h3>Nessuna transazione effettuta! Falla ora <a href='index.php'>qui</a></h3>
		<? endif; ?>
	</div>
</body>

</html>
