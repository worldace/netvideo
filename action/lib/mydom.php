<?php

$html = new myDOM();
$html->生追加("body", "長男", '<p class="b a">aa</p><p class="a">bb</p>');
$html->生追加(".a", "末っ子", '<div></div>');

print_r();
print $html;
class myDOM{
    private $doc;

    public function __construct($str = '<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title></title></head><body></body></html>'){
        libxml_use_internal_errors(true);
        $this->doc = new DOMDocument(); // https://secure.php.net/manual/ja/class.domdocument.php
        $this->doc->encoding = "utf-8";
        $this->doc->formatOutput = true;
        $this->doc->loadHTML($str, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_COMPACT); // https://php.net/manual/ja/libxml.constants.php
    }

    public function 内容($selector, $value = null){
        $where = $this->検索($selector);

        if($value === null){
            $return = [];
            for($i = 0; $i < $where->length; $i++){
                $return[] = $where[$i]->textContent;
            }
            return $return;
        }
        else{
            for($i = 0; $i < $where->length; $i++){
                $where[$i]->textContent = $value;
            }
        }
    }

    public function 属性($selector, $name, $value = null){
        $where = $this->検索($selector);

        if($value === null){
            $return = [];
            for($i = 0; $i < $where->length; $i++){
                $return[] = $where[$i]->getAttribute($name);
            }
            return $return;
        }
        else{
            for($i = 0; $i < $where->length; $i++){
                $where[$i]->setAttribute($name, $value);
            }
        }
    }

    public function 属性削除($selector, $name){
        $where = $this->検索($selector);
        for($i = 0; $i < $where->length; $i++){
            $where[$i]->removeAttribute($name);
        }
    }

    public function 生追加($selector, $relation, $str){
        $where = $this->検索($selector);
        $add   = $this->doc->createDocumentFragment();

        switch($relation){
            case "兄":
                for($i = 0; $i < $where->length; $i++){
                    $add->appendXML($str);
                    $where[$i]->parentNode->insertBefore($add, $where[$i]);
                }
                break;
            case "弟":
                for($i = 0; $i < $where->length; $i++){
                    $add->appendXML($str);
                    $where[$i]->parentNode->insertBefore($add, $where[$i]->nextSibling);
                }
                break;
            case "長男":
                for($i = 0; $i < $where->length; $i++){
                    $add->appendXML($str);
                    $where[$i]->insertBefore($add, $where[$i]->firstChild);
                }
                break;
            case "末っ子":
                for($i = 0; $i < $where->length; $i++){
                    $add->appendXML($str);
                    $where[$i]->appendChild($add);
                }
                break;
        }
    }

    public function 削除($selector){
        $where = $this->検索($selector)[0];
        if(!$where){ return false; }
        $where->parentNode->removeChild($where);
    }

    public function HTML($selector = null){
        if($selector === null){
            return $this->doc->saveHTML();
        }
        else{
            $where = $this->検索($selector)[0];
            if(!$where){ return false; }
            return $this->doc->saveHTML($where);
        }
    }

    public function __toString(){
        return $this->doc->saveHTML();
    }


    private function 検索($selector){
        $xpath  = new DOMXPath($this->doc); // https://secure.php.net/manual/ja/class.domxpath.php
        $return = $xpath->query($this->selector2XPath($selector)); //DOMNodeではなくDOMNodeList(複数形)が返る
        return $return ?? [];
    }

