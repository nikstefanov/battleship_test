Battleships Programming Test
Please follow the instructions carefully!

Please only use the language and version you have been instructed to use.
Do not store state in database or filesystem.
Do not use a framework.
The game logic and presentation must be separated.

In addition to working code that follows this spec and example closely you are expected to make your code elegant, easy to follow and the best you can do, i.e. separation of logic / object-oriented abstraction. But don’t go object oriented crazy and create 20+ classes.
The Problem
You must write a simple web application to allow a single human player to play a one-sided game of battleships against the computer. 

Please see example here http://www.techhuddle.com/tests/battleships/v4/index.php

The program should create a 10x10 grid, and place a number of ships on the grid at random with the following sizes:
1 x Battleship (5 squares)
2 x Destroyers (4 squares)
Ships can touch but they must not overlap.

The application should accept input from the user in the format “A5” to signify a square to target, and feedback to the user whether the shot was success, miss, and additionally report on the sinking of any vessels.
. = no shot
- = miss
X = hit

Example output

*** Miss ***

  1 2 3 4 5 6 7 8 9 10
A - . . . . . . . . .
B . . . . . . . . . .
C . . . . . . . . . .
D . . . . . . . . . .
E . . . . . . . . . .
F . . . . . . . . . .
G . . . . . . . . . .
H . . . . . . . . . .
I . . . . . . . . . .
J . . . . . . . . . .

Enter coordinates (row, col), e.g. A5 =
You should implement a show command to aid debugging and backdoor cheat. Example output after entering show

  1 2 3 4 5 6 7 8 9 10
A             X   
B             X   
C     X       X   
D     X       X   
E     X       X   
F     X       
G           
H           
I             X X X X
J           

Enter coordinates (row, col), e.g. A5 =

Please report the number of shots taken once game complete, e.g.

*** Sunk ***

  1 2 3 4 5 6 7 8 9 10
A . . . . . . X . . .
B . . . . . . X . . .
C . . X . . . X . . .
D . . X . . . X . . .
E . . X . . . X . . .
F . . X . . . . . . .
G . . . . . . . . . .
H . . . . . . . . . .
I . . . . . – X X X X
J . . . . . . . . . .

Well done! You completed the game in 14 shots


Please email your finished solution zipped up (without binaries and in .zip format not .rar, .7z, etc.)
