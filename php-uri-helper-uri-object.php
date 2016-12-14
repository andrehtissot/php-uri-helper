<?php
namespace URIHelper;
class URIObject {
    public $host;
    public $protocol;
    public $port;
    public $path;
    public $params;
    public $external;
    public $hash;

    function full(){
        $port = (($this->protocol!=='https' && $this->port=='80') || ($this->protocol==='https' && $this->port=='443'))
            ? '' : ':'.$this->port;
        $applicationRoute = $this->external ? '' : \URIHelper::currentApplicationPath();
        $query = empty($this->params) ? '' : '?'.http_build_query($this->params);
        $hash = empty($this->hash) ? '' : "#{$this->hash}";
        return $this->protocol . '://' . $this->host . $port . $applicationRoute . $this->path . $query . $hash;
    }

    function __toString(){
        return $this->full();
    }

    function format($format = "PROTOCOL://HOST:PORT_OR_HIDDEN_IF_DEFAULT/APPLICATION_ROUTE/PATH?QUERY#HASH"){
        $formatted = str_replace('PROTOCOL://HOST', "{$this->protocol}://HOST", $format);
        $port = (($this->protocol!=='https' && $this->port=='80') || ($this->protocol==='https' && $this->port=='443'))
            ? '' : ':'.$this->port;
        $formatted = str_replace('HOST:PORT_OR_HIDDEN_IF_DEFAULT', "HOST{$port}", $formatted);
        $query = $this->params ? http_build_query($this->params) : '';
        if($query){ $formatted = str_replace('QUERY', $query, $formatted); }
        else { $formatted = str_replace('?QUERY', '', $formatted); }
        if($this->hash){ $formatted = str_replace('HASH', $this->hash, $formatted); }
        else { $formatted = str_replace('#HASH', '', $formatted); }
        $formatted = str_replace('/PATH', $this->path, $formatted);
        $applicationRoute = $this->external ? '' : \URIHelper::currentApplicationPath();
        $formatted = str_replace('/APPLICATION_ROUTE', $applicationRoute, $formatted);
        $formatted = str_replace(array('PROTOCOL', 'HOST', 'PORT_OR_HIDDEN_IF_DEFAULT', 'APPLICATION_ROUTE', 'PATH',
            'QUERY', 'PORT', 'HASH'), array($this->protocol, $this->host, $port, $applicationRoute, $this->path, $query,
            $this->port, $this->hash), $formatted);
        return $formatted;
    }

    public function setParam($key, $value){
        $this->params[$key] = $value;
        return $this;
    }

    public function getParam($key){
        return $this->params[$key];
    }

    public function removeParam($key){
        $this->params[$key] = null;
        unset($this->params[$key]);
        return $this;
    }

    public function setParams(array $valuesBykey){
        $this->params = array_merge($this->params, $valuesBykey);
        return $this;
    }

    public function removeParams(array $keys){
        foreach ($keys as $key){
            $this->params[$key] = null;
            unset($this->params[$key]);
        }
        return $this;
    }

    public function getParams(array $options = array()){
        return $this->params;
    }
}
