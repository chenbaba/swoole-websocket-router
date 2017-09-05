<?php
namespace sheld\websocket_router;

/**
* 
*/
class Router
{
	private $wsServer;
	private $frame;

	private $messageKeys;
	//定义消息需要的key，可以按着需要自己修改这个对应的key值
	private $routeKeyFlag='r';
	private $routeMessageFlag='m';
	private $directory;
	private $controller;
	private $method;
	private $routeValidArray;
	
	function __construct($ws,$frame)
	{
		$this->wsServer=$ws;
		$this->frame=$frame;
		$this->messageKeys=[$this->routeKeyFlag,$this->routeMessageFlag];


	}

	public function route()
	{
		$messageFrame=json_decode($this->frame->data);
		if($this->checkKeys($messageFrame))
		{
			$routeStr=$messageFrame->r;
			$message=$messageFrame->m;
			if(preg_match("/^[a-zA-Z0-9\/+]+$/", $routeStr))
			{
				$this->routeValidArray=explode('/', $routeStr);
				$this->directory=$this->findDirectory();
				$controller=$this->controller=$this->findController();
				if($this->controller!=null)
				{
					$method=$this->mothod=$this->findMethod();
					$obj=new $controller;
					if(method_exists($obj, $this->mothod))
					{
						return $obj->$method();
					}
				}
			}
			else
			{
				if(DEBUG)echo "route string must only contain a-z A-Z 0-9 and / .\n";
			}
		}
		return '';
	}

	private function findController()
	{
		if(count($this->routeValidArray)>0)
		{
			$controller=ucfirst($this->routeValidArray[0]);
			$controller_file=$this->directory.$controller.'.php';
			if(file_exists($controller_file))
			{
				include_once($controller_file);
				if(class_exists($controller))
				{
					array_splice($this->routeValidArray, 0,1);
					return $controller;
				}			
			}
			else
			{

			}
		}

		return null;
	}

	private function findMethod()
	{
		if(count($this->routeValidArray)>0)
		{
			$method=$this->routeValidArray[0];
			return $method;
		}
		return null;
	}


	private function findDirectory()
	{
		$directory=CPATH;
		while(count($this->routeValidArray)>0)
		{
			if(file_exists($directory.$this->routeValidArray[0]))
			{
				$directory=$directory.$this->routeValidArray[0].DIRECTORY_SEPARATOR ;
				array_splice($this->routeValidArray, 0,1);
			}
			else
			{
				break;
			}
		}
		return $directory;
	}


	private function checkKeys($messageFrame)
	{
		$keys=$this->messageKeys;
		$checkPass=true;
		foreach ($keys as $key) {
			if(!isset($messageFrame->$key))
			{
				$checkPass=false;
				if(DEBUG)echo "message object does not contain key $key .\n";
			}
		}
		return $checkPass;
	}
}