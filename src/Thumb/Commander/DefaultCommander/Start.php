<?php


namespace Thumb\Thumb\Commander\DefaultCommander;


use Thumb\Thumb\Commander\CommanderInterface;

class Start implements CommanderInterface
{
    public function commandName()
    {
        return 'start';
    }

    public function exec(array $args)
    {

    }

    public function help(array $args)
    {

    }
}