<?php


namespace Thumb\Thumb\Commander;


use Thumb\Thumb\Single;

class CommandContainer
{
    use Single;

    private $_container = [];

    public function set($command)
    {
        $commandName = strtolower($command->commandName());
        if (!isset($this->_container[$commandName])) {
            $this->_container[$commandName] = $command;
        }

    }

    public function get($key)
    {
        $key = strtolower($key);
        if (isset($this->_container[$key])) {
            return $this->_container[$key];
        } else {
            return null;
        }
    }

    public function getCommandList()
    {
        return array_keys($this->_container);
    }

    public function hook($commandName, $args)
    {
        $command = $this->get($commandName);
        if ($command) {
            return $command->exec($args);
        }
        return null;
    }
}