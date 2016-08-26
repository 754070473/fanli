<?php
namespace Com\Util;

defined('LOG_PATH') or define('LOG_PATH', './logs/');

abstract class Logger
{

     /**
      * 调试日志
      *
      * @access public
      * @param string 				$type
      * @param string 				$data
      * @return void
      */
	public static function debug($type, $data)
	{
		self::write('debug', $type, $data);
	}

     /**
     * 错误日志
     *
     * @access public
     * @param string                    $type
     * @param string                    $data
     * @return void
     */
     public static function error($type, $data)
     {
          self::write('error', $type, $data);
     }

	/**
     * 记录日志
     *
     * @access public
     * @param string 				$file
     * @param string 				$type
     * @param string 				$data
     * @return void
     */
	public static function write($file, $type, $data)
	{
		$data = is_array($data) ? json_encode($data) : $data;
		$log_file = LOG_PATH . $file . '_' . date('Ymd') . '.log';
		$log_data = date('[Y-m-d H:i:s]') . ' ' .$type . ' ' . $data . PHP_EOL;
		@file_put_contents($log_file, $log_data, FILE_APPEND);
	}
}
