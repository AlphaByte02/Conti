<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Riepilogo Conti</title>

	<link href="style.css" rel="stylesheet">
</head>

<body>
	<h1>Riepilogo Conti</h1>
	<a href="index.php">
		<h4>Inserisci altri dati</h4>
	</a>
	<a href="riepilogoTransazioni.php">
		<h4>Guarda il Riepilogo delle Transazioni</h4>
	</a>
	<br />
	<a href="riepilogoContiFinali.php">
		<h4>Guarda il Riepilogo dei conti finali</h4>
	</a>
	<?php
	include_once("db.php");

	$query = "SELECT
				P.NOME AS debitore,
				S.NOME AS creditore,
				T.CAUSALE AS causale,
				SUM(T.IMPORTO / PARTECIPANTI.TOTALE_PARTECIPANTI) AS spesa
			FROM
				TRANSAZIONI T
				JOIN SOGGETTI TS ON T.ID = TS.ID_TRANSAZIONE
				JOIN NOMI S ON T.ID_PAGANTE = S.ID
				JOIN NOMI P ON TS.ID_SOGGETTO = P.ID
				JOIN (
					SELECT
						ID_TRANSAZIONE,
						COUNT(*) AS TOTALE_PARTECIPANTI
					FROM
						SOGGETTI
					GROUP BY
						ID_TRANSAZIONE
				) PARTECIPANTI ON T.ID = PARTECIPANTI.ID_TRANSAZIONE
			WHERE
				S.ID <> P.ID
			GROUP BY
				DEBITORE,
				CREDITORE,
				CAUSALE
			ORDER BY
				DEBITORE,
				CREDITORE,
				CAUSALE";

	$lastname = "";
	$res = $db->query($query);
	if ($res !== null && $res->rowCount() > 0) {
		while ($row = $res->fetch()) {
			if ($lastname != $row["debitore"]) {
				if ($lastname != "") {
					echo "<tr><th>-</th><td>-</td><td>-</td></tr>";
					foreach ($somme as $pagante => $somma) {
						echo "<tr><th>TOT $pagante</th><td colspan='2'>" . sprintf('%0.2f', $somma["spesa"]) . "</td></tr>";
					}
					echo "</tbody>";
					echo "</table>";
					echo "<br/><br/>";
				}

				$somme = [];
				$lastname = $row["debitore"];

				echo "<table>";
				echo "<caption><b>$row[debitore]</b></caption>";
				echo "<thead><tr><th>Pagante</th><th>Quanto (€)</th><th>Causale</th></tr></thead>";
				echo "<tbody>";
			}

			echo "<tr>";
			echo "<td>$row[creditore]</td><td>" . sprintf('%0.2f', $row["spesa"]) . "</td><td>" . (empty($row["causale"]) ? "-" : $row["causale"]) . "</td>";
			echo "</tr>";
			$somme[$row["creditore"]]["spesa"] = (isset($somme[$row["creditore"]]) ? $somme[$row["creditore"]]["spesa"] : 0) + $row["spesa"];
		}
	} else {
		echo "<h3>Nessuna transazione effettuta! Falla ora <a href='index.php'>qui</a></h3>";
	}

	?>
</body>

</html>
