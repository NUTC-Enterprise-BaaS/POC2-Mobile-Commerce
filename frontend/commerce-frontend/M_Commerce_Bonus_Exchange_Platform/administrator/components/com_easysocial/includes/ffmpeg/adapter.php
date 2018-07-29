<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

/**
* FFmpeg PHP Class
* 
* @package      FFmpeg
* @version      0.1.3
* @license      http://opensource.org/licenses/gpl-license.php  GNU Public License
* @author       Olaf Erlandsen <olaftriskel@gmail.com>
*/
class FFmpeg
{
    private $STD = ' 2<&1';
    private $quickMethods = array('sameq');

    private $as     =   array(
        'b'         =>  'bitrate',
        'r'         =>  'frameRate',
        'fs'        =>  'fileSizeLimit',
        'f'         =>  'forceFormat',
        'force'     =>  'forceFormat',
        'i'         =>  'input',
        's'         =>  'size',
        'ar'        =>  'audioSamplingFrequency',
        'ab'        =>  'audioBitrate',
        'acodec'    =>  'audioCodec',
        'vcodec'    =>  'videoCodec',
        'std'       =>  'redirectOutput',
        'unset'     =>  '_unset',
        'number'    =>  'videoFrames',
        'vframes'   =>  'videoFrames',
        'y'         =>  'overwrite',
        'log'       =>  'loglevel',
    );
    /**
    *   
    */
    private $FFmpegOptionsAS = array(
        'position'          =>  'ss',
        'duration'          =>  't',
        'filename'          =>  'i',
        'offset'            =>  'itsoffset',
        'time'              =>  'timestamp',
        'number'            =>  'vframes',
    );
    /**
    *   
    */
    private $ffmpeg =   'ffmpeg';
    /**
    *   
    */
    private $options =   array(
        'y' =>  null
    );
    /**
    *   
    */
    private $fixForceFormat = array(
        "ogv"   =>  'ogg',
        "jpeg"  =>  'mjpeg',
        "jpg"   =>  'mjpeg',
        "flash" =>  "flv",
    );
    public $command;

    public function __call($method, $args)
    {
        if (array_key_exists($method, $this->as)) {
            return call_user_func_array(array($this, $this->as[$method]), (is_array($args)) ? $args : array($args));
        } else if(in_array($method, $this->quickMethods)) {
            return call_user_func_array(array($this,'set'),( is_array( $args ) ) ? $args : array( $args ));
        } else {
            throw new Exception('method '. $method .' doesnt exist');
        }
    }

    public function call( $method , $args = array() )
    {
        if( method_exists( $this , $method ) )
        {
            return call_user_func_array( array( $this , $method )  , 
                ( is_array( $args ) ) ? $args : array( $args )
            );
        }else{
            throw new Exception( 'method doesnt exist' );
        }
        return $this;
    }

    public function __construct( $ffmpeg = null ,$input = false )
    {
        $this->ffmpeg($ffmpeg);

        if (!empty($input)) {
            $this->input($input);
        }

        return $this;
    }

    /**
    * @param    string  $std
    * @return   object  Return self
    * @access   public
    */
    public function redirectOutput($std)
    {
        if (!empty($std)) {
            // $this->STD = ' 2>' . $std . ' &';
            $this->STD = '</dev/null >/dev/null 2>' . $std . ' &';
        }

        return $this;
    }

    /**
    * @param    string  $output         Output file path
    * @param    string  $forceFormat    Force format output
    * @return   object  Return self
    * @access   public
    */
    public function output($output = null, $forceFormat = null)
    {
        $this->forceFormat($forceFormat);

        // If there's a log file, we want to pipe all output there
        if (isset($this->logFile) && $this->logFile) {
            $this->redirectOutput($this->logFile);
        }

        $options = array();

        foreach ($this->options as $option => $values) {

            if (is_array($values)) {
                $items = array();
                foreach( $values AS $item => $val )
                {
                    if( !is_null( $val ) )
                    {
                        if( is_array( $val ) )
                        {
                            print_r( $val );
                            $val = join( ',' , $val );
                        }
                        $val = strval( $val );
                        
                        if( is_numeric( $item ) AND is_integer( $item ) )
                        {
                            $items[] = $val;
                        }else{
                            $items[] = $item."=". $val;
                        }
                    }else{
                        $items[] = $item;
                    }
                }
                $options [] = "-".$option." ".join(',',$items);
            } else {
                $options[] = "-" . $option . " " . strval($values);
            }
        }

        $this->command = $this->ffmpeg." ".join(' ',$options)." ".$output . $this->STD;

        return $this;
    }

