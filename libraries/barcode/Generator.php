<?php

namespace App\libraries\barcode;

use App\libraries\barcode\exceptions\UnknownTypeException;
use App\libraries\barcode\types\PDF417;
use App\libraries\barcode\types\TypeInterface;
use App\libraries\barcode\types\UPC;

abstract class Generator
{
    const TYPE_UPC = 'UPC';
    const TYPE_PDF_417 = 'PDF417';

    protected function getBarcodeData(string $code, string $type)
    {
        $barcodeDataBuilder = $this->createDataBuilderForType($type);

        return $barcodeDataBuilder->getBarcodeData($code);
    }

    protected function createDataBuilderForType(string $type): TypeInterface
    {
        switch ($type) {
            case self::TYPE_UPC:
                return new UPC();

            case self::TYPE_PDF_417:
                return new PDF417();
        }

        throw new UnknownTypeException();
    }

    abstract function generate(string $barcode, string $type): any;
}
