<?php
/**
 * WebPHPack v1.2.2
 * webpack PHP alternative
 * 
 * @package phreak 
 * @author Simeon Lyubenov <lyubenov@gmail.com> www.webdevlabs.com
 *
 */

namespace Phreak;

class WebPHPack
{
    private $outputHTML;
    public $matchString;
    public $outputURL;
    public $outputPath;
    public $jsPath, $cssPath;
    public $excludeCSS, $excludeJS;    
    public $outputJSfilename, $outputCSSfilename;    
    public $caching;

    public function __construct($inputHTML)
    {
        $this->outputHTML = $inputHTML;
        $this->matchString = "//dashmash.ddns.net";
        $this->jsPath = 'script/';
        $this->cssPath = 'style/css';
        $this->outputPath = 'cache';
        $this->outputURL = 'https://dashmash.ddns.net/cache/';
        $this->outputJSfilename = 'dash.js';
        $this->outputCSSfilename = 'style.css';
        $this->excludeJS = [];
        $this->excludeCSS = [];
        $this->caching = false;

    }

    public function output()
    {
        return $this->outputHTML;
    }

    public function combineJS ()
    {
        $pma = preg_match_all('/<script[^>]*src="([^"]*)\.js[^>]*"[^>]*><\/script>/', $this->outputHTML, $matches);
        
        if (($pma !== false && $pma > 0) && (!file_exists($this->outputPath.'/'.$this->outputJSfilename) || $this->caching===false)) {
            $jscombined = "";
            foreach ($matches[1] as $match) {
                if (strpos($match, $this->matchString) !== false) {
                    if (in_array(basename($match), $this->excludeJS)) {continue;}					
                    // read all javascript files and combine them
                    $jscombined .= file_get_contents($this->jsPath.'/'.basename($match).'.js');
                }
            }    
            file_put_contents($this->outputPath.'/'.$this->outputJSfilename, $jscombined);
        }
        $newsrc = $this->outputHTML;
        foreach ($matches[0] as $match) {
            if (strpos($match, $this->matchString) !== false) {
                foreach ($this->excludeJS as $mtc) {
                    if (strpos($match, $mtc)!==false) {continue 2;}    
                }
                // read all javascript and remove from html source
                $newsrc = str_replace($match, '', $newsrc);
            }
        }
        clearstatcache();
        $filetime = filemtime($this->outputPath.'/'.$this->outputJSfilename);
        $newsrc = str_replace("</head>","<script src=".$this->outputURL.'/'.$this->outputJSfilename.'?'.$filetime." async></script></head>", $newsrc);
        $this->outputHTML = $newsrc;
        return $this;
    }

    public function combineCSS()
    {
        $pma = preg_match_all('/<link[^>]*href="([^"]*)\.css[^>]*"[^>]*>/', $this->outputHTML, $matches);

        if (($pma !== false && $pma > 0) && (!file_exists($this->outputPath.'/'.$this->outputCSSfilename) || $this->caching===false)) {
            $csscombined = "";
            foreach ($matches[1] as $match) {
                if (strpos($match, $this->matchString) !== false) {
                if (in_array(basename($match), $this->excludeCSS)) {continue;}					
                    // read all css files and combine them
                    $csscombined .= file_get_contents($this->cssPath.'/'.basename($match).'.css');					
                }
            }
            file_put_contents($this->outputPath.'/'.$this->outputCSSfilename, $csscombined);
        }
        $newsrc = $this->outputHTML;
        foreach ($matches[0] as $match) {
            if (strpos($match, $this->matchString) !== false) {
                foreach ($this->excludeCSS as $mtc) {
                    if (strpos($match, $mtc)!==false) {continue 2;}    
                }
                // read all css and remove from html source
                $newsrc = str_replace($match, '', $newsrc);
            }
        }
        clearstatcache();
        $filetime = filemtime($this->outputPath.'/'.$this->outputCSSfilename);
        $newsrc = str_replace('</head>', '<link href="'.$this->outputURL.'/'.$this->outputCSSfilename.'?'.$filetime.'" rel="stylesheet">'.'</head>', $newsrc);
        $this->outputHTML = $newsrc;
        return $this;
    }

}
