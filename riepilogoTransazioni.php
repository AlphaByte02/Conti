<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Riepilogo Transazioni</title>

	<link href="style.css" rel="stylesheet">
</head>

<body>
	<h1>Riepilogo Transazioni</h1>
	<a href="index.php">
		<h4>Inserisci altri dati</h4>
	</a> <a href="riepilogoConti.php">
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

	$res = $db->query($query);

	if ($res != null && $res->rowCount() > 0) {
		echo "<table>";
		echo "<thead><tr><th>ID Transazione</th><th>Pagante</th><th>N°</th><th>Importo (€)</th><th>Causale</th></tr></thead>";
		echo "<tbody>";
		while ($trans = $res->fetch()) {
			echo "<tr>";
			echo "<td>$trans[id]</td><td>$trans[pagante]</td><td>$trans[numero_persone]</td><td>" . sprintf('%0.2f', $trans["importo"]) . "</td><td>" . (empty($trans["causale"]) ? "-" : $trans["causale"]) . "</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		echo "<br/><br/>";
	} else
		echo "<h3>Nessuna transazione effettuta! Falla ora <a href='index.php'>qui</a></h3>";
	?>
</body>

</html>
