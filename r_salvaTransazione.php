<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST["importo"]) && $_POST["importo"] !== "0") {
		include_once("db.php");

		$res = $db->query("SELECT id FROM nomi");
		$soggetti = array();
		while ($row = $res->fetch()) {
			if (isset($_POST["soggetto_$row[id]"]))
				$soggetti[] = $row["id"];
		}

		if (count($soggetti) > 0 && !(count($soggetti) == 1 && $soggetti[0] == $_POST["pagante"])) {
			$res = $db->query("INSERT INTO transazioni(id_pagante, importo, causale) VALUES ($_POST[pagante], $_POST[importo], " . (empty($_POST["causale"]) ? "NULL" : "'$_POST[causale]'") . ")");
			if ($res !== false) {
				$id_transazione = $db->lastInsertId();

				$q = "INSERT INTO soggetti(id_transazione, id_soggetto) VALUES ";
				foreach ($soggetti as $id_soggetto) {
					$q .= "($id_transazione, $id_soggetto),";
				}
				$q = trim($q, " ,");

				$db->query($q);
			}
		}
	}
}
