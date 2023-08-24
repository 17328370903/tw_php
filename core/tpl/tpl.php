<?php

namespace core\tpl;

//視圖解析
class tpl
{
    protected readonly array $labels;
    protected readonly array $config;
    protected string $html;
    protected readonly array $data;
    protected readonly array $file_labels;

    public function __construct(string $route = '', array $data = [])
    {


        $this->file_labels = ['/\{#include (.*)#\}/'];


        $this->labels = [

            "/\{#foreach\s+(.*)#\}/" => '<?php foreach ($1) { ?>',
            '/\{#\/foreach\s*#\}/'   => '<?php }?>',
            '/\{#for (.*)#\}/'        => '<?php for ($1) { ?>',
            '/\{#\/for\s*#\}/'       => '<?php }?>',


            '/\{#if\s+(.*)#\}/'     => '<?php if($1){ ?>',
            '/\{#elseif\s+(.*)#\}/' => '<?php } elseif($1){ ?>',
            '/\{#else#\}/'          => '<?php } else {?>',
            '/\{#\/if#\}/'          => '<?php }?>',
            '/\{#(.*)#\}/'          => '<?=$1 ?>',
        ];

        $this->config = $GLOBALS['appConfig']['template'];

        $this->html = $this->getViewFile($route);
        $this->data = $data;


    }

    //模版解析
    public function tpl_analysis(): void
    {

        foreach ($this->file_labels as $item) {
            preg_match($item, $this->html, $result);
            $contentTemp = file_get_contents(
                ROOT_PATH.DS."app".DS.M.DS.$this->config["view_dir"].DS.trim(
                    $result[1]
                )
            );
            $this->html  = preg_replace($item, $contentTemp, $this->html);
        }

        $patterns     = array_keys($this->labels);
        $replacements = array_values($this->labels);
        $content      = preg_replace(
            $patterns, $replacements, $this->html
        );
        $file         = $this->createTempTplFile();
        file_put_contents($file, $content);

        foreach ($this->data as $key => $item) {
            $$key = $item;
        }

        include_once $file;
        unlink($file);
    }


    //獲取視圖文件
    protected function getViewFile($route): string
    {
        if (empty($route)) {
            $viewFile = ROOT_PATH.DS.'app'.DS.M.DS.$this->config['view_dir'].DS
                .C.DS.A.".{$this->config['suffix']}";
        } else {
            $routeArr = explode(".", $route);
            $count    = count($routeArr);
            if ($count == 1) {
                $viewFile = ROOT_PATH.DS.'app'.DS.M.DS.$this->config['view_dir']
                    .DS.C.DS.$route.".{$this->config['suffix']}";
            } elseif ($count == 2) {
                $viewFile = ROOT_PATH.DS.'app'.DS.M.DS.$this->config['view_dir']
                    .DS.$routeArr[0].DS.$routeArr[1]
                    .".{$this->config['suffix']}";
            } else {
                $viewFile = ROOT_PATH.DS.'app'.DS.$routeArr[0].DS
                    .$this->config['view_dir'].DS.$routeArr[1].DS.$routeArr[2]
                    .".{$this->config['suffix']}";
            }
        }

        return file_get_contents($viewFile);
    }

    //創建緩存文件
    protected function createTempTplFile(): string
    {
        $dir = ROOT_PATH.DS.'runtime'.DS.M.DS.C;

        if ( ! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fileName = A."_".md5(uniqid().time()).".php";

        $fullPath = $dir.DS.$fileName;

        return $fullPath;

    }


}