<?php

namespace App\libraries\documents;

abstract class DocumentAbstract implements DocumentInterface
{
    protected $prefix;

    protected $separator = '/';

    public function setPathPrefix($prefix)
    {
        $this->prefix = rtrim($prefix, '/') . $this->separator;
    }

    public function applyPathPrefix($path)
    {
        return $this->prefix . ltrim($path, '/');
    }
}