    /**
    * @param    string  $forceFormat    Force format output
    * @return   object  Return self
    * @access   public
    */
    public function forceFormat($forceFormat)
    {
        if( !empty( $forceFormat ) )
        {
            $forceFormat = strtolower( $forceFormat );
            if( array_key_exists( $forceFormat , $this->fixForceFormat ) )
            {
                $forceFormat = $this->fixForceFormat[ $forceFormat ];
            }
            $this->set('f',$forceFormat,false);
        }
        return $this;
    }
    /**
    * @param    string  $file   input file path
    * @return   object  Return self
    * @access   public
    * @version  1.2 Fix by @propertunist
    */
    public function input ($file)
    {
        if (file_exists($file) AND is_file($file)) {
            $this->set('i', '"'.$file.'"', false);
        } else {
            if (strstr($file, '%') !== false) {
                $this->set('i', '"'.$file.'"', false);
            } else {
                trigger_error ("File $file doesn't exist", E_USER_ERROR);
            }
        }

        $this->file = $file;

        return $this;
    }

    /**
    * @param    string  $size
    * @param    string  $start
    * @param    string  $videoFrames
    * @return   object  Return self
    * @access   public
    * @version  1.2 Fix by @propertunist
    */
    public function thumb($input, $output, $position = '00:00:01')
    {
        $command = $this->ffmpeg . ' ';
        $command .= '-itsoffset -1 -i ' . $input . ' ';
        $command .= '-ss ' . $position . ' ';
        $command .= '-vframes 1 ';
        $command .= '-vf "scale=\'if(gt(a,16/10),1920,-1)\':\'if(gt(a,16/10),-1,1080)\', pad=w=1920:h=1080:x=(ow-iw)/2:y=(oh-ih)/2:color=black" ';
        $command .= '-f mjpeg ';        
        $command .= $output . ' ';

        ob_start();
        passthru($command);
        $output = ob_get_contents();
        ob_end_clean();

        return $this;
    }
    
    /**
    * @return   object  Return self
    * @access   public
    */
    public function clear()
    {
        $this->options = array();
        return $this;
    }
    
    /**
    * @param    string  $transpose  http://ffmpeg.org/ffmpeg.html#transpose
    * @return   object  Return self
    * @access   public
    */
    public function transpose( $transpose = 0 )
    {
        if( is_numeric( $transpose )  )
        {
            $this->options['vf']['transpose'] = strval($transpose);
        }
        return $this;
    }
    /**
    * @return   object  Return self
    * @access   public
    */
    public function vflip()
    {
        $this->options['vf']['vflip'] = null;
        return $this;
    }
    /**
    * @return   object  Return self
    * @access   public
    */
    public function hflip()
    {
        $this->options['vf']['hflip'] = null;
        return $this;
    }
    /**
    * @return   object  Return self
    * @param    $flip   v OR h
    * @access   public
    */
    public function flip( $flip )
    {
        if( !empty( $flip ) )
        {
            $flip = strtolower( $flip );
            if( $flip == 'v' )
            {
                return $this->vflip();
            }
            else if( $flip == 'h' )
            {
                $this->hflip();
            }
        }
        return false;
    }

