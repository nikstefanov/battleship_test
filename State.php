<?php
abstract class State
{
    public const ERROR = 0;
    public const MISS = 1;
    public const HIT = 2;
    public const SUNK = 3;
    public const WIN = 4;
    public const SHOW = 5;
    public const INIT = 6;
    public const FATAL = 7;
}
