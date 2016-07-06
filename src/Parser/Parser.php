<?php

namespace ChangelogParser\Parser;

class Parser {
    /** @var array **/
    protected $releases;
    /** @var string **/
    protected $currentRelease;
    /** @var string **/
    protected $currentReleasePart;

    /**
     * @param string $filepath
     * @return array
     */
    public function parse($filepath) {
        foreach($this->parseFile($filepath) as $line) {
            $this->parseLine($line);
        }
        return $this->releases;
    }

    /**
     * @param string $remoteurl
     * @return string|null local path to the fetched changelog
     */
    public function fetchFile($remoteurl) {
        $tokens = parse_url($remoteurl);
        $remotepath = $tokens['path'];
        $remotehost = $tokens['host'];
        $localfilename = str_replace([':', ' ', '/', '\\'], '-', $remotehost . dirname($remotepath) . '-' . basename($remotepath, '.md'));
        $localfetchedfilepath = __DIR__ . '/../../data/cache/' . $localfilename;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remoteurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $remotechangelog = curl_exec($ch);
        $error_log = curl_error($ch);

        curl_close($ch);

        if (!empty($error_log)) {
          throw new \RuntimeException("Failed to fetch changelog from $remoteurl. " . $error_log);
        }
        else {
          file_put_contents($localfetchedfilepath, $remotechangelog);
          return $localfetchedfilepath;
        }
    }

    /**
     * @param string $filepath
     * @throws \RuntimeException
     */
    private function parseFile($filepath) {
        // if the $filepath is a url, fetch and cache the file.
        if(filter_var($filepath, FILTER_VALIDATE_URL)) {
          $filepath = $this->fetchFile($filepath);
        }

        if(($file = fopen($filepath, 'r')) === false) {
            throw new \RuntimeException("The cached changelog at $filepath does not exist");
        }

        while($line = fgets($file)) {
            yield $line;
        }
        fclose($file);
    }

    /**
     * @param string $line
     */
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

    /**
     * @param string $line
     */
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
                    $this->currentRelease = $this->formatReleaseVersion($line);
                    break;
                }
                $this->currentRelease = $this->formatReleaseVersion($parts[0]);
                unset($parts[0]);
                $this->releases[$this->currentRelease]['date'] = trim(implode('-', $parts));

                break;
            case 3:
                $this->currentReleasePart = strtolower(trim(substr($line, 3)));
                break;
        }
    }

    /**
     * @param string $releaseVersion
     * @return string
     */
    private function formatReleaseVersion($releaseVersion) {
        return strtolower(str_replace(['[', ']'], '', trim(substr($releaseVersion, 2))));
    }

    /**
     * @param string $line
     */
    private function parseItem($line) {
        $this->releases[$this->currentRelease]['items'][$this->currentReleasePart][] = trim(substr($line,1));
    }
}
