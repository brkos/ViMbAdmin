<?php
/**
 * OSS Framework
 *
 * This file is part of the "OSS Framework" - a library of tools, utilities and
 * extensions to the Zend Framework V1.x used for PHP application development.
 *
 * Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * All rights reserved.
 *
 * Open Source Solutions Limited is a company registered in Dublin,
 * Ireland with the Companies Registration Office (#438231). We
 * trade as Open Solutions with registered business name (#329120).
 *
 * Contact: Barry O'Donovan - info (at) opensolutions (dot) ie
 *          http://www.opensolutions.ie/
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * It is also available through the world-wide-web at this URL:
 *     http://www.opensolutions.ie/licenses/new-bsd
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@opensolutions.ie so we can send you a copy immediately.
 *
 * @category   OSS
 * @package    OSS_Validate
 * @copyright  Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * @license    http://www.opensolutions.ie/licenses/new-bsd New BSD License
 * @link       http://www.opensolutions.ie/ Open Source Solutions Limited
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @author     The Skilled Team of PHP Developers at Open Solutions <info@opensolutions.ie>
 */

/**
 * @category   OSS
 * @package    OSS_Validate
 * @copyright  Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * @license    http://www.opensolutions.ie/licenses/new-bsd New BSD License
 */

class OSS_Validate_OSSIPv6 extends Zend_Validate_Abstract
{

    const INVALID_REC = 'invalidRec';

    /**
     * Possible error messages
     *
     * @var array
     */
    protected $_messages = array(
        self::INVALID_REC => 'Invalid IPv6 address.'
    );

    /**
     * It will return true if given value is valid AAAA record (IPv6 address)
     *
     * @param strig $value Date string
     * @param null|mixed $context
     * @return bool
     */
    public function isValid( $value, $context = null )
    {
        if( strlen( $value ) < 3 )
            return $value == '::';

        if( strpos( $value, '.' ) )
        {
            $lastcolon = strrpos( $value, ':' );

            $validator = new OSS_Validate_OSSIPv4();

            if( !( $lastcolon && $validator->isValid( substr( $value, $lastcolon + 1 ) ) ) )
                return false;

            $value = substr( $value, 0, $lastcolon ) . ':0:0';
        }

        $dc = strpos( $value, '::' );

        if( $dc === false ) {
            return preg_match( '/\A(?:[a-f0-9]{1,4}:){7}[a-f0-9]{1,4}\z/i', $value ) == 1;
        }

        $colonCount = substr_count( $value, ':' );
        if( $colonCount < 8 && $dc ) {
            return preg_match( '/\A(?::|(?:[a-f0-9]{1,4}:)+):(?:(?:[a-f0-9]{1,4}:)*[a-f0-9]{1,4})?\z/i', $value ) == 1;
        }

        // special case with ending or starting double colon
        if( $colonCount == 8 )
            return preg_match( '/\A(?:::)?(?:[a-f0-9]{1,4}:){6}[a-f0-9]{1,4}(?:::)?\z/i', $value ) == 1;

        return false;
    }

}
