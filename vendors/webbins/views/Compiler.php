<?php namespace Webbins\Views;

use Exception;
use Webbins\Config\Config;

class Compiler {
    /**
     * Tags used in templating.
     */
    private $tags = array(
        'open'       => '\{\{',
        'close'      => '\}\}',
        'end'        => 'W.end',
        'extends'    => 'W.extends',
        'include'    => 'W.include',
        'render'     => 'W.render',
        'block'      => 'W.block'
    );

    /**
     * All template files must use the choosen extension.
     * e.g index.webbins.html
     * @var  string
     */
    private $extension = 'webbins.html';

    /**
     * If the compiler don't find a template file, then it
     * will look for a fallback. Notice that this file
     * won't be compiled as a template, just an ordinary
     * page.
     * @var  string
     */
    private $fallbackExtension = 'html';

    /**
     * A compiled outcome will have a hashed name and have
     * the following extension.
     * @var  string
     */
    private $compiledExtension = 'webbins';

    /**
     * Stores the accepted globla namespaces from the config
     * file.
     * @var  array
     */
    private $namespaces;

    /**
     * Stores the path to all views.
     * @var  string
     */
    private $viewsPath;

    /**
     * Stores the path to all temporary views.
     * @var  string
     */
    private $tmpViewsPath;

    /**
     * Stores the base path to the application.
     * @var  string
     */
    private $basePath;

    /**
     * Stores the page that the user tries to open.
     * @var  string
     */
    private $page;

    /**
     * Stores the parameters that comes with the page.
     * @var  array
     */
    private $params;

    /**
     * Is true if files are supposed to be stored.
     * @var  bool
     */
    private $storing;

    /**
     * Used to determine if the compiler should clean up afterwards.
     * @var  bool
     */
    private $cleanup;

    /**
     * Construct.
     */
    public function __construct($page, $params=array(), $storing=true, $cleanup=true) {
        $this->page = $page;
        $this->params = $params;
        $this->storing = $storing;
        $this->cleanup = $cleanup;
        $this->viewsPath = Config::get('views');
        $this->tmpViewsPath = Config::get('tmpViews');
        $this->namespaces = Config::get('namespaces');
        $this->basePath = Config::get('path');
    }

    /**
     * Searches for a template file, if found, it will load the
     * template and try to compile it.
     *
     * If no template file were found, search for a fallback,
     * load the fallback and return the code without compiling.
     * @throws  Exception
     * @return  string
     */
    public function compile() {
        $url = $this->viewsPath.'/'.$this->page.'.'.$this->extension;

        // if no template file were found, try with a fallback file.
        if (!is_file($url)) {
            $url = $this->viewsPath.'/'.$this->page.'.'.$this->fallbackExtension;
            // throw an error if no fallback was found.
            if (!is_file($url)) {
                throw new Exception('Couldn\'t locate '.$url);
            }
            return $this->load($url);
        }

        $code = $this->load($url);
        $code = $this->compileIncludes($code);
        $code = $this->compileExtends($code);
        $code = $this->compileRenders($code);
        $code = $this->compileEvals($code);

        $code = $this->clean($code);

        if ($this->storing) {
            $code = $this->addNamespaces($code);

            $compiledPage = $this->storeCompiledCode($this->page, $code);
            return $this->runCompiledCode($compiledPage, $this->params);
        }

        return $code;
    }

    /**
     * Tries to load the page.
     * @throws  Exception
     * @return  string
     */
    private function load($url) {
        if (!$code = file_get_contents($url)) {
            throw new Exception('Couldn\'t open '.$url);
        }
        $code = $this->absolutePaths($code);
        return $code;
    }

    /**
     * Stores the compiled code as a temporary file on the
     * server.
     * @param   string  $page
     * @param   string  $code
     * @throws  Exception
     * @return  string
     */
    private function storeCompiledCode($page, $code) {
        $page = md5($page).'.'.$this->compiledExtension;
        if (!file_put_contents($this->tmpViewsPath.'/'.$page, $code)) {
            throw new Exception('Couldn\'t save the compiled code.');
        }

        return $page;
    }

    /**
     * Runs the compiled code from the temporary file.
     * Creates variables from the passed parameters on
     * the fly. Then uses output buffering strategy when
     * running the code.
     * @param   string  $page
     * @param   array   $params
     * @return  string
     */
    private function runCompiledCode($page, $params) {
        foreach ($params as $key => $value) {
            ${$key} = $value;
        }
        ob_start();
        require($this->tmpViewsPath.'/'.$page);
        return ob_get_clean();
    }

