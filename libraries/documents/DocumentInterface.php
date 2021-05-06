<?php

namespace App\libraries\documents;

interface DocumentInterface
{
    public function read($path);

    public function write($path, $contents);

    public function rename($path, $newpath);

    public function copy($path, $newpath);

    public function delete($path);
}
