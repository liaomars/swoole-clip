<?php


namespace Thumb\Thumb\Commander;


interface CommanderInterface
{
    public function commandName();

    public function exec(array $args);

    public function help(array $args);
}