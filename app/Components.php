<?php
namespace App;

class Components
{
    private $components;
    public function __construct()
    {
        
    }
    public function c($name) 
    {
        return $this->components[$name];
    }
    public function register($name, $uri){
        $this->components[$name] = $uri;
    }
}

$components = new Components();