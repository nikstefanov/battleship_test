<?php
/**
 * VMAX - The maximum awolled cells verticaly.
 * HMAX - The maximum awolled cells horizontaly.
 */
define('VMAX', 26);
define('HMAX', 99);

abstract class InitialState
{
    public static function loadConfig()
    {
        $config = parse_ini_file("config.ini");
        if (!$config) {
            echo "There is a problem with the configuration file.";
            return false;
        }
        if ($config['horizontal_dimension'] > HMAX ||
            $config['vertical_dimension'] > VMAX) {
            echo "Dimensions of the board are out of the limits.";
            return false;
        }
        $_SESSION['horizontal_dimension'] = $config['horizontal_dimension'];
        $_SESSION['vertical_dimension'] = $config['vertical_dimension'];

        /**
         * @global array $_SESSION['shipLengths']
         * @name $shipLengths
         * Array of integers, representing every ship's length.
         */
        $_SESSION['shipLengths'] = $config['shipLengths'];
        return true;
    }

    public static function loadModel()
    {
        self::generateBoard();
        /**
         * @global array $_SESSION['hitsPerShip']
         * @name $hitsPerShip
         * Array of integers, representing how many cells of each ship have been hit.
         */
        $_SESSION['hitsPerShip'] = array();
        for ($skey = 0; $skey < count($_SESSION['shipLengths']); $skey++) {
            $_SESSION['hitsPerShip'][$skey] = 0;
        }
        /**
         * @global integer $_SESSION['sunkShips']
         * @name $sunkShips
         * The number of sunk ships across the board.
         */
        $_SESSION['sunkShips'] = 0;
        /**
         * @global integer $_SESSION['shotsCount']
         * @name $shotsCount
         * The number of shots made by the player.
         */
        $_SESSION['shotsCount'] = 0;
    }

    private static function generateEmptyBoard()
    {
        for ($i = 0; $i < $_SESSION['vertical_dimension']; $i++) {
            $rowOfBoard = array();
            for ($j = 0; $j < $_SESSION['horizontal_dimension']; $j++) {
                $rowOfBoard[$j] = new Cell();
            }
            /**
             * @global array $_SESSION['board']
             * @name $board
             * Two dimensional array of Cell objects,
             * representing the board.
             * First dimension is the vertical coordinate.
             * Second dimension is the horizontal coordinate.
             */
            $_SESSION['board'][$i] = $rowOfBoard;
        }
    }

    /**
     * @method generateBoard
     * Places ships on an empty board randomly.
     * The algorithm is like this:
     * 1.Pick a ship orientation.
     * 2.Pick a board cell that can be a start of the particular ship.
     * 3.For all of the ship cells check if there is interference with other ship.
     * 4.If there is not, place the ship.
     * 5.Otherwise repeat the procedure.
     */
    private static function generateBoard()
    {
        self::generateEmptyBoard();
        for ($skey = 0; $skey < count($_SESSION['shipLengths']); $skey++) {
            $shipPlaced = false;
            while (!$shipPlaced) {
                /**
                 * @name $shipOrientation
                 * Denote the orientation of the ship.
                 * 0 - horizontal orientation,
                 * 1 - vertical orientation
                 */
                $shipOrientation = rand(0, 1);
                $shipPosX =
                    rand(0,
                    $_SESSION['horizontal_dimension'] - ($shipOrientation ? 1 : $_SESSION['shipLengths'][$skey]));
                $shipPosY =
                    rand(0,
                    $_SESSION['vertical_dimension'] - ($shipOrientation ? $_SESSION['shipLengths'][$skey] : 1));
                $shipPlaced = true;
                for ($i = 0; $i < $_SESSION['shipLengths'][$skey]; $i++) {
                    if ($_SESSION['board'][$shipPosY + $i * $shipOrientation][$shipPosX + $i * (1 - $shipOrientation)]
                        ->getShipIndex() !== null) {
                        $shipPlaced = false;
                        break;
                    }
                }
                if ($shipPlaced) {
                    for ($i = 0; $i < $_SESSION['shipLengths'][$skey]; $i++) {
                        $_SESSION['board'][$shipPosY + $i * $shipOrientation][$shipPosX + $i * (1 - $shipOrientation)]
                            ->setShipIndex($skey);
                    }
                }
            }
        }
    }
}
