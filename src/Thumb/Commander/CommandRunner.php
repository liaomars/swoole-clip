<?php


namespace Thumb\Thumb\Commander;


use Thumb\Thumb\Single;

class CommandRunner
{
    use Single;

    public function __construct()
    {
        CommandContainer::getInstance();
    }

    public function run(array $args)
    {

    }
}