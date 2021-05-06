<?php

namespace App\libraries\documents;

class Local extends DocumentAbstract
{
    public function __construct($prefix = '')
    {
        $this->setPathPrefix($prefix);
    }

    public function read($path)
    {
        $location = $this->applyPathPrefix($path);
        $contents = file_get_contents($location);

        return $contents;
    }

    public function write($path, $contents)
    {
        $location = $this->applyPathPrefix($path);

        $size = file_put_contents($location, $contents);

        return $size;
    }

    public function rename($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);

        return rename($location, $destination);
    }

    public function copy($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);

        return copy($location, $destination);
    }

    public function delete($path)
    {
        $location = $this->applyPathPrefix($path);

        return unlink($location);
    }
}
