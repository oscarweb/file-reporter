<?php
namespace FileReporter;

class FileReporter{
	const OUTPUT_OBJECT = 'object';
	const OUTPUT_ARRAY  = 'array';	
	const OUTPUT_JSON   = 'json';
	const OUTPUT_SERIALIZE = 'serialize';

	/**
	 * @var string $dir
	 */
	protected $dir = '';

	/**
	 * @var string $dir_cache
	 */
	protected $dir_cache = '';

	/**
	 * @var array $control
	 */
	protected $control = [];

	/**
	 * @var array $report_old
	 */
	protected $report_old = [];

	/**
	 * @var array $report
	 */
	protected $report = [];

	/**
	 * @var string $output
	 */
	protected $output = '';

	/**
	 * @var bool $exception
	 */
	protected $exception = true;

	/**
	 * @var array $files
	 */
	protected $files = [];

	protected $json_pretty_print = false;

	/**
	 * FileReporter Constructor
	 * @param string $dir
	 */
	public function __construct($dir = ''){
		set_exception_handler([$this, 'error']);

		$this->dir = $dir;
		$this->setOutput(self::OUTPUT_OBJECT);
	}

	/**
	 * @return mixed - default object
	 */
	public function search(){
		$args = func_get_args();
		if(count($args) < 2 || count($args) > 3){
			return $this->getError('You must send at least 2 parameters ~ examples: [key] [value] or [key] [operator] [value]');
		}

		foreach($args as $arg){
			if(empty($arg)){
				return $this->getError('You must not send empty arguments.');
				break;
			}
		}

		$key = $args[0]; $operator = '='; $value = '';

		if(count($args) == 2){
			$value = $args[1];
		}

		if(count($args) == 3){
			$operator = $args[1];
			$value = $args[2];
		}

		$filter = new FileReporterFilter($this->files);
		return $this->getOutput($filter->searchBy($key, $operator, $value));
	}

	/**
	 * @return mixed - default object
	 */
	public function repeatsByHash(){
		$filter = new FileReporterFilter($this->files);
		return $this->getOutput($filter->repeatsBy('hash'));
	}

	/**
	 * @return mixed - default object
	 */
	public function repeatsByName(){
		$filter = new FileReporterFilter($this->files);
		return $this->getOutput($filter->repeatsBy('name'));
	}