    /**
     * Compiles "extends" commands.
     * @param   string  $code
     * @return  string
     */
    private function compileExtends($code) {
        if (preg_match('/[^\\\\]?'.$this->tags['extends'].'\([\'|\"](.+)[\'|\"]\)/', $code, $matches)) {
            // store the extends command: "W.extends('whatever')"
            $extendsCmd = $matches[0];

            $compiler = new Compiler($matches[1], array(), false, false);
            $extendedCode = $compiler->compile();

            // remove the extends command from original code (only matches once)
            $extendsCmd = preg_quote($extendsCmd, '/');
            $code = preg_replace('/'.$extendsCmd.'/', '', $code, 1);

            return $extendedCode.$code;
        }

        return $code;
    }

    /**
     *  Compiles "include" commands.
     *  @param   string  $code
     *  @return  string
     */
    private function compileIncludes($code) {
        if (preg_match_all('/[^\\\\]'.$this->tags['include'].'\([\'|\"](.+?)[\'|\"]\)/', $code, $matches)) {
            $keys = $matches[0];
            $pages = $matches[1];
            for ($i=0; $i<count($keys); $i++) {
                $compiler = new Compiler($pages[$i], array(), false, false);
                $includeCode = $compiler->compile();
                $code = str_replace($keys[$i], $includeCode, $code);
            }
        }

        return $code;
    }

    /**
     *  Compiles "render" commands.
     *  @param   string  $code
     *  @return  string
     */
    private function compileRenders($code) {
        if (preg_match_all('/[^\\\\]'.$this->tags['block'].'\([\'|\"](.+?)[\'|\"]\)(.+?)[^\\\\]'.$this->tags['end'].'/s', $code, $matches)) {
            $default = $matches[0];
            $keys = $matches[1];
            $values = $matches[2];
            for ($i=0; $i<count($keys); $i++) {
                $code = str_replace($default[$i], '', $code);
                $code = preg_replace('/'.$this->tags['render'].'\([\'|\"]'.$keys[$i].'[\'|\"]\)/', $values[$i], $code);
            }
        }

        return $code;
    }

    /**
     *  Compile "eval" ("{{ some code; }}") commands.
     *  @param   string  $code
     *  @return  string
     */
    private function compileEvals($code) {
        if (preg_match_all('/[^\\\\]'.$this->tags['open'].'\s?(.+?)\s?'.$this->tags['close'].'/s', $code, $matches)) {
            $keys = $matches[0];
            $values = $matches[1];
            for ($i=0; $i<count($keys); $i++) {
                $key = substr($keys[$i], 1);
                $value = trim($values[$i]);
                $code = preg_replace('/'.preg_quote($key, '/').'/s', '<?php '.$value.' ?>', $code, 1);
            }
        }
        return $code;
    }

    /**
     *  Add global namespaces.
     *  @param   string  $code
     *  @return  string
     */
    private function addNamespaces($code) {
        if (!$this->namespaces) {
            return $code;
        }

        $tmp = '';
        foreach ($this->namespaces as $namespace) {
            $tmp .= 'use '.$namespace.';';
        }
        $namespace = '<?php '.$tmp.'?>';

        return $namespace.$code;
    }

    /**
     * Clean up code.
     * @param   string  $code
     * @return  string
     */
    private function clean($code) {
        if ($this->cleanup) {
            // if a render were found but had nothing to load, remove it.
            $code = preg_replace('/[^\\\\]'.$this->tags['render'].'\([\'|\"](.+?)[\'|\"]\)/', '', $code);
            // clean up escaped code.
            $code = preg_replace('/\\\\(W\.[a-z]+.+?)/', '$1', $code);
            $code = preg_replace('/\\\\(\{\{)/', '$1', $code);
        }
        return $code;
    }

    /**
     *  Forces all href="" and src="" to look from root
     *  directory automagically.
     *  @param   string  $code
     *  @return  string
     */
    private function absolutePaths($code) {
        return preg_replace('/(href|src)=([\"|\'])(?!http:\/\/|https:\/\/|\/\/)(.+?)([\"|\'])/', '$1=$2/'.$this->basePath.'/$3$4', $code);
    }
}
