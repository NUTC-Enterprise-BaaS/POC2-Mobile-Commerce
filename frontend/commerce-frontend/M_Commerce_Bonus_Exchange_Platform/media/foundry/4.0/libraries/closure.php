<?php
/**
 * @package   Foundry
 * @copyright Copyright (C) 2010-2013 Stack Ideas Sdn Bhd. All rights reserved.
 * @license   GNU/GPL, see LICENSE.php
 *
 * Foundry is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . '/media/foundry/4.0/joomla/framework.php');
/**
 * Class Minify_JS_ClosureCompiler
 * @package Minify
 */

/**
 * Minify Javascript using Google's Closure Compiler API
 *
 * @link http://code.google.com/closure/compiler/
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 *
 * @todo can use a stream wrapper to unit test this?
 */

class FD40_ClosureCompiler {
    const URL = 'http://deployer.stackideas.com:1280';

    /**
     * Minify Javascript code via HTTP request to the Closure Compiler API
     *
     * @param string $js input code
     * @param array $options unused at this point
     * @return string
     */
    public static function minify($js, array $options = array())
    {
        $obj = new self($options);
        return $obj->min($js);
    }

    /**
     *
     * @param array $options
     *
     * fallbackFunc : default array($this, 'fallback');
     */
    public function __construct(array $options = array())
    {
        $this->_fallbackFunc = isset($options['fallbackMinifier'])
            ? $options['fallbackMinifier']
            : array($this, '_fallback');
    }

    public function min($js)
    {
        $postBody = $this->_buildPostBody($js);
        $bytes = (function_exists('mb_strlen') && ((int)ini_get('mbstring.func_overload') & 2))
            ? mb_strlen($postBody, '8bit')
            : strlen($postBody);
        // if ($bytes > 200000) {
        //     throw new Minify_JS_ClosureCompiler_Exception(
        //         'POST content larger than 200000 bytes'
        //     );
        // }
        $response = $this->_getResponse($postBody);

        if (preg_match('/^Error\(\d\d?\):/', $response)) {
            if (is_callable($this->_fallbackFunc)) {
                $response = "/* Received errors from Closure Compiler API:\n$response"
                          . "\n(Using fallback minifier)\n*/\n";
                $response .= call_user_func($this->_fallbackFunc, $js);
            } else {
                throw new FD40_Minify_JS_ClosureCompiler_Exception($response);
            }
        }
        if ($response === '') {
            $errors = $this->_getResponse($this->_buildPostBody($js, true));
            throw new FD40_Minify_JS_ClosureCompiler_Exception($errors);
        }
        return $response;
    }

    protected $_fallbackFunc = null;

    protected function _getResponse($postBody)
    {
        $ch = curl_init(self::URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        $contents = curl_exec($ch);
        curl_close($ch);

        if (false === $contents) {
            throw new FD40_Minify_JS_ClosureCompiler_Exception(
               "No HTTP response from server"
            );
        }
        return trim($contents);
    }

    protected function _buildPostBody($js, $returnErrors = false)
    {
        return http_build_query(array(
            'js_code' => $js,
            'output_info' => ($returnErrors ? 'errors' : 'compiled_code'),
            'output_format' => 'text',
            'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
            'language'   => 'ECMASCRIPT5'
        ), null, '&');
    }

    /**
     * Default fallback function if CC API fails
     * @param string $js
     * @return string
     */
    protected function _fallback($js)
    {
        require_once(FD40_FOUNDRY_LIB . '/jsmin.php');
        return FD40_JSMinPlus::minify($js);
    }
}

class FD40_Minify_JS_ClosureCompiler_Exception extends Exception {}
