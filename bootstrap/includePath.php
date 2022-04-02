<?php

namespace Bootstrap;

class IncludePath {

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function getFilesFromRoute(string $route){
        $added = '/';
        $Suffix = '*.php';
        $result = [];
        while(!$this->isEmptyDir($route . $added)){
            $filesRoute = $this->getallFilesRoute($route , $added , $Suffix);
            foreach ($filesRoute as $file) {
                array_push($result,$file);
            }
            $added .= '*/';
        }
        return $result;
    }

    public function addRequire(array $filesRoute){
        foreach ($filesRoute as $file) {
            require($file);   
        }
    }

    public function getallFilesRoute(string $route , string $added , string $Suffix){
        return glob($route . $added . $Suffix);
    }

    public function isEmptyDir(string $dir){return empty(glob($dir));}

    public function addAllRequiredFiles(){
        foreach($this->paths as $path){
            $this->addRequire($this->getFilesFromRoute($path));
        }
    }
}







