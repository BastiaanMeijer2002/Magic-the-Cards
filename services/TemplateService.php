<?php

namespace Service;

use ValueError;

class TemplateService
{
    public $variables;

    public function loadFile($filename)
    {
        $filename = "templates/".$filename.".html.template";
        return file_get_contents($filename);
    }

    public function loadStyleSheets($html)
    {
        $styleDirectory = "public/styles";
        $styleSheets = scandir($styleDirectory);
        $styleTag = "<style>";

        foreach($styleSheets as $file) {
            if ($file != '.' && $file != '..' && is_file($styleDirectory. '/' . $file)) {
                $styleTag .= file_get_contents($styleDirectory.'/'.$file);
            }
        }

        $styleTag .= "</style>";


        return $styleTag;

    }

    public function replaceTemplates($matches)
    {
        $key = $matches[1];
        $template= $this->loadFile($key);
        if($this->hasChildTemplates($template) > 0){
            $template = $this->addChildTemplates($template);
        }
        return $template;
    }

    public function addChildTemplates($template)
    {
        $pattern = "/\{\{(.+?)\}\}/";;
        return preg_replace_callback($pattern, function ($matches) {return $this->replaceTemplates($matches);}, $template);
    }

    public function hasChildTemplates($template)
    {
        $pattern = "/\{\{(.+?)\}\}/";
        return preg_match($pattern,$template);
    }

    public function replaceVariables($matches)
    {
        $key = $matches[1];

        if (array_key_exists($key, $this->getVariables())){
            return $this->getVariables()[$key];
        } else {
            throw new ValueError("No data for this key.");
        }
    }

    public function addVariables($template)
    {
        $pattern = "/\{(.+?)\}/";
        return preg_replace_callback($pattern, function ($matches) {return $this->replaceVariables($matches);}, $template);

    }

    public function replaceIf($matches, $elseBlock = false)
    {
        $ifStatement = $matches[1];
        $ifBody = $matches[2];

        if (eval("return".$ifStatement.";")){
            return $ifBody;
        } else if ($elseBlock !== false){
            return $elseBlock;
        } else {
            return '';
        }
    }

    public function handleIfStatements($template)
    {
        $ifPattern = "/\*(.+?)\*(.+?)\*/s";
        $template = preg_replace_callback($ifPattern, function ($matches) {return $this->replaceIf($matches);}, $template);

        $ifElsePattern = "/\*(.+?)\*(.+?)\&&(.+?)\&&/s";
        return preg_replace_callback($ifElsePattern, function ($matches) {return $this->replaceIf($matches, $matches[3]);}, $template);

    }

    public function replaceForEach($matches)
    {
        $statement = $matches[1];
        $body = $matches[2];

        $value = $this->getVariables()[$statement];

        $finalHtml = '';
        $arrayValuePattern = '/\[([^\]]+)\]/';

        foreach ($value as $item) {
            $tempBody = preg_replace_callback($arrayValuePattern, function ($matches) use ($item) {return $item[$matches[1]];}, $body);
            $finalHtml = $finalHtml.$tempBody;
        }

        return $finalHtml;

    }

    public function handleForEachStatements($template)
    {
        $forEachPattern = "/\#(.+?)\#(.+?)\#/s";
        return preg_replace_callback($forEachPattern, function ($matches) {return $this->replaceForEach($matches);}, $template);
    }

    public function render($template, $variables)
    {
        $baseHtml = file_get_contents("public/index.html");
        $template = $this->loadFile($template);
        $this->setVariables($variables);

        $template = $this->addChildTemplates($template);
        $template = $this->handleForEachStatements($template);
        $template = $this->addVariables($template);
//        $template = $this->handleIfStatements($template);

        $finalHtml = preg_replace("/{{base}}/", $template, $baseHtml);
        $finalHtml = preg_replace("/{{style}}/", $this->loadStyleSheets($finalHtml), $finalHtml);
//        echo $finalHtml;
        return $finalHtml;

    }

    public function setVariables(array $variables) {$this->variables = $variables;}

    public function getVariables(){return $this->variables;}

}
