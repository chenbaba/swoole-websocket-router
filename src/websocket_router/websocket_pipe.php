<?php
namespace sheld\websocket_router;

class Websocket_pipe
{
	private $websocketServer;
	private $messageKeys;
	//定义消息需要的key，可以按着需要自己修改这个对应的key值
	private $routeKeyFlag='r';
	private $routeMessageFlag='m';

	public function __construct($wsServer)
	{
		$this->websocketServer=$wsServer;
		$this->messageKeys=[$this->routeKeyFlag,$this->routeMessageFlag];
	}

	private function onMessage($ws, $frame)
	{
		$messageFrame=json_decode($frame->data);
		if($messageFrame)
		{
			if($this->checkKeys($messageFrame))
			{
				$routeStr=$messageFrame->r;
				$message=$messageFrame->m;
				
			}
		}
		else
		{
			if(DEBUG)echo "message $frame->data is not json type.\n";
		}
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

	public function pipe()
	{
		$this->websocketServer->on('connect', function ($serv, $fd){
			if(DEBUG)echo "client $fd is connected.\n";
		});

		//监听WebSocket连接打开事件
		$this->websocketServer->on('open', function ($ws, $request) {
		    
		});

		//监听WebSocket消息事件
		$this->websocketServer->on('message', function ($ws, $frame) {
			$this->onMessage($ws, $frame);
		});

		//监听WebSocket连接关闭事件
		$this->websocketServer->on('close', function ($ws, $fd) {
			if(DEBUG)echo "client $fd is closed.\n";
		});
	}
}