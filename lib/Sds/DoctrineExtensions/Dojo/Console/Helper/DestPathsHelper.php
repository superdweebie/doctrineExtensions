<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo\Console\Helper;

use Symfony\Component\Console\Helper\Helper;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DestPathsHelper extends Helper
{
    protected $destPaths;

    public function __construct(array $destPaths)
    {
        $this->destPaths = $destPaths;
    }

    public function getDestPaths()
    {
        return $this->destPaths;
    }

    public function getName()
    {
        return 'destPaths';
    }
}