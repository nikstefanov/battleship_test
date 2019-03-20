<?php
require_once "Cell.php";
session_start();
require_once "State.php";
require_once "InitialState.php";
require_once "Controller.php";
require_once "View.php";

//In case of debug
//error_reporting(E_ALL);
//ini_set("display_errors", "stdout");
?>
<html>
<head>
    <title>Battleship game</title>
    <meta charset="UTF-8">
    <meta name="description" content="TechHuddle test task">
    <meta name="author" content="Nikolay Stefanov">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            font-style: normal;
            font-size: 14px;
            font-weight: normal;
            font-family: "Courier New",monospace;
        }
        table tr{
            line-height: 1.3;
        }
        td{
            padding: 0px 4px;
        }
    </style>
</head>
<body>
<?php
View::getView(Controller::getState());
?>
</body>
</html>