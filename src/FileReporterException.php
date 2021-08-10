<?php
namespace FileReporter;

class FileReporterException extends \Exception{
	/**
	 * @param object $e
	 */
	public static function getResponse($e){
		$r = self::getResponseArray($e);
	
		$m  = '[Error]'.PHP_EOL;
		$m .= 'Message: '.$r['msj'].PHP_EOL;
		$m .= 'Line: '.$r['line'].PHP_EOL;
		$m .= 'File: '.$r['file'].PHP_EOL;
		$m .= PHP_EOL.'[Trace]'.PHP_EOL;
		$m .= $e->getTraceAsString();

		echo $m;
	}

	/**
	 * @param object $e
	 * @return array
	 */
	public static function getResponseArray($e){
		$r = [
			'code' => 0,
			'msj'  => $e->getMessage(),
			'line' => $e->getLine(),
			'file' => $e->getFile(),
			'trace' => $e->getTrace()
		];

		return $r;
	}
}