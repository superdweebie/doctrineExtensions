<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Sds\Common\Crypt\BlockCipherInterface;
use Zend\Crypt\BlockCipher;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ZendBlockCipher implements BlockCipherInterface {

    public static function encrypt($plainText, $key){
        $cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
        $cipher->setKey($key);
        return $cipher->encrypt($plainText);
    }

    public static function decrypt($encryptedText, $key){
        $cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
        $cipher->setKey($key);
        return $cipher->decrypt($encryptedText);
    }
}
