<?php

namespace Sds\DoctrineExtensions;

use Doctrine\Common\Cache\FileCache;

class FilesystemCache extends FileCache
{
    const EXTENSION = '.doctrinecache.php';

    /**
     * {@inheritdoc}
     */
    protected $extension = self::EXTENSION;

    /**
     * {@inheritdoc}
     */
    protected function doFetch($id)
    {

        $filename = $this->getFilename($id);
        $value = @include $filename;
        if (!isset($value)){
            return false;
        }

        $lifetime = (integer) $value['lifetime'];
        if ($lifetime !== 0 && $lifetime < time()) {
            return false;
        }
        return unserialize($value['data']);
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        $filename = $this->getFilename($id);
        $value = @include $filename;
        if (!isset($value)){
            return false;
        }

        $lifetime = $value['lifetime'];

        return $lifetime === 0 || $lifetime > time();
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifetime = 0)
    {
        if ($lifetime > 0) {
            $lifetime = time() + $lifetime;
        }

        $filename   = $this->getFilename($id);
        $filepath   = pathinfo($filename, PATHINFO_DIRNAME);

        if ( ! is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }

        $value = [
            'lifetime' => $lifetime,
            'format' => 'standard',
            'data' => serialize($data)
        ];
        return file_put_contents($filename, sprintf('<?php return %s;', var_export($value, true)));
    }
}