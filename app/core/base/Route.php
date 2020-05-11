<?php

namespace app\base;

class Route {

    private $_route;

    private $_url;

    private $_req;

    private $_params;

    private $_ok;

    public function __construct($_route, $_url)
    {
        $this->_route = '/' . ltrim(rtrim(rtrim($_route), '/'), '/') . '/';
        $this->_url = $_url;

        $this->_ok = $this->parseRoute();
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function isOk()
    {
        return $this->_ok;
    }

    private function getRegex($pattern){
        if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern))
            return false; // Invalid pattern
    
        // Turn "(/)" into "/?"
        $pattern = preg_replace('#/#', '/?', $pattern);
    
        // Create capture group for ":parameter"
        $allowedParamChars = '[a-zA-Z0-9\_\-]+';
        $pattern = preg_replace(
            '/:(' . $allowedParamChars . ')/',   # Replace ":parameter"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );
    
        // Create capture group for '{parameter}'
        $pattern = preg_replace(
            '/{('. $allowedParamChars .')}/',    # Replace "{parameter}"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );
    
        // Add start and end matching
        $patternAsRegex = "@^" . $pattern . "$@D";
    
        return $patternAsRegex;
    }

    private function parseRoute()
    {
        $patternAsRegex = $this->getRegex($this->_route);

        $ok = false;

        if ($ok = !!$patternAsRegex) {
        // We've got a regex, let's parse a URL
            if ($ok = preg_match($patternAsRegex, $this->_url, $matches)) {
                // Get elements with string keys from matches
                // var_dump($this->_route);
                $this->_params = array_intersect_key(
                    $matches,
                    array_flip(array_filter(array_keys($matches), 'is_string'))
                );
            }
        }

        return $ok;
    }
}