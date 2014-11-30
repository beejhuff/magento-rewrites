<?php

namespace MagentoRewrites;

class Inspect
{
    private $output = [];
    private $file;

    public function run($file)
    {
        $this->file = $file;

        $this->inspectModelsBlocksHelpers($file);
        $this->inspectControllers($file);

        return $this->output;
    }

    private function inspectModelsBlocksHelpers($file)
    {
        $conf = new \SimpleXMLElement($file);

        $matches = $conf->xpath('//rewrite');

        if ($matches) {
            foreach($this->xml2array($matches) as $key => $value) {
                foreach($value as $rewrite => $class) {
                    $this->output['other'] = ['class' => $class, 'rewrite' => $rewrite];
                }
            }
        }

        return $this->output;
    }

    private function inspectControllers($file)
    {
        $conf = new \SimpleXMLElement($file);
        $xpath = '/config/*[name()="frontend" or name()="admin"]/routers/*/args/modules/*[@before]';
        foreach ($conf->xpath($xpath) as $rewrite) {
            var_dump($rewrite);
        }
    }

    private function xml2array($xml)
    {
        $arr = [];

        foreach ($xml as $element) {
            $tag = $element->getName();
            $e = get_object_vars($element);
            if (!empty($e)) {
                $arr[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
            } else {
                $arr[$tag] = trim($element);
            }
        }

        return $arr;
    }
}
