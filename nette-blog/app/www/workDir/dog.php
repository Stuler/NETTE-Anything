<?php

class Wolf {

    public $name;
    public $weight;

    public function __construct($name, $weight) 
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    public function bark() {
        echo $this->name . " GGRRRRRRRRRrrrrrgRRR";
    }
}

class Dog extends Wolf {

    public function bark() {
        echo $this->name . " woof";
    }
}

class Coyote extends Wolf {

    public function bark() {
        echo $this->name . " WOOF";
    }
}

$dunco = new Dog("Dunco", 5);
var_dump($dunco->name);
$dunco->bark();

$courage = new Wolf("Courage", 12);
var_dump($courage->name);
$courage->bark();

$monster = new Coyote("Monster", 20);
var_dump($monster->name);
$monster->bark();