    /**
     * HTML_CSS_Selector2XPath.php The MIT License
     * Copyright (c) 2008 Daichi Kamemoto <daikame@gmail.com>
     * Copyright (c) 2009 Daichi Kamemoto <daikame@gmail.com>, TANAKA Koichi <tanaka@ensites.com>
     */
    private function selector2XPath($input_selector, $throw_exception = false){
        $regex = [
            'element'    => '/^(\*|[a-z_][a-z0-9_-]*|(?=[#:.\[]))/i',
            'id_class'   => '/^([#.])([a-z0-9*_-]*)/i',
            'attribute'  => '/^\[\s*([^~|=\s]+)\s*([~|]?=)\s*"([^"]+)"\s*\]/',
            'attr_box'   => '/^\[([^\]]*)\]/',
            'attr_not'   => '/^:not\(([^)]*)\)/i',
            'pseudo'     => '/^:([a-z0-9_-]+)(\(\s*([a-z0-9_\s+-]+)\s*\))?/i',
          //'combinator' => '/^(\s*[>+~\s])/i',
          //'comma'      => '/^(,)/',
            'combinator_or_comma' => '/^(\s*[>+~\s,])/i',
        ];
        $parts[] = '//';
        $last = '';
        $selector = trim($input_selector);
        $element = true;

        $pregMatchDelete = function ($pattern, &$subject, &$matches){ // 正規表現でマッチをしつつ、マッチ部分を削除
            if (preg_match($pattern, $subject, $matches)) {
                $subject = substr($subject, strlen($matches[0]));
                return true;
            }
        };

        while ((strlen(trim($selector)) > 0) && ($last != $selector)){
            $selector = trim($selector);
            $last = trim($selector);

            // Elementを取得
            if($element){
                if ($pregMatchDelete($regex['element'], $selector, $e)){
                    $parts[] = $e[1]==='' ? '*' : $e[1];
                }
                elseif($throw_exception) {
                    throw new UnexpectedValueException("parser error: '$input_selector' is not valid selector.(missing element)");
                }
                $element = false;
            }

            // IDとClassの指定を取得
            if($pregMatchDelete($regex['id_class'], $selector, $e)) {
                switch ($e[1]){
                    case '.':
                        $parts[] = '[contains(concat( " ", @class, " "), " ' . $e[2] . ' ")]';
                        break;
                    case '#':
                        $parts[] = '[@id="' . $e[2] . '"]';
                        break;
                    default:
                        if($throw_exception) throw new LogicException("Unexpected flow occured. please conntact authors.");
                        break;
                }
            }

            // atribauteを取得
            if($pregMatchDelete($regex['attribute'], $selector, $e)) {
                switch ($e[2]){ // 二項(比較)
                    case '!=':
                        $parts[] = '[@' . $e[1] . '!=' . $e[3] . ']';
                        break;
                    case '~=':
                        $parts[] = '[contains(concat( " ", @' . $e[1] . ', " "), " ' . $e[3] . ' ")]';
                        break;
                    case '|=':
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '" or starts-with(@' . $e[1] . ', concat( "' . $e[3] . '", "-"))]';
                        break;
                    default:
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '"]';
                        break;
                }
            }
            else if ($pregMatchDelete($regex['attr_box'], $selector, $e)) {
                $parts[] = '[@' . $e[1] . ']';  // 単項(存在性)
            }

            // notつきのattribute処理
            if ($pregMatchDelete($regex['attr_not'], $selector, $e)) {
                if ($pregMatchDelete($regex['attribute'], $e[1], $sub_e)) {
                    switch ($sub_e[2]){ // 二項(比較)
                        case '=':
                            $parts[] = '[@' . $sub_e[1] . '!=' . $sub_e[3] . ']';
                            break;
                        case '~=':
                            $parts[] = '[not(contains(concat( " ", @' . $sub_e[1] . ', " "), " ' . $sub_e[3] . ' "))]';
                            break;
                        case '|=':
                            $parts[] = '[not(@' . $sub_e[1] . '="' . $sub_e[3] . '" or starts-with(@' . $sub_e[1] . ', concat( "' . $sub_e[3] . '", "-")))]';
                            break;
                        default:
                            break;
                    }
                }
                else if ($pregMatchDelete($regex['attr_box'], $e[1], $e)) {
                    $parts[] = '[not(@' . $e[1] . ')]'; // 単項(存在性)
                }
            }

            // 疑似セレクタを処理
            if ($pregMatchDelete($regex['pseudo'], $selector, $e)) {
                switch ($e[1]) {
                    case 'first-child':
                        $parts[] = '[not(preceding-sibling::*)]';
                        break;
                    case 'last-child':
                        $parts[] = '[not(following-sibling::*)]';
                        break;
                    case 'nth-child':
                        // CSS3
                        if (is_numeric($e[3])) {
                            $parts[] = '[count(preceding-sibling::*) = ' . $e[3] . ' - 1]';
                        }
                        else if ($e[3] == 'odd') {
                            $parts[] = '[count(preceding-sibling::*) mod 2 = 0]';
                        }
                        else if ($e[3] == 'even') {
                            $parts[] = '[count(preceding-sibling::*) mod 2 = 1]';
                        }
                        else if (preg_match('/^([+-]?)(\d*)n(\s*([+-])\s*(\d+))?\s*$/i', $e[3], $sub_e)) {
                            $coefficient = $sub_e[2]==='' ? 1 : intval($sub_e[2]);
                            $constant_term = array_key_exists(3, $sub_e) ?  intval($sub_e[4]==='+' ? $sub_e[5] : -1 * $sub_e[5]) : 0;
                            if($sub_e[1]==='-') {
                                $parts[] = '[(count(preceding-sibling::*) + 1) * ' . $coefficient . ' <= ' . $constant_term . ']';
                            }
                            else { // '+' or ''
                                $parts[] = '[(count(preceding-sibling::*) + 1) ' . ($coefficient===0 ? '': 'mod ' . $coefficient . ' ') . '= ' . ($constant_term>=0 ? $constant_term : $coefficient + $constant_term) . ']';
                            }
                        }
                        break;
                    case 'lang':
                        $parts[] = '[@xml:lang="' . $e[3] . '" or starts-with(@xml:lang, "' . $e[3] . '-")]';
                        break;
                    default:
                        break;
                }
            }

             // combinatorとカンマがあったら、区切りを追加。また、次は型選択子又は汎用選択子でなければならない
            if ($pregMatchDelete($regex['combinator_or_comma'], $selector, $e)) {
                switch (trim($e[1])) {
                    case ',':
                        $parts[] = ' | //*';
                        break;
                    case '>':
                        $parts[] = '/';
                        break;
                    case '+':
                        $parts[] = '/following-sibling::*[1]/self::';
                        break;
                    case '~': // CSS3
                        $parts[] = '/following-sibling::';
                        break;
                  //case '':
                    default:
                        $parts[] = '//';
                        break;
                }
                $element = true;
            }
        }

        return implode('', $parts);
    }
}