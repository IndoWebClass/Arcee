<?php
namespace app\core;

class Server
{
    protected array $pathVars = [];
    public function __construct()
    {

    }

    public function getPath()
    {
        $uri = $_SERVER["REQUEST_URI"] ?? "/";

        $search = "/arcee";
        $path = str_replace($search, "", $uri);

        $search = "?";
        $indexLocation = strpos($path, $search);

        if(is_int($indexLocation))
        {
            $varPath = substr($path, $indexLocation+1);
            $vars = explode("&", $varPath);

            foreach($vars AS $var)
            {
                $keyValuePair = explode("=", $var);
                $key = $keyValuePair[0];
                $value = $keyValuePair[1];

                $this->pathVars[$key] = $value;
            }

            $path = substr($path, 0, $indexLocation);
        }

        return $path;
    }

    public function getPathVars(string $key = NULL)
    {
        if(isset($key))return $this->pathVars[$key];

        return $this->pathVars;
    }

    public function getScriptName()
    {
        return $_SERVER["SCRIPT_NAME"];
    }
}
