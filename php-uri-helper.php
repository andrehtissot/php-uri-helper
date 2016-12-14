<?php
include 'php-uri-helper-uri-object.php';
class URIHelper {
    protected static $applicationPath = null;
    public static function forceApplicationPath($applicationPath){
        self::$applicationPath = $applicationPath;
    }

    public static function currentApplicationPath(){
        if(self::$applicationPath) { return self::$applicationPath; }
        if(empty($_SERVER['APPL_MD_PATH'])) { return ''; }
        return substr($_SERVER['APPL_MD_PATH'], strpos($_SERVER['APPL_MD_PATH'], 'ROOT/')+4); //IIS 6.2
    }

    public static function currentHost(){
        $host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
        return $host ? $host : $_SERVER['SERVER_NAME'];
    }

    public static function currentPort(){
        return isset($_SERVER['SERVER_PORT']) ? intval($_SERVER['SERVER_PORT']) : null;
    }

    public static function currentSSL(){
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
    }

    public static function currentProtocol(){
        if(!isset($_SERVER['SERVER_PROTOCOL'])) { return null; }
        $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
        return substr($sp, 0, strpos($sp, '/' )) . ((self::currentSSL()) ? 's' : '' );
    }

    public static function currentPath(){
        $path = $_SERVER['REQUEST_URI'];
        $splitIndex = strpos($path, '?');
        if($splitIndex !== false)
            $path = substr($path, 0, $splitIndex);
        $applicationRoute = self::currentApplicationPath();
        if($applicationRoute && strpos($path, $applicationRoute) === 0)
            return substr($path, strlen($applicationRoute));
        return $path;
    }

    public static function currentParams(){
        return $_REQUEST;
    }

    public static function currentFull(){
        $port = self::currentPort();
        $protocol = self::currentProtocol();
        $port = (($protocol!=='https' && $port===80) || ($protocol==='https' && $port===443)) ? '' : ":$port";
        return $protocol . '://' . self::currentHost() . $port . $_SERVER['REQUEST_URI'];
    }

    public static function currentApplicationRoot(){
        $port = self::currentPort();
        $protocol = self::currentProtocol();
        $port = (($protocol!=='https' && $port===80) || ($protocol==='https' && $port===443)) ? '' : ":$port";
        return $protocol . '://' . self::currentHost() . $port . self::currentApplicationPath();
    }

    public static function generateFromApplicationRoot(){
        $uri = new \URIHelper\URIObject();
        $uri->host = self::currentHost();
        $uri->port = self::currentPort();
        $uri->path = '/';
        $uri->protocol = self::currentProtocol();
        $uri->external = false;
        return $uri;
    }

    public static function generateFromCurrent(){
        $uri = self::generateFromApplicationRoot();
        $uri->path = self::currentPath();
        $uri->params = self::currentParams();
        return $uri;
    }

    static function from($address, array $options = array()){
        $address = "$address";
        $uri = new \URIHelper\URIObject();
        $applicationRoute = self::currentApplicationRoot();
        $params_string = '';
        if($applicationRoute && strpos($address, $applicationRoute) === 0){
            $pathAndParams = substr($address, strlen($applicationRoute));
            $uri->external = false;
            $uri->host = self::currentHost();
            $uri->port = self::currentPort();
            $uri->protocol = self::currentProtocol();
            $pathAndParams = explode('?', $pathAndParams);
            $uri->path = $pathAndParams[0];
            if(isset($pathAndParams[1]) && !empty($pathAndParams[1]))
                $params_string = $pathAndParams[1];
        } elseif(strpos($address, 'http') === 0) {
            $uri_components = parse_url($address);
            $uri->protocol = $uri_components['scheme'];
            $uri->host = $uri_components['host'];
            $uri->port = empty($uri_components['port']) ? ($uri->protocol === 'https' ? '443' : '80')
                : $uri_components['port'];
            if($applicationRoute && strpos($uri_components['path'], $applicationRoute) === 0
                && $uri->protocol === self::currentProtocol()
                && $uri->host === self::currentHost()
                && $uri->port === self::currentPort()) {
                $uri->path = substr($uri_components['path'], strlen($applicationRoute));
                $uri->external = false;
            } else {
                $uri->path = @$uri_components['path'];
                $uri->external = true;
            }
            if(!empty($uri_components['query']))
                $params_string = $uri_components['query'];
        } else {
            throw new Exception("URI doesn't recognize this \"{$address}\" address", 1);
        }
        if(strpos($address, '#') !== false)
            $uri->hash = substr($address, strpos($address, '#')+1);
        if(!isset($options['parseParams']) || $options['parseParams'] === 'deep')
            $uri->params = self::paramsStringToHash($params_string);
        elseif($options['parseParams'] === 'superficial')
            $uri->params = self::paramsStringToDeepHash($params_string);
        return $uri;
    }

    protected static function paramsStringToHash($paramsAsString){
        if(empty($paramsAsString)) { return array(); }
        $paramsAsArray = array();
        $params = explode('&', $paramsAsString);
        foreach ($params as $param) {
            list($param, $value) = explode('=', $param);
            $paramsAsArray[urldecode($param)] = urldecode($value);
        }
        return $paramsAsArray;
    }

    protected static function paramsStringToDeepHash($paramsAsString){
        if(empty($paramsAsString)) { return array(); }
        $paramsAsArray = array();
        $params = explode('&', $paramsAsString);
        foreach ($params as $param) {
            list($param, $value) = explode('=', $param);
            $param = str_replace('%5D', '', $param);
            $paramsLevels = explode('%5B', $param);
            $lastLevel = array_pop($paramsLevels);
            $currentArray = &$paramsAsArray;
            foreach ($paramsLevels as $level => $param) {
                if(!isset($currentArray[$param]))
                    $currentArray[$param] = array();
                $currentArray = &$currentArray[$param];
            }
            $currentArray[urldecode($lastLevel)] = urldecode($value);
        }
        return $paramsAsArray;
    }
}