	/**
	 * @return $this
	 * @throws FileReporterException 
	 */
	public function filterReport(){
		$this->report = [];
		if($this->isSetCache()){
			if(!$this->existControl()){
				$this->iterateDir();
				$this->createReport();
				$this->createControl();
			}

			if(!$this->existReport()){
				$this->iterateDir();
				$this->createReport();
				$this->updateControl();
			}

			$this->report = $this->getFileJson($this->reportFilePath(), false);
		}

		if(!$this->isDirWriteable($this->dir)){
			return $this->getError('Did not establish a directory!');
		}

		$this->iterateDir();

		foreach($this->report['content'] as $item){
			if($item['is']['file']){
				$this->files[] = $item['data'];
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 * @throws FileReporterException
	 */
	public function filterCache(){
		$this->report = [];
		if($this->isSetCache()){
			if($this->existControl()){
				$this->control = $this->getFileJson($this->controlFilePath());
				$this->files = [];

				foreach($this->control->reports as $report){
					if($report->count->files > 0){
						$cache_report = $this->getFileJson($this->reportFilePath($report->route));
						foreach($cache_report->content as $item){
							if($item->is->file){
								$this->files[] = (array)$item->data;
							}
						}
					}
				}

				return $this;
			}
			return $this->getError('File does not exist!');
		}
		return $this->getError('Did not set cache!');
	}

	/**
	 * @return array|mixed
	 * @throws FileReporterException
	 */
	public function getControl(){
		if($this->isSetCache()){
			if($this->existControl()){
				return $this->getOutput($this->getFileJson($this->controlFilePath()));
			}
		}
		return $this->getError('Did not set cache!');
	}

	/**
	 * @return object
	 * @throws FileReporterException
	 */
	public function getReport(){
		if($this->isSetCache()){
			if(!$this->existControl()){
				$this->iterateDir();
				$this->createReport();
				$this->createControl();

				return $this->getOutput();
			}

			if(!$this->existReport()){
				$this->iterateDir();
				$this->createReport();
				$this->updateControl();
				return $this->getOutput();
			}

			return $this->getFileJson($this->reportFilePath());
		}

		if(!$this->isDirWriteable($this->dir)){
			return $this->getError('Did not establish a directory!');
		}

		$this->iterateDir();

		return $this->getOutput();
	}

	/**
	 * @return mixed
	 * @throws FileReporterException
	 */
	public function saveReport(){
		if(!$this->isDirWriteable($this->dir)){
			return $this->getError('Did not establish a directory!');
		}

		if(!$this->isSetCache()){
			return $this->getError('Did not set cache!');
		}
		return $this->getReport();
	}

	/**
	 * @return mixed
	 * @throws FileReporterException
	 */
	public function updateReport(){
		if(!$this->isDirWriteable($this->dir)){
			return $this->getError('Did not establish a directory!');
		}

		if(!$this->isSetCache()){
			return $this->getError('Did not set cache!');
		}

		if(!$this->existControl()){
			return $this->getError('File does not exist ~ control.json');
		}

		if($this->existReportInControl() === false){
			return $this->getError('The report was not found, you must create the file!');
		}

		$this->report_old = $this->getFileJson($this->reportFilePath(), false);

		$this->iterateDir([
			'created' => $this->report_old['created']
		]);

		$this->saveFileJson($this->reportFilePath(), $this->report);

		$this->updateControl();

		return $this->getOutput();
	}

	/**
	 * @return bool
	 * @throws FileReporterException
	 */
	public function deleteReport(){
		if(!$this->isDirWriteable($this->dir)){
			return $this->getError('Did not establish a directory!');
		}
				
		if(!$this->isSetCache()){
			return $this->getError('Did not set cache!');
		}

		if(!$this->existControl()){
			return $this->getError('File does not exist ~ control.json');
		}

		if($this->existReportInControl() !== false){
			$this->deleteRouteInControl();
			//$this->updateControl();
		}

		if(!$this->existReport()){
			return $this->getError('File does not exist ~ '.$this->reportFilePath());
		}

		return @unlink($this->reportFilePath());
	}

	/**
	 * @return bool
	 */
	protected function deleteRouteInControl(){
		$this->control = $this->getFileJson($this->controlFilePath(), false);
		//- remove report by key
		array_splice($this->control['reports'], $this->existReportInControl(), 1);
		
		$this->report = [];

		//- updated control
		$this->control['updated'] = $this->getTime();
		//- return bool
		return $this->saveFileJson($this->controlFilePath(), $this->control);
	}

	/**
	 * @return bool
	 * @throws FileReporterException
	 */
	public function deleteCache(){
		if(!$this->isSetCache()){
			return $this->getError('Did not set cache!');
		}

		if(!$this->existControl()){
			return $this->getError('File does not exist ~ control.json');
		}

		$control = $this->getFileJson($this->controlFilePath(), false);

		if(count($control['reports']) > 0){
			$not_deleted = [];
			foreach($control['reports'] as $report){
				if(@unlink($this->reportFilePath($report['route'])) == false){
					$not_deleted[] = $report['route'];
				}
			}

			if(count($not_deleted) > 0){
				return $this->getError('Some files could not be deleted: '.implode(' | ', $not_deleted));
			}
		}

		if(unlink($this->controlFilePath()) === false){
			return $this->getError('The file could not be deleted ~ control.json');
		}

		return true;
	}


	/**
	 * @return bool
	 * @throws FileReporterException
	 */
	protected function createReport(){
		return $this->saveFileJson($this->reportFilePath(), $this->report);
	}

	/**
	 * @return bool
	 */
	protected function updateControl(){
		if(empty($this->control)){
			$this->control = $this->getFileJson($this->controlFilePath(), false);
		}

		if(!empty($this->report)){
			$report_key = $this->existReportInControl();
			if($report_key !== false){
				$this->control['reports'][$report_key]['updated'] = $this->report['updated'];
				$this->control['reports'][$report_key]['count'] = $this->report['count'];
			}
			else{
				$this->control['reports'][] = [
					'route' => $this->report['route'],
					'name' => $this->report['name'],
					'json' => $this->reportFilePath(),
					'created' => $this->report['created'],
					'updated' => $this->report['updated'],
					'count' => $this->report['count']
				];
			}
		}

		$this->control['updated'] = $this->getTime();

		return $this->saveFileJson($this->controlFilePath(), $this->control);
	}

	/**
	 * @return bool
	 */
	protected function createControl(){
		$time = $this->getTime();

		$this->control = [
			'created' => $time,
			'updated' => $time,
			'reports' => [
				[
					'route' => $this->report['route'],
					'name' => $this->report['name'],
					'json' => $this->reportFilePath(),
					'created' => $this->report['created'],
					'updated' => $this->report['updated'],
					'count' => $this->report['count']
				]
			]
		];

		return $this->saveFileJson($this->controlFilePath(), $this->control);
	}

	/**
	 * @return int|bool
	 */
	protected function existReportInControl(){
		if(empty($this->control)){
			$this->control = $this->getFileJson($this->controlFilePath(), false);
		}

		foreach($this->control['reports'] as $k => $report){
			if($report['route'] == $this->dir){
				return $k;
				break;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	protected function existReport(){
		return $this->existFile($this->reportFilePath());
	}

	/**
	 * @return bool
	 */
	protected function existControl(){
		return $this->existFile($this->controlFilePath());
	}

	/**
	 * @param string $route
	 * @return bool
	 */
	protected function existFile($route){
		return file_exists($route);
	}

	/**
	 * @param array $replace
	 * @return array
	 */
	protected function iterateDir($replace = []){
		$list = new \DirectoryIterator($this->dir);

		$time = $this->getTime();

		$this->report = [
			'route' => $this->dir,
			'name'  => basename($this->dir),
			'created' => $time,
			'updated' => $time,
			'count'   => [
				'dirs' => 0,
				'files' => 0
			],
			'content' => []
		];

		foreach($list as $item){
			if(!$item->isDot()){
				$this->report['content'][] = [
					'type' => $item->getType(),
					'is' => [
						'dir' => $item->isDir(),
						'file' => $item->isFile()
					],
					'data' => [
						'mime' => mime_content_type($item->getPathname()),
						'ext'  => $item->getExtension(),
						'name' => $item->getFileName(),
						'mtime' => $item->getMTime(),
						'route' => $item->getPathname(),
						'hash'  => ($item->isFile())? sha1_file($item->getPathname()) : null
					]
				];

				if($item->isDir()){
					$this->report['count']['dirs']++;
				}

				if($item->isFile()){
					$this->report['count']['files']++;
				}
			}
		}

		if(!empty($replace)){
			foreach($replace as $k => $v){
				if(isset($this->report[$k])){
					$this->report[$k] = $v;
				}
			}
		}

		return $this->report;
	}

	/**
	 * @param string $route
	 * @param bool $obj
	 * @return mixed
	 * @throws FileReporterException if not read file
	 */
	protected function getFileJson($route, $obj = true){
		$file = file_get_contents($route);
		if($file === false){
			return $this->getError('The file could not be read ~ '.$route);
		}
		return ($obj)? json_decode($file) : json_decode($file, true);
	}

	/**
	 * @param string $route
	 * @param array $content
	 * @return bool
	 * @throws FileReporterException if not write file
	 */
	protected function saveFileJson($route, $content){
		$fpc = ($this->json_pretty_print)? 
						file_put_contents($route, json_encode($content, JSON_PRETTY_PRINT)) : 
						file_put_contents($route, json_encode($content));

		if( $fpc === false){
			return $this->getError('The file could not be written ~ '.$route);
		}
		return true;
	}

	public function setJsonPrettyPrint(){
		$this->json_pretty_print = true;
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function getOutput($data = []){
		$data = (empty($data))? $this->report : $data;

		switch($this->output){
			case 'object':
				return $this->reportToObject($data);
			break;

			case 'array':
				return $this->reportToArray($data);
			break;

			case 'json':
				return $this->reportToJson($data);
			break;
			
			case 'serialize':
				return $this->reportToSerialize($data);
			break;

			default:
				return $this->reportToObject($data);
			break;
		}
	}

	/**
	 * @param string $format
	 */
	public function setOutput($format = ''){
		$this->output = $format;
	}

	/**
	 * @param array $data
	 * @return object
	 */
	protected function reportToObject($data){
		return json_decode($this->reportToJson($data));
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected function reportToJson($data){
		return json_encode($data);
	}

	/**
	 * @param array $data
	 * @return object
	 */
	protected function reportToArray($data){
		return $data;
	}

	/**
	 * @param array $data
	 * @return object
	 */
	protected function reportToSerialize($data){
		return serialize($data);
	}

	/**
	 * @param string $dir
	 * @return string
	 */
	protected function reportFilePath($dir = ''){
		return $this->dir_cache.DIRECTORY_SEPARATOR.'report.'.$this->getReportHash($dir).'.json';
	}

	/**
	 * @return string
	 */
	protected function controlFilePath(){
		return $this->dir_cache.DIRECTORY_SEPARATOR.'control.json';
	}

	/**
	 * @return int
	 */
	protected function getTime(){
		return strtotime('now');
	}

	/**
	 * @param string $dir
	 * @return string
	 */
	protected function getReportHash($dir = ''){
		if(empty($dir)){
			$dir = $this->dir;
		}
		return md5($dir);
	}

	/**
	 * @return bool
	 */
	protected function isSetCache(){
		if(empty($this->dir_cache)){
			return false;
		}
		return true;
	}

	/**
	 * @param string $dir_cache
	 */
	public function setCacheDir($dir_cache = ''){
		if($this->isDirWriteable($dir_cache)){
			$this->dir_cache = $dir_cache;
		}
	}

	/**
	 * @param string $dir
	 */
	public function setDir($dir = ''){
		if($this->isDirWriteable($dir)){
			$this->dir = $dir;
		}
	}

	/**
	 * @return string
	 */
	public function getDir(){
		return $this->dir;
	}

	/**
	 * @param string $dir
	 * @return bool
	 * @throws FileReporterException
	 */
	protected function isDirWriteable($dir){
		if(empty($dir)){
			return $this->getError('Route was not sent.');
		}

		if(!is_dir($dir)){
			return $this->getError('It is not a directory ~ '.$dir);
		}

		if(!is_writable($dir)){
			return $this->getError('Can\'t write to directory ~ '.$dir);
		}

		return true;
	}

	public function disableExceptions(){
		$this->exception = false;
	}

	protected function getError($msj){
		if($this->exception){
			throw new FileReporterException($msj);	
		}
		return false;
	}

	/**
	 * @param object $e
	 */
	public function error($e){
		return FileReporterException::getResponse($e);
	}	
}