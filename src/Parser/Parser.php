<?php

namespace ChangelogParser\Parser;

class Parser {
    /** @var array **/
    protected $releases;
    /** @var string **/
    protected $currentRelease;
    /** @var string **/
    protected $currentReleasePart;
    
    public function parse($filepath) {
        foreach($this->parseFile($filepath) as $line) {
            $this->parseLine($line);
        }
        return $this->releases;
    }
    
    private function parseFile($filepath) {
        if(($file = fopen($filepath, 'r')) === false) {
            throw new \RuntimeException("The file $filepath does not exist");
        }
        
        while($line = fgets($file)) {
            yield $line;
        }
        fclose($file);
    }
    
    private function parseLine($line) {
        switch($line{0}) {
            case '#':
                $this->parseTitle($line);
                break;
            case '-':
                $this->parseItem($line);
                break;
        }
    }
    
    private function parseTitle($line) {
        for($i = 0; $i < 3; ++$i) {
            if($line{$i} !== '#') {
                break;
            }
        }
        switch($i) {
            case 2:
                $parts = explode('-', $line);
                if(count($parts) === 1) {
                    $this->currentRelease = trim(substr($line, 2));
                    break;
                }
                $this->currentRelease = trim(substr($parts[0], 2));
                unset($parts[0]);
                $this->releases[$this->currentRelease]['date'] = trim(implode('-', $parts));
                
                break;
            case 3:
                $this->currentReleasePart = trim(substr($line, 3));
                break;
        }
    }
    
    private function parseItem($line) {
        $this->releases[$this->currentRelease]['items'][$this->currentReleasePart][] = trim(substr($line,1));
    }
}