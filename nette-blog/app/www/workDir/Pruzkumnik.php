<?php

/*  _____ _______         _                      _
 * |_   _|__   __|       | |                    | |
 *   | |    | |_ __   ___| |___      _____  _ __| | __  ___ ____
 *   | |    | | '_ \ / _ \ __\ \ /\ / / _ \| '__| |/ / / __|_  /
 *  _| |_   | | | | |  __/ |_ \ V  V / (_) | |  |   < | (__ / /
 * |_____|  |_|_| |_|\___|\__| \_/\_/ \___/|_|  |_|\_(_)___/___|
 *
 * IT ZPRAVODAJSTVÍ  <>  PROGRAMOVÁNÍ  <>  HW A SW  <>  KOMUNITA
 *
 * Tento zdrojový kód je součástí výukových seriálů na
 * IT sociální síti WWW.ITNETWORK.CZ
 *
 * Kód spadá pod licenci prémiového obsahu a vznikl díky podpoře
 * našich členů. Je určen pouze pro osobní užití a nesmí být šířen.
 */

/**
 * Reprezentuje jednoduchý průzkumník souborů
 */
class Pruzkumnik
{
	/**
	 * @var string Relativní cesta ke složce, kterou prozkoumáváme
	 */
	private $cesta;
	/**
	 * @var array Seznam nalezených souborů
	 */
	// Následují pole pro názvy souborů a složek v dané cestě. Pole jsou 2, protože chceme vypsat nejdříve složky a až potom soubory
	private $soubory = array();
	/**
	 * @var array Seznam nalezených složek
	 */
	private $slozky = array();
	/**
	 * @var array Mapa přípon souborů na ikony
	 */
	private $ikony = array(
		'jpg' => 'img.png',
		'jpeg' => 'img.png',
		'gif' => 'img.png',
		'bmp' => 'img.png',
		'png' => 'img.png',
		'txt' => 'txt.png',
	);

	/**
	 * Inicializuje instanci
	 * @param $slozka Relativní cesta ke složce, kterou prozkoumáváme
	 */
	public function __construct($slozka)
	{
		$this->cesta = str_replace('.', '', $slozka); // Pro jistotu odstraníme tečky, aby se uživatel nedostal někam, kam nemá
	}

	/**
	 * @return string Podle aktuální cestu zjistí cestu o složku výš (z soubory/obrazky/modre udela soubory/obrazky)
	 */
	private function zjistiCestuNahoru()
	{
		return mb_substr($this->cesta, 0, mb_strrpos($this->cesta, '/'));
	}

	/**
	 * Podle celého názvu souboru vrátí název ikony, která se pro něj má zobrazit
	 * @param $soubor Název souboru
	 * @return string Název ikony
	 */
	private function vyberIkonu($soubor)
	{
		$pripona = mb_substr($soubor, mb_strrpos($soubor, '.') + 1);
		if (isset($this->ikony[$pripona]))
			return $this->ikony[$pripona];
		return 'unknown.png';
	}

	/**
	 * Načte soubory a složky do polí
	 */
	public function nacti()
	{
		$slozka = dir($this->cesta); // Otevření zvolené cesty

		while ($polozka = $slozka->read()) // Čtení složek a souborů
		{
			if (is_file($this->cesta . '/' . $polozka)) // Jedná se o soubor
			{
				$this->soubory[] = $polozka;
			}
			else if ($polozka != '.' && $polozka != '..') // Jedná se o složku a není to složka . nebo ..
			{
				$this->slozky[] = $polozka;
			}
		}
		$slozka->close();
	}

	/**
	 * Vypíše složky z pole jako ikonky
	 */
	private function vypisSlozky()
	{
		foreach ($this->slozky as $slozka)
		{
			echo('<div class="ikona">
					<a href="index.php?cesta=' . $this->cesta . '/' . htmlspecialchars($slozka) . '">
						<img src="' . htmlspecialchars('ikony/folder.png') . '" alt="Složka" /><br />
						' . htmlspecialchars($slozka) . '
					</a>
				</div>');
		}
	}

	/**
	 * Vypíše soubory z pole jako ikonky
	 */
	private function vypisSoubory()
	{
		foreach ($this->soubory as $soubor)
		{
			$ikona = $this->vyberIkonu($soubor);
			echo('<div class="ikona">
					<a href="' . $this->cesta . '/' . htmlspecialchars($soubor) . '">
						<img src="' . htmlspecialchars('ikony/' . $ikona) . '" alt="Soubor" /><br />
						' . htmlspecialchars($soubor) . '
					</a>
				</div>');
		}
	}

	/*
	 * Vypíše soubory a složky v zadané cestě
	 */
	public function vypis()
	{
		// Výpis tlačítka nahoru
		$cestaNahoru = $this->zjistiCestuNahoru();
		if ($cestaNahoru)
		echo('<div class="ikona">
				<a href="index.php?cesta=' . $cestaNahoru . '">
					<img src="ikony/up.png" alt="Nahoru" /><br />
					Nahoru
				</a>
			</div>');
		$this->vypisSlozky(); // Výpis složek
		$this->vypisSoubory(); // Výpis souborů
		var_dump($this->zjistiCestuNahoru());
	}
} 