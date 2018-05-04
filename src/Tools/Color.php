<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 09:01
 */

namespace App\Tools;


use Stringy\Stringy;

class Color
{
    private CONST DEFAULT = '#000';
    /**
     * @var Stringy
     */
    private $hexColor;

    /**
     * Color constructor.
     * @param string $htmlColor
     */
    public function __construct($htmlColor = '')
    {
        $this->hexColor = Stringy::create($htmlColor);
        $len = $this->hexColor->length();
        if (($len !== 3 && $len !== 6) || !$this->hexColor->isHexadecimal()) {
            $this->hexColor = Stringy::create(static::DEFAULT);
        }
        if ($this->hexColor->startsWith('#')) {
            $this->hexColor = $this->hexColor->substr(1);
        }
    }

    /**
     * RGBA string from hexa color
     * @param float $opacity
     * @return string
     */
    public function getRgba($opacity = 0.0) {
        if ($this->hexColor->length() === 3) {
            $arr = [
                $this->hexColor->at(0)->repeat(2),
                $this->hexColor->at(1)->repeat(2),
                $this->hexColor->at(2)->repeat(2),
            ];
        } else {
            $arr = [
                $this->hexColor->substr(0, 2),
                $this->hexColor->substr(2, 2),
                $this->hexColor->substr(4, 2),
            ];
        }
        $rgb = array_map('hexdec', $arr);
        if ($opacity <= 0.0 || $opacity > 1.0) {
            return "rgb(".implode(',', $rgb).")";
        }
        $rgb[] = $opacity;
        return "rgba(".implode(',', $rgb).")";
    }

}