<?php

// This class is used to encapsulate global state between layers without using $GLOBAL[] or
// relying on the consumer being loaded correctly by the dynamic class autoloader
class HidePollResults_Globals
{
    public static $inputData = null;

    private function __construct() {}
}
