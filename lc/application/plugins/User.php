<?php
class UserPlugin extends Yaf_Plugin_Abstract 
{
	//这个会在路由之前出发，也就是路由之前会调用这个Hook ，这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成 
	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "routerStartup<br>";
	}
	
	//这个在路由结束之后触发，需要注意的是，只有路由正确完成之后才会触发这个Hook
	public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "routerShutdown<br>";
	}
	
	//分发循环开始之前被触发 
	public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "dispatchLoopStartup<br>";
	}
	
	//分发之前触发，如果在一个请求处理过程中, 发生了forward, 则这个事件会被触发多次 
	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "preDispatch<br>";
	}
	
	//分发结束之后触发，此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次 
	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "postDispatch<br>";
	}
	
	//分发循环结束之后触发 此时表示所有的业务逻辑都已经运行完成, 但是响应还没有发送 
	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) 
	{
		echo "dispatchLoopShutdown<br>";
	}
	
	public function preResponse(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
	{   
		echo "preResponse<br/>\n";   
	}
	
}