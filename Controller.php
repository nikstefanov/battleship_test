<?php

/**
 * ACODE - the ASCII code of 'a'.
 */
define('ACODE', 97);

abstract class Controller
{
    public static function getState()
    {
        $shot = "";
        if (!array_key_exists('board', $_SESSION)) {
            //Initial case
            if (!InitialState::loadConfig()) {
                return State::FATAL;
            }
            InitialState::loadModel();
            return State::INIT;
        } elseif ($_SESSION['sunkShips'] == count($_SESSION['shipLengths'])) {
            //Reload after winning case
            unset($_SESSION['board']);
            unset($_SESSION['hitsPerShip']);
            InitialState::loadModel();
            return State::INIT;
        } elseif (($shot = strtolower(trim(htmlspecialchars($_POST['shot'])))) === "show") {
            return State::SHOW;
        } elseif (!preg_match('/^[a-z][1-9]\d?$/', $shot = preg_replace('/\s/', '', $shot))) {
            return State::ERROR;
        } else {
            $shotPosY = ord($shot) - ACODE;
            if ($shotPosY >= $_SESSION['vertical_dimension']) {
                return State::ERROR;
            }
            $shotPosX = intval(substr($shot, 1)) - 1;
            if ($shotPosX >= $_SESSION['horizontal_dimension']) {
                return State::ERROR;
            }
            $_SESSION['shotsCount']++;
            if ($_SESSION['board'][$shotPosY][$shotPosX]->getShipIndex() === null) {
                $_SESSION['board'][$shotPosY][$shotPosX]->setFired();
                return State::MISS;
            } else {
                if ($_SESSION['board'][$shotPosY][$shotPosX]->isFired()) {
                    return State::HIT;
                } else {
                    $_SESSION['board'][$shotPosY][$shotPosX]->setFired();
                    if (++$_SESSION['hitsPerShip'][$_SESSION['board'][$shotPosY][$shotPosX]->getShipIndex()] !=
                        $_SESSION['shipLengths'][$_SESSION['board'][$shotPosY][$shotPosX]->getShipIndex()]) {
                        return State::HIT;
                    } else {
                        if (++$_SESSION['sunkShips'] != count($_SESSION['shipLengths'])) {
                            return State::SUNK;
                        } else {
                            return State::WIN;
                        }
                    }
                }
            }
        }
    }
}