    /**
     * Adds 
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    /**
    * @param    string  $aspect sample aspect ratio
    * @return   object  Return self
    * @access   public
    */
    public function aspect( $aspect )
    {
        $this->set('aspect',$aspect,false);
    }
    /**
    * @param    string  $b  set bitrate (in bits/s)
    * @return   object  Return self
    * @access   public
    */
    public function bitrate( $b )
    {
        return $this->set('b',$b,false);
    }
    /**
    * @param    string  $r  Set frame rate (Hz value, fraction or abbreviation).
    * @return   object  Return self
    * @access   public
    */
    public function frameRate( $r )
    {
        if( !empty( $r ) AND preg_match( '/^([0-9]+\/[0-9]+)$/' , $r ) XOR is_numeric( $r ) )
        {
            $this->set('r',$r,false);
        }
        return $this;
    }
    /**
    * @param    string  $s  Set frame size.
    * @return   object  Return self
    * @access   public
    */
    public function size( $s )
    {
        if( !empty( $s ) AND preg_match( '/^([0-9]+x[0-9]+)$/' , $s ) )
        {
            $this->set('s',$s,false);
        }
        return $this;
    }
    /**
    * When used as an input option (before "input"), seeks in this input file to position. When used as an output option (before an output filename), decodes but discards input until the timestamps reach position. This is slower, but more accurate.
    *
    * @param    string  $s  position may be either in seconds or in hh:mm:ss[.xxx] form.
    * @return   object  Return self
    * @access   public
    */
    public function position( $ss )
    {
        return $this->set('ss',$ss,false);
    }
    /**
    * @param    string  $t  Stop writing the output after its duration reaches duration. duration may be a number in seconds, or in hh:mm:ss[.xxx] form.
    * @return   object  Return self
    * @access   public
    */
    public function duration( $t )
    {
        return $this->set('t',$t,false);
    }
    /**
    * Set the input time offset in seconds. [-]hh:mm:ss[.xxx] syntax is also supported. The offset is added to the timestamps of the input files.
    *
    * @param    string  $t  Specifying a positive offset means that the corresponding streams are delayed by offset seconds.
    * @return   object  Return self
    * @access   public
    */
    public function itsoffset( $itsoffset )
    {
        return $this->set('itsoffset',$itsoffset,false);
    }
    /**
    *   
    */
    public function audioSamplingFrequency( $ar )
    {
        return $this->set('ar',$ar,false);
    }

    /**
     * Sets the audio bitrate
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function audioBitrate($ab)
    {
        return $this->set('ab', $ab, false);
    }

    /**
     * Sets the audio codec
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function audioCodec($acodec = 'copy')
    {
        $result = $this->set('acodec', $acodec , false);

        if ($acodec == 'aac') {
            $this->set('strict', null);
            $this->set('2', null);
        }

        return $result;
    }

    /**
    *   
    */
    public function audioChannels( $ac )
    {
        $this->set('ac',$ac,false);
    }
    /**
    *   
    */
    public function audioQuality( $aq )
    {
        return $this->set('aq', $a , false );
    }
    /**
    *   
    */
    public function audioDisable()
    {
        return $this->set('an',null,false);
    }
    /**
    * @param    string  $number
    * @return   object  Return self
    * @access   public
    */
    public function videoFrames( $number )
    {
        return $this->set( 'vframes' , $number );
    }
    /**
    *   @param string   $vcodec
    *   @return object Self
    */
    public function videoCodec( $vcodec = 'copy' )
    {
        return $this->set('vcodec' , $vcodec );
    }
    /**
    *   @return object Self
    */
    public function videoDisable()
    {
        return $this->set('vn',null,false);
    }
    /**
    *   @return object Self
    */
    public function overwrite()
    {
        return $this->set('y',null,false);
    }
    /**
    *   @param string   $fs
    *   @return object Self
    */
    public function fileSizeLimit( $fs )
    {
        return $this->set('fs' , $fs , false );
    }
    /**
    *   @param string   $progress
    *   @return object Self
    */
    public function progress( $progress )
    {
        return $this->set('progress',$progress);
    }
    /**
    *   @param integer  $pass
    *   @return object Self
    */
    public function pass( $pass )
    {
        if( is_numeric( $pass ) )
        {
            $pass = intval( $pass );
            if( $pass == 1 OR $pass == 2 )
            {
                $this->options['pass'] = $pass;
            }
        }
        return $this;
    }

