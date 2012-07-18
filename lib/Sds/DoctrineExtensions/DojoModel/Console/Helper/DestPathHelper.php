<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel\Console\Helper;

use Symfony\Component\Console\Helper\Helper;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DestPathHelper extends Helper
{
    protected $destPath;

    public function __construct($destPath)
    {
        $this->destPath = $destPath;
    }

    public function getDestPath()
    {
        return $this->destPath;
    }

    public function getName()
    {
        return 'destPath';
    }
}