<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Riepilogo Conti Finali</title>

	<link href="style.css" rel="stylesheet">
</head>

<body>
	<h1>Riepilogo Conti Finali</h1>
	<a href="index.php">
		<h4>Inserisci altri dati</h4>
	</a>
	<a href="riepilogoTransazioni.php">
		<h4>Guarda il Riepilogo delle Transazioni</h4>
	</a>
	<br />
	<a href="riepilogoConti.php">
		<h4>Guarda il Riepilogo dei conti</h4>
	</a>
	<?php
	include_once("db.php");

	$query = "WITH
			DEBITI AS (
				SELECT
					P.NOME AS DEBITORE,
					S.NOME AS CREDITORE,
					SUM(T.IMPORTO / PARTECIPANTI.TOTALE_PARTECIPANTI) AS IMPORTO_TOTALE
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
					CREDITORE
			),
			SALDIFINALI AS (
				SELECT
					D1.DEBITORE,
					D1.CREDITORE,
					COALESCE(D1.IMPORTO_TOTALE, 0) - COALESCE(D2.IMPORTO_TOTALE, 0) AS SALDO
				FROM
					DEBITI D1
					LEFT JOIN DEBITI D2 ON D1.DEBITORE = D2.CREDITORE
					AND D1.CREDITORE = D2.DEBITORE
			)
		SELECT
			DEBITORE AS debitore,
			CREDITORE AS creditore,
			SALDO AS saldo
		FROM
			SALDIFINALI
		WHERE
			SALDO > 0
		ORDER BY
			DEBITORE,
			CREDITORE";


	$lastname = "";
	$res = $db->query($query);
	if ($res !== null && $res->rowCount() > 0) {
		echo "<table>";
		echo "<thead><tr><th>Debitore</th><th>Creditore</th><th>Saldo (€)</th></tr></thead>";
		echo "<tbody>";
		while ($row = $res->fetch()) {
			if ($lastname != $row["debitore"]) {
				if ($lastname != "") {
					echo "<tr><td>-</td><td>-</td><td>-</td></tr>";
				}
				$lastname = $row["debitore"];
			}
			echo "<tr><td>$row[debitore]</td><td>$row[creditore]</td><td>" . sprintf('%0.2f', $row["saldo"]) . "</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	} else
		echo "<h3>Nessuna transazione effettuta! Falla ora <a href='index.php'>qui</a></h3>";

	?>
</body>

</html>