    /**
     * Executes the command
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function execute($debug = false)
    {
        if (!$this->command) {
            $this->output();
        }

        if ($debug) {

            $this->getDimension($this->file);
            echo $this->command;exit;
        }
        
        ob_start();
        passthru($this->command);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Given a file, retrieve the dimension of the video
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function getDimension($file)
    {
        $ffprobe = '/opt/local/bin/ffprobe';
        $command = $ffprobe . ' -v error -show_entries stream=width,height -of default=noprint_wrappers=1 ' . $file;

        ob_start();
        passthru($command);
        $output = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Resizes a video
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function resize($input, $output, $bitrate, $size, $logFile, $debug = false)
    {
        $command = $this->ffmpeg . ' ';
        $command .= '-y -i ' . $input . ' -strict -2 ';
        $command .= '-acodec aac -vcodec libx264 -crf 23 ';
        $command .= '-ab ' . $bitrate . ' ';
        $command .= '-vf "scale=-2:\'min(ih,' . $size . ')\'" -movflags faststart ';       
        $command .= $output;
        $command .= ' >/dev/null 2>' . $logFile . ' &';

        if ($debug) {
            echo $command;exit;
        }

        ob_start();
        passthru($command);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Retrieves the content of the log file
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function getLog()
    {
        $contents = JFile::read($this->logFile);

        dump($contents, $this->logFile);
        exit;
    }

    /**
     * Retrieves the name of the log file
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function getLogFileName()
    {
        return basename($this->logFile);
    }

    /**
    * @return   object  Return self
    * @param    string  $append
    * @access   public
    */
    public function ready($append = null)
    {
        if (empty($this->command)) {
            $this->output();
        }
        
        if (empty($this->command)) {
            trigger_error("Cannot execute a blank command",E_USER_ERROR);
            return false;
        }

        return exec($this->command . $append);
    }

    /**
     * Generates a random log file name
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function setLogFile($file)
    {
        $this->logFile = $file;
    }

    /**
    * @return   object  Return self
    * @param    string  ffmpeg
    * @access   public
    */
    public function ffmpeg( $ffmpeg )
    {
        if( !empty( $ffmpeg ) )
        {
            $this->ffmpeg = $ffmpeg;
        }
    }
    /**
    *   @param string   $key
    *   @param string   $value
    *   @param boolen   $append
    *   @return object Self
    */
    public function set( $key , $value = null , $append = false )
    {
        $key = preg_replace( '/^(\-+)/' , '' , $key );
        if( !empty( $key ) )
        {
            if( array_key_exists( $key , $this->FFmpegOptionsAS ) )
            {
                $key = $this->FFmpegOptionsAS[ $key ];
            }
            if( $append === false )
            {
                $this->options[ $key ] = $value;
            }else{
                if( !array_key_exists( $key , $this->options )  )
                {
                    $this->options[ $key ] = array($value);
                }else if( !is_array( $this->options[ $key ] ) )
                {   
                    $this->options[ $key ] = array($this->options[ $key ],$value);
                }else{
                    $this->options[ $key ][] = $value;
                }
            }
        }
        return $this;
    }
    /**
    *   @param string   $key
    *   @return object Self
    */
    public function _unset( $key )
    {
        if( array_key_exists( $key , $this->options ) )
        {
            unset( $this->options[ $key ] ) ;
        }
        return $this;
    }
    /**
    *   @return object Self
    *   @access public
    */
    public function grayScale( )
    {
        return $this->set('pix_fmt','gray');
    }
    
    /**
    * @param    string  $level
    * @return   object  Return self
    * @access   public
    */
    public function loglevel( $level = "verbose" )
    {
        $level = strtolower( $level );
        if( in_array( $level , array("quiet","panic","fatal","error","warning","info","verbose","debug") ) )
        {
            return $this->set('loglevel',$level );
        }else{
            trigger_error(  "The option does not valid in loglevel" );
        }
    }
}