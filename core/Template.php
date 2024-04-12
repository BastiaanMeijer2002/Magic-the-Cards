<?php

namespace Core;

use ValueError;

class Template
{

    public static function loadFile($filename): bool|string
    {
        $filename = "templates/".$filename.".html.template";
        return file_get_contents($filename);
    }

    public static function loadStyleSheets($html): string
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

    public static function replaceTemplates($matches): array|bool|string|null
    {
        $key = $matches[1];
        $template= self::loadFile($key);
        if(self::hasChildTemplates($template) > 0){
            $template = self::addChildTemplates($template);
        }
        return $template;
    }

    public static function addChildTemplates($template)
    {
        $pattern = "/\{\{(.+?)\}\}/";
        return preg_replace_callback($pattern, function ($matches) {return self::replaceTemplates($matches);}, $template);
    }

    public static function hasChildTemplates($template)
    {
        $pattern = "/\{\{(.+?)\}\}/";
        return preg_match($pattern,$template);
    }

    public static function replaceVariables($matches, $variables)
    {
        $key = $matches[1];

        if (array_key_exists($key, $variables)){
            return $variables[$key];
        } else {
            throw new ValueError("No data for this key.");
        }
    }

    public static function addVariables($template, $variables): array|string|null
    {
        $pattern = "/\{(.+?)\}/";
        return preg_replace_callback($pattern, function ($matches) use ($variables) {return self::replaceVariables($matches,$variables);}, $template);

    }

    public static function replaceIf($matches, $elseBlock = false)
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

    public static function handleIfStatements($template): array|string|null
    {
        $ifPattern = "/\*(.+?)\*(.+?)\*/s";
        $template = preg_replace_callback($ifPattern, function ($matches) {return $this->replaceIf($matches);}, $template);

        $ifElsePattern = "/\*(.+?)\*(.+?)\&&(.+?)\&&/s";
        return preg_replace_callback($ifElsePattern, function ($matches) {return $this->replaceIf($matches, $matches[3]);}, $template);

    }

    public static function replaceForEach($matches, $variables): string
    {
        $statement = $matches[1];
        $body = $matches[2];

        $value = $variables[$statement];

        $finalHtml = '';
        $arrayValuePattern = '/\[([^\]]+)\]/';

        foreach ($value as $item) {
            $tempBody = preg_replace_callback($arrayValuePattern, function ($matches) use ($item) {return $item[$matches[1]];}, $body);
            $finalHtml = $finalHtml.$tempBody;
        }

        return $finalHtml;

    }

    public static function handleForEachStatements($template, $variables): array|string|null
    {
        $forEachPattern = "/\#\#(.+?)\#\#(.+?)\#\#/s";
        return preg_replace_callback($forEachPattern, function ($matches) use ($variables) {return self::replaceForEach($matches, $variables);}, $template);
    }

    public static function render($template, $variables): array|string|null
    {
        $baseHtml = file_get_contents("public/index.html");
        $template = self::loadFile($template);

        $template = self::addChildTemplates($template);
        $template = self::handleForEachStatements($template, $variables);
        $template = self::addVariables($template, $variables);
//        $template = $this->handleIfStatements($template);

        $finalHtml = preg_replace("/{{base}}/", $template, $baseHtml);
        $finalHtml = preg_replace("/{{style}}/", self::loadStyleSheets($finalHtml), $finalHtml);
//        echo $finalHtml;
        return $finalHtml;

    }

    public static function generate($template, $variables): string
    {
        $template = self::loadFile($template);
        return self::addVariables($template, $variables);
    }


}
