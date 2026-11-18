<?php
interface SessionManagerInterface
{
    public function startSession();
    public function set($key, $value);
    public function get($key);
    public function destroySession();
    
}