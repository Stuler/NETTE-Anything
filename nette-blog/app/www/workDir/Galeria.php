<?php
declare(strict_types=1);

class Galeria {

	private $zlozka;        // cesta ku zlozke s obrazkami
	private $pocetStlpcov;  //kolko nahladov sa zobrazi v jednom riadku
	private $subory = array();

	public function __construct($zlozka, $pocetStlpcov){
		$this->zlozka=$zlozka;
		$this->pocetStlpcov=$pocetStlpcov;
	}

	/*
	  Metoda nacitaj prehlada zlozku a ulozi si do pamate nahlady obrazkov
	  pouzijeme php triedu Directory
	 */
	public function nacitaj(){
		$zlozka = dir($this->zlozka);

		while ($polozka = $zlozka->read()){     //pomocou READ nacitavame postupne cely obsah zlozky, kym nedojdeme na koniec zlozky
			if (strpos($polozka,'_nahled.')){
				$this->subory[] = $polozka;
			}
		}
		$zlozka->close();
	}

	public function vypis(){
		echo ('<table id="galeria"><tr>');
		var_dump($this->subory);
		$stlpec=0;
		foreach ($this->subory as $subor){
			$nahled = $this->zlozka . '/' . $subor;
			$obrazok = $this->zlozka . '/' . str_replace('_nahled.', '.', $subor);
			echo('<td><img src="' . $nahled . '" alt=""></td>');
			$stlpec++;
			if ($stlpec >= $this->pocetStlpcov){
				echo ('</tr><tr>');
				$stlpec=0;
			}
		}
		echo '</tr></table>';
	}
}