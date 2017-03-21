<?php
 
class Base 
{
 
	//���ģʽ֮����ģʽ,$_instance��������Ϊ��̬��˽�б���
	
	//������ʵ���ڴ�������
	private static $_instance;
	 
	//��������
	public static function getInstance() 
	{
	 
		$class_name = get_called_class();
		if (!isset(self::$_instance[$class_name])) 
		{
			self::$_instance[$class_name] = new $class_name;
		}
		 
		return self::$_instance[$class_name];
	}
	 
	//��ֹ�û����ƶ���ʵ��
	public function __clone() 
	{
		trigger_error('Clone is not allow', E_USER_ERROR);
	}
 
}