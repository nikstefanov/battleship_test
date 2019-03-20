<?php
class Cell
{
    /**
     * If the cell is part of a ship, here is stored its index. Otherwise it is set to null.
     */
    private $shipIndex;

    /**
     * A boolean value representing if the current cell has been fired.
     */
    private $fired;

    public function getShipIndex()
    {
        return $this->shipIndex;
    }

    public function setShipIndex($ship)
    {
        $this->shipIndex = $ship;
    }

    public function isFired()
    {
        return $this->fired;
    }

    public function setFired()
    {
        $this->fired = true;
    }
}
