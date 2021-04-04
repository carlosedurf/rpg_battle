<?php

namespace App\Traits;

trait Dice {

    protected $dice;
    protected $qtdDice;
    protected $numDice;

    public function playDice($dice = '1d10')
    {
        $this->dice = $dice;

        $this->formatDice();

        $value = 0;
        for($i = 1; $i<=$this->qtdDice; $i++){
            $value += rand(1, $this->numDice);
        }

        return $value;
    }

    private function formatDice()
    {
        list($this->qtdDice, $this->numDice) = explode('d', $this->dice);
    }

}
