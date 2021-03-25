<?php
/*
 *         __          __                __            
 *    ____/ /__ _   __/ /_  ____  ____  / /__ _________
 *   / __  / _ \ | / / __ \/ __ \/ __ \/ //_// ___/_  /
 *  / /_/ /  __/ |/ / /_/ / /_/ / /_/ / ,< _/ /__  / /_
 *  \__,_/\___/|___/_.___/\____/\____/_/|_(_)___/ /___/
 *                                                   
 *                                                           
 *      TUTORIÁLY  <>  DISKUZE  <>  KOMUNITA  <>  SOFTWARE
 * 
 *  Tento zdrojový kód je součástí tutoriálů na programátorské 
 *  sociální síti WWW.DEVBOOK.CZ    
 *  
 *  Kód můžete upravovat jak chcete, jen zmiňte odkaz 
 *  na www.devbook.cz :-) 
 */
 
class Galerie
{
    private $slozka;
    private $sloupcu;
    private $soubory = array();
    
    
    public function __construct($slozka, $sloupcu)
    {
        $this->slozka = $slozka;
        $this->sloupcu = $sloupcu;
    }
    
    
    public function nacti()
    {
        $slozka = dir($this->slozka);

        while ($polozka = $slozka->read()) 
        {
            if (is_file($this->slozka . '/' . $polozka) && strpos($polozka, '_nahled.'))
            {
                $this->soubory[] = $polozka;
            }
        }
        $slozka->close();
    }
    
    
    public function vypis()
    {
        echo('<table id="galerie"><tr>');
        $sloupec = 0;
        foreach ($this->soubory as $soubor)
        {
            $nahled = $this->slozka . '/' . $soubor;
            $obrazek = $this->slozka . '/' . str_replace('_nahled.', '.', $soubor) ;
            echo('<td><a href="' . htmlspecialchars($obrazek) . '" rel="lightbox[galerie]"><img src="' . htmlspecialchars($nahled) . '" alt=""></a></td>');
            $sloupec++;
            if ($sloupec >= $this->sloupcu)
            {
                echo('</tr><tr>');
                $sloupec = 0;
            }
        }
        echo('</tr></table>');
    }
    
    
}
