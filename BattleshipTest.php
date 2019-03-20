<?php
require_once "InitialState.php";
require_once "Cell.php";
require_once "State.php";
require_once "Controller.php";

final class BattleshipTest extends phpunit_framework_testcase
{
    /**
     * @covers InitialState
     */
    public function testShipsDoNotOverlapOrExtendOutOfBoard()
    {
        InitialState::loadConfig();
        InitialState::loadModel();
        $battleshipCells = 0;
        foreach ($_SESSION['shipLengths'] as $shipLength) {
            $battleshipCells += $shipLength;
        }
        $battleshipCellsOnBoard = 0;
        foreach ($_SESSION['board'] as $boardRow) {
            foreach ($boardRow as $boardCell) {
                if ($boardCell->getShipIndex() !== null) {
                    $battleshipCellsOnBoard++;
                }
            }
        }
        $this->assertSame($battleshipCells, $battleshipCellsOnBoard);
        return $_SESSION;
    }

    /**
     * @depends testShipsDoNotOverlapOrExtendOutOfBoard
     * @covers Controller
     */
    public function testStateWhenCommandIsShow($session)
    {
        $_POST['shot'] = "show";
        $_SESSION = $session;
        $this->assertSame(State::SHOW, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenCommandIsShow
     * @covers Controller
     */
    public function testStateWhenCommandIsError($session)
    {
        $_POST['shot'] = "n8n98";
        $_SESSION = $session;
        $this->assertSame(State::ERROR, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenCommandIsError
     * @covers Controller
     */
    public function testStateWhenShotIsOutOfBoundaries($session)
    {
        $_POST['shot'] = "a99";
        $_SESSION = $session;
        $this->assertSame(State::ERROR, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenShotIsOutOfBoundaries
     * @covers Controller
     */
    public function testStateWhenShotIsMiss($session)
    {
        $_SESSION = $session;
        foreach ($_SESSION['board'] as $row => $boardRow) {
            foreach ($boardRow as $col => $boardCell) {
                if ($boardCell->getShipIndex() === null) {
                    $_POST['shot'] = chr(ACODE + $row) . strval($col + 1);
                    break 2;
                }
            }
        }
        $this->assertSame(State::MISS, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenShotIsMiss
     * @covers Controller
     */
    public function testStateWhenShotIsHit($session)
    {
        $_SESSION = $session;
        foreach ($_SESSION['board'] as $row => $boardRow) {
            foreach ($boardRow as $col => $boardCell) {
                if ($boardCell->getShipIndex() === 0) {
                    $_POST['shot'] = chr(ACODE + $row) . strval($col + 1);
                    break 2;
                }
            }
        }
        $this->assertSame(State::HIT, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenShotIsHit
     * @covers Controller
     */
    public function testStateWhenShotIsSunk($session)
    {
        $_SESSION = $session;
        foreach ($_SESSION['board'] as $row => $boardRow) {
            foreach ($boardRow as $col => $boardCell) {
                if ($boardCell->getShipIndex() === 0 && !$_SESSION['board'][$row][$col]->isFired()) {
                    if ($_SESSION['hitsPerShip'][0] + 1 < $_SESSION['shipLengths'][0]) {
                        $_SESSION['board'][$row][$col]->setFired();
                        $_SESSION['hitsPerShip'][0]++;
                        $_SESSION['shotsCount']++;
                    } else {
                        $_POST['shot'] = chr(ACODE + $row) . strval($col + 1);
                        break 2;
                    }
                }
            }
        }
        $this->assertSame(State::SUNK, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenShotIsSunk
     * @covers Controller
     */
    public function testStateWhenShotIsWin($session)
    {
        $_SESSION = $session;
        foreach ($_SESSION['board'] as $row => $boardRow) {
            foreach ($boardRow as $col => $boardCell) {
                if ($boardCell->getShipIndex() !== null && !$_SESSION['board'][$row][$col]->isFired()) {
                    if (array_sum($_SESSION['hitsPerShip']) + 1 < array_sum($_SESSION['shipLengths'])) {
                        if (++$_SESSION['hitsPerShip'][$boardCell->getShipIndex()] ==
                            $_SESSION['shipLengths'][$boardCell->getShipIndex()]) {
                            $_SESSION['sunkShips']++;
                        }
                        $_SESSION['board'][$row][$col]->setFired();
                        $_SESSION['shotsCount']++;
                    } else {
                        $_POST['shot'] = chr(ACODE + $row) . strval($col + 1);
                        break 2;
                    }
                }
            }
        }
        $this->assertSame(State::WIN, Controller::getState());
        return $_SESSION;
    }

    /**
     * @depends testStateWhenShotIsWin
     * @covers Controller
     */
    public function testCountOfShots($session)
    {
        $_SESSION = $session;
        $this->assertSame(array_sum($_SESSION['shipLengths']) + 1, $_SESSION['shotsCount']);
    }
}
