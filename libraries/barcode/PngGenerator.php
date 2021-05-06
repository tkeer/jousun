<?php

namespace App\libraries\barcode;

use App\libraries\barcode\exceptions\UnknownTypeException;
use App\libraries\barcode\types\TypeInterface;
use App\libraries\barcode\types\UPC;

class PngGenerator extends Generator
{
    function generate(string $barcode, string $type): any
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        // generate image using Imagick
        return generateImagickPng($barcodeData);
    }
}
