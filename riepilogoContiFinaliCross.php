<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Riepilogo Conti Finali Cross</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
	<a href="riepilogoContiFinali.php">
		<h4>Guarda il Riepilogo dei conti finali</h4>
	</a>
	<div class="container w-50">
	<?php
	include_once("db.php");

	$query = "SELECT id, nome FROM nomi";
	$res = $db->query($query);

	$personNames = [];
	while ($row = $res->fetch()) {
		$personNames[intval($row['id'])] = $row['nome'];
	}

	$query = "SELECT
				ts.id_soggetto           AS id_debitore,
				t.id_pagante            AS id_creditore,
				SUM(t.importo::numeric / cnt.tot_partecipanti) AS spesa
			FROM transazioni t
			JOIN soggetti ts
			ON ts.id_transazione = t.id
			AND ts.id_soggetto   <> t.id_pagante
			JOIN (
				SELECT
					s2.id_transazione,
					COUNT(*) AS tot_partecipanti
				FROM soggetti s2
				GROUP BY s2.id_transazione
			) cnt
			ON cnt.id_transazione = t.id
			GROUP BY
				ts.id_soggetto,
				t.id_pagante";


	// Array che conterrà, in forma “netta”, i saldi di ciascuno:
	//   • se $balances[$id] > 0 ⇒ quella persona è CREDITRICE (gli devono),
	//   • se $balances[$id] < 0 ⇒ quella persona è DEBITRICE (deve).
	$balances = [];

	$res = $db->query($query);
	while ($row = $res->fetch()) {
		$debtorId    = intval($row['id_debitore']);
		$creditorId = intval($row['id_creditore']);
		$amount      = floatval($row['spesa']);

		// Inizializzo a 0 se non esistono ancora
		if (!isset($balances[$debtorId])) {
			$balances[$debtorId] = 0.0;
		}
		if (!isset($balances[$creditorId])) {
			$balances[$creditorId] = 0.0;
		}

		// Il debitore perde (saldo -= amount), il creditore guadagna (saldo += amount)
		$balances[$debtorId]   -= $amount;
		$balances[$creditorId] += $amount;
	}

	$debtors   = [];
	$creditors = [];

	foreach ($balances as $personId => $saldoNetto) {
		$saldoNettoR = round($saldoNetto, 2);

		if ($saldoNetto < 0) {
			$debtors[$personId] = abs($saldoNettoR);
		} elseif ($saldoNetto > 0) {
			$creditors[$personId] = $saldoNettoR;
		}
	}

	arsort($debtors);
	arsort($creditors);

	$settlements = []; // Array di righe ['debitore' => id, 'creditore' => id, 'importo' => float]

	while (!empty($debtors) && !empty($creditors)) {
		// Primo debitore (chi deve di più)
		$debtorId   = array_key_first($debtors);
		$debtorAmt  = $debtors[$debtorId];

		// Primo creditore (chi ha credito maggiore)
		$creditorId  = array_key_first($creditors);
		$creditorAmt = $creditors[$creditorId];

		// Importo da trasferire = minimo fra debito e credito
		$payment = min($debtorAmt, $creditorAmt);
		$payment = round($payment, 2);

		// Registra nel risultato
		$settlements[] = [
			'debitore'  => $debtorId,
			'creditore' => $creditorId,
			'importo'   => $payment,
		];

		// Aggiorna saldi residui
		$newDebtorSaldo   = $debtorAmt   - $payment;
		$newCreditorSaldo = $creditorAmt - $payment;

		if ($newDebtorSaldo == 0.0) {
			unset($debtors[$debtorId]);
		} else {
			$debtors[$debtorId] = round($newDebtorSaldo, 2);
		}

		if ($newCreditorSaldo == 0.0) {
			unset($creditors[$creditorId]);
		} else {
			$creditors[$creditorId] = round($newCreditorSaldo, 2);
		}
	}


	if (!empty($creditors)) {
		echo "<h3>C'È UN ERRORE!</h3>";
	}

	if (empty($settlements)) {
		echo "<h3>Nessuna transazione effettuta! Falla ora <a href='index.php'>qui</a></h3>";
	} else {
		$lastname = "";

		echo "<table class='table'>";
		echo "<thead><tr><th>Debitore</th><th>Creditore</th><th>Saldo (€)</th></tr></thead>";
		echo "<tbody>";
		foreach ($settlements as $row) {
			$debName  = htmlspecialchars($personNames[$row['debitore']]  ?? "ID {$row['debitore']}");
			$credName = htmlspecialchars($personNames[$row['creditore']] ?? "ID {$row['creditore']}");
			$amt      = number_format($row['importo'], 2, ',', '.');

			if ($lastname != $debName) {
				if ($lastname != "") {
					echo "<tr><td>-</td><td>-</td><td>-</td></tr>";
				}
				$lastname = $debName;
			}

			echo "<tr><td>$debName</td><td>$credName</td><td>$amt</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
	?>
	</div>
</body>

</html>
