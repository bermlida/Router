<?php

class TestParamHandler
{
    public function __invoke($param)
    {
        return $param;
    }

    public function process($param)
    {
        return $param;
    }

    public function processTimesTen($param)
    {
        return $param * 10;
    }

    public function processDividedTen($param)
    {
        return $param / 10;
    }
}
