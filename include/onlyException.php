<?
/*
 *	数据库错误堆楗
 *	Exception raise catch finally classname define
 *	author: lj
 *	lastmodify: 2004-07-29
*/

define ("CONNECT_FAILD", "Faild to connect database server");


//保存异常的堆栈
$ExceptionStack = Array();  //结构：('异常名' => 异常实例)


function raise(&$e, $file = __FILE__, $line = __Line__)
{
	//$e为一个异常的实例，将$e保存到$ExceptionStack异常列表中

	global $ExceptionStack;

	$e_class_name = get_class($e);

	unset($Exception);

	$Exception["e"]      = &$e;
	$Exception["file"]   = $file;
	$Exception["line"]   = $line;

	$ExceptionStack[] = &$Exception;
}

function onlyCatch($E_name)
{
	//$E_name是异常的名称(即异常类名称)
	//捕获异常，失败返回false

	global $ExceptionStack;

	$e = array_pop($ExceptionStack);  //array_pop(array) 弹出并返回数组的最后一个元素

	//is_a(object, class_name) 如果对象属于该类，或该类是此对象的父类，返回true
	//is_subclass_of(object, class_name) 如果对象是该类的子类，返回true
	if ( is_a($e["e"], $E_name) || is_subclass_of($e["e"], $E_name) )
	{
		return $e["e"];
	}
	else
	{
		if ($e) array_push($ExceptionStack, $e);  //array_push(array, var[,var...])将变量压入数组array的末尾
		$e = null;
		return false;
	}
}

// function finally()
function finall()
{
	//发生异常而未捕获时进行处理，例如可以写入异常文件

	global $ExceptionStack;

	if (count($ExceptionStack) < 1) return;

	$str = "<pre>found not catch exception: <br><br>\n";

	foreach($ExceptionStack as $Exception)
	{
		$str .= "\t" . $Exception["e"]->getErrorMsg() . "<br>\n";
	}

	$str .= "</pre>";

	return $str;
}

class OnlyException
{
	var $errMsg;
	var $errNo;

	function OnlyException($errMsg, $errNo = 0)
	{
		$this->errMsg = $errMsg;
		$this->errNo  = $errNo;
	}

	function getErrorNo()
	{
		return $this->errNo;
	}

	function getErrorMsg()
	{
		return $this->errMsg;
	}
}

class SQLException extends OnlyException
{
	var $errSQL;

	function SQLException($errMsg, $errNo = 0, $errSQL)
	{
		parent::OnlyException($errMsg, $errNo);
		$this->errSQL = $errSQL;
	}

	function getErrorSQL()
	{
		return $this->errSQL;
	}
}
?>
