<?php
namespace FileReporter;

class FileReporterFilter{
	/**
	 * @var array $files
	 */
	protected $files = [];
	
	/**
	 * @var array $filter
	 */
	protected $filter = [
		'count'  => 0,
		'result' => []
	];

	/**
	 * FileReporterFilter Consctructor.
	 * @param array $files
	 */
	public function __construct($files = []){
		$this->files = $files;
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function repeatsBy($key = ''){
		switch($key){
			case 'hash':
				$this->filter['result'] = $this->groupBy('hash');
			break;

			case 'name':
				$this->filter['result'] = $this->groupBy('name');
			break;

			default:
				$this->filter = [];
			break;
		}

		return $this->filter;
	}

	/**
	 * @param string $key
	 * @param string $operator
	 * @param string|int|bool $value
	 * @return array
	 */
	public function searchBy($key, $operator, $value){
		if(empty($this->files)){
			return $filter;
		}

		$result = [];

		switch($operator){
			case '=':
				foreach($this->files as $file){
					if($file[$key] == $value){
						$this->filter['count']++;
						$result[] = $file;
					}
				}
			break;

			case 'like':
				foreach($this->files as $file){
					if(stripos($file[$key], $value) !== false){
						$this->filter['count']++;
						$result[] = $file;
					}
				}
			break;

			case '<':
				foreach($this->files as $file){
					if($file[$key] < $value){
						$this->filter['count']++;
						$result[] = $file;
					}
				}
			break;

			case '>':
				foreach($this->files as $file){
					if($file[$key] > $value){
						$this->filter['count']++;
						$result[] = $file;
					}
				}
			break;
		}

		$this->filter['result'] = $result;
		return $this->filter;
	}

	/**
	 * @param string $name_key
	 * @return array
	 */
	protected function groupBy($name_key = ''){
		if(empty($this->files)){
			return $filter;
		}

		$keys = [];

		foreach($this->files as $file){
			$keys[] = $file[$name_key];
		}

		$keys = array_count_values($keys);
		$keys_filter = [];
		foreach($keys as $k => $count){
			if($count > 1){
				$keys_filter[$k] = [
					'count' => $count,
					'files' => []
				];
			}
		}

		$this->filter['result'] = [];

		foreach($this->files as $item){
			if(array_key_exists($item[$name_key], $keys_filter)){
				$this->filter['count']++;
				$keys_filter[$item[$name_key]]['files'][] = $item;
			}
		}

		return $keys_filter;
	}
}