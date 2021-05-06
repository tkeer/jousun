<?php

namespace App\libraries\barcode\types;

interface TypeInterface
{
    public function getBarcodeData($code);
}
