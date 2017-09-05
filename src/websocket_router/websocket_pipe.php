<?php
namespace sheld\websocket_router;

class Websocket_pipe
{
	private $websocketServer;
	

	public function __construct($wsServer)
	{
		$this->websocketServer=$wsServer;
		
	}

	private function onMessage($ws, $frame)
	{
		$messageFrame=json_decode($frame->data);
		if($messageFrame)
		{
			$router=new \sheld\websocket_router\Router($ws, $frame);
			$message=$router->route();
			$ws->push($frame->fd, $message);
			unset($router);
		}
		else
		{
			if(DEBUG)echo "message $frame->data is not json type.\n";
		}
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