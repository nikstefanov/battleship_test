<?php

/**
 * ACAPCODE - the ASCII code of 'A'.
 */
define('ACAPCODE', 65);

abstract class View
{
    private static $state;

    public static function getView($state)
    {
        self::$state = $state;?>
        <div>
            <?=self::getMessage()?>
        </div>
        <br/>
        <?=self::showBoard()?>
        <br/>
        <div>
            <?=self::showSubboardSpace()?>
        </div><?php
}

    private static function getMessage()
    {
        switch (self::$state) {
            case State::ERROR:
                return "*** Error ***";
            case State::MISS:
                return "*** Miss ***";
            case State::HIT:
                return "*** Hit ***";
            case State::SUNK:
                return "*** Sunk ***";
            case State::WIN:
                return "*** Win ***";
            default:
                return "&nbsp;";
        }
    }

    private static function showBoard()
    {
        if (self::$state === State::FATAL) {
            echo "<br/>\n";
        }
        echo "<table>\n";
        echo "<tr>\n";
        echo "<td></td>\n";
        for ($col = 0; $col < $_SESSION['horizontal_dimension']; $col++) {
            echo "<td>" . ($col + 1) . "</td>\n";
        }
        echo "</tr>\n";
        for ($row = 0; $row < $_SESSION['vertical_dimension']; $row++) {
            echo "<tr>\n";
            echo "<td>" . chr(ACAPCODE + $row) . "</td>\n";
            for ($col = 0; $col < $_SESSION['horizontal_dimension']; $col++) {
                echo "<td>";
                if (self::$state === State::SHOW) {
                    if ($_SESSION['board'][$row][$col]->getShipIndex() !== null &&
                        !$_SESSION['board'][$row][$col]->isFired()) {
                        echo "X";
                    }
                } else {
                    if (!$_SESSION['board'][$row][$col]->isFired()) {
                        echo ".";
                    } elseif ($_SESSION['board'][$row][$col]->getShipIndex() !== null) {
                        echo "X";
                    } else {
                        echo "-";
                    }
                }
                echo "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
    }

    private static function showSubboardSpace()
    {
        switch (self::$state) {
            case State::FATAL:
                return;
            case State::WIN:
                echo "Well done! You completed the game in " . $_SESSION['shotsCount'] . " shots";
                return;
            default: ?>
                <form accept-charset='UTF-8' action='index.php' autocomplete='off' method='post'>
                    <label>Enter coordinates (row, col), e.g. A5 </label>
                    <input type='text' autocomplete='off' autofocus required name='shot' size='6'>
                    <input type='submit' value='Shoot!'>
                </form>
                <?php return;
        }
    }
}
