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
        $nodes = ['blocks', 'models', 'helpers'];

        foreach ($nodes as $node) {
            $xpath = "//{$node}//rewrite";

            $matches = $conf->xpath($xpath);
            $this->parseRewriteMatches($matches, $node);
        }

        return $this->output;
    }

    private function inspectControllers($file)
    {
        $conf = new \SimpleXMLElement($file);
        $xpath = '/config/*[name()="frontend" or name()="admin"]/routers/*/args/modules/*[@before]';

        $matches = $conf->xpath($xpath);
        $this->parseRewriteMatches($matches, 'controller');

        return $this->output;
    }

    private function xml2array($xml)
    {
        $arr = [];

        foreach ($xml as $element) {
            $tag = $element->getName() .rand();
            $e = get_object_vars($element);
            if (!empty($e)) {
                $arr[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
            } else {
                $arr[$tag] = trim($element);
            }
        }

        return $arr;
    }

    /**
     * @param $matches
     */
    private function parseRewriteMatches($matches, $type = 'other')
    {
        if ($matches) {
            foreach ($this->xml2array($matches) as $key => $value) {
                foreach ($value as $rewrite => $class) {
                    $this->output[$type][] = ['class' => $class, 'rewrite' => $rewrite];
                }
            }
        }
    }
}
