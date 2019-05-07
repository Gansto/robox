<?
function make_news_list($hot,$info,$page_id,$page_size,$class_id,$page_num,$url="display.php")
{
	$str="<div class='topnews'><div class='bds'><ul class='clearfix'>";
	foreach($hot as $key=>$val)
	{
		$url2=$url."?id=".$val['id'];	
		$str.="<li>
			<a href='$url2'><img src='".UPLOAD_PATH.$val['pic']."' width='177' height='116'></a>
			<div class='mask' ><p style='text-align:center'>".utf8substr($val['title'],20)."</p></div>
		</li>";
	}
	$str.="</ul></div></div>";
	
	$str.="<div class='newslist'>";
	foreach($info as $key=>$val)
	{
		$time=strtotime($val['create_time']);
		if(empty($val['website']))
		{
			$url2=$url."?id=".$val['id'];	
			$target="";
		}
		else
		{
			$url2=$val['website'];	
			$target="target='_blank'";
		}
		
		 $str.="<dl>
				 <dt><span>[".date("Y-m-d",$time)."]</span><a href='$url2'>".utf8substr($val['title'],16)."</a></dt>
					<dd>".utf8substr(strip_tags($val['content']),200)."…</dd>
				</dl>";
	}
	$str.="</div>";
	$str.=page_str($page_id,$page_num,$page_size,$class_id);
	echo $str;
}

function make_pic_list($info,$page_id,$page_size,$class_id,$page_num,$url="display.php")
{  
	$str="<div class='pic-item-list w207 clearfix'>";
	foreach($info as $key=>$val)
	{
		$url2=$url."?id=".$val['id'];
		$str.="<div class='pic-item'>";
		$str.="<div class='list-pic'><a href='".$url2."'><img src='".UPLOAD_PATH.$val['pic']."' width='207' height='133' /></a></div>";
		$str.="<dl><dt><a href='$url2'>·".utf8substr($val['title'],15)."·</a></dt></dl></div>";
		if($key%3==2)
		{
			$str.="<div class='clear'></div>";
		}
	}
	$str.="</div>";
	$str.=page_str($page_id,$page_num,$page_size,$class_id);
	echo $str;
}
function make_hr($info,$page_id,$page_num,$class_id)
{	
	$str="<div class='hr'>";
	foreach($info as $val)
	{
		$str.=" <dl class='hr-list'><dt class='title'><span>".$val['publishdate']."</span>".$val['name']."</dt>";
		$str.=" <dd class='info'>".$val['content'];
		$str.=" </dd>";
		$str.=" <dd class='apply'><a href='hr_display.php?id=".$val['id']."'>我要应聘</a></dd>";
		$str.=" </dl>";
	}
	$str.="</div>";
	$str.=page_str($page_id,$page_num,$page_size,$class_id);
	echo $str;
}
function make_message($info,$page_id,$page_num,$class_id)
{	
	$str="";
	foreach($info as $val)
	{
		$str.=" <dl class='message-list'><dt class='m-title'><span>".$val['modify_time']."</span>留言者:".$val['name']."</dt>";
		$str.=" <dd class='m-info'>".$val['content']."</dd>";
		$str.=" <dt class='r-title'>管理员回复：</dt>";
		$str.=" <dd class='r-info'>".$val['reply']."</dd></dl>";
	}
	$pre_id=max(1,$page_id-1);
	$page_num=max(1,$page_num);
	$next_id=min($page_num,$page_id+1);
	$str.= page_str2($page_id,$pre_id,$next_id,$page_num,$class_id);
	echo $str;
}
function make_content($row)
{
	$str="<div class='article'><div class='bd'>".$row['content']."</div></div>";
	echo $str;
}
function page_str($page_id,$page_num,$page_size,$class_id)
{
	$php_self 	= $_SERVER["PHP_SELF"];
	$php_file 	= basename($php_self);
	$pre_id=max(1,$page_id-1);
	$next_id=min($page_num,$page_id+1);
	$page_num=max(1,$page_num);
	$str="<div class='pages clearfix'><a href='$php_file?class_id=$class_id&page_id=1'>首页</a>";
	$str.="<a href='$php_file?class_id=$class_id&page_id=$pre_id'>上一页</a>";
	for($i=1;$i<=$page_num;$i++)
	{
		if($i==$page_id)
		{
			$str.="<a href='$php_file?class_id=$class_id&page_id=$i' class='current'>$i</a>";
		}
		else
		{
			$str.="<a href='$php_file?class_id=$class_id&page_id=$i'>$i</a>";
		}
	}
	$str.="<a class='next' href='$php_file?class_id=$class_id&page_id=$next_id'>下一页</a>";
	$str.="<a class='last' href='$php_file?class_id=$class_id&page_id=$page_num'>尾页</a></div>";
	return $str;
}


	
function page_str2($page_id,$pre_id,$next_id,$page_num,$class_id)
{
	$php_self 	= $_SERVER["PHP_SELF"];
	$php_file 	= basename($php_self);
	$str="<div class='page clearfix'>";
	if($page_num>5)
	{
		$dl=$page_id-1;
		$dr=$page_id+1;
		
		if($dl>=3&&$dr<=($page_num-1))
		{
			$str.="<a href='$php_file?class_id=$class_id&page_id=1'>1</a>...";
			for($i=0;$i<3;$i++)
			{
				$j=$page_id-1+$i;
				if($j==$page_id)
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$j' class='current'>$j</a>";
				}
				else
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$j'>$j</a>";
				}
				
			}
			if($dr<$page_num-1)
			{
				$str.="...";
			}
			$str.="<a href='$php_file?class_id=$class_id&page_id=$page_num'>$page_num</a>";
		}
		else if($dl<3)
		{
			for($i=1;$i<=4;$i++)
			{
				if($i==$page_id)
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$i' class='current'>$i</a>";
				}
				else
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$i'>$i</a>";
				}
			}
			$last_id=$page_num-1;
			$str.="...<a href='$php_file?class_id=$class_id&page_id=$page_num'>$page_num</a>";
			
		}
		else if($dr>($page_num-1))
		{
			$str.="<a href='$php_file?class_id=$class_id&page_id=1'>1</a>...";
			for($i=$page_num-3;$i<=$page_num;$i++)
			{
				if($i==$page_id)
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$i' class='current'>$i</a>";
				}
				else
				{
					$str.="<a href='$php_file?class_id=$class_id&page_id=$i'>$i</a>";
				}
			}
		}
		
	}
	else
	{
		for($i=1;$i<=$page_num;$i++)
		{
			if($i==$page_id)
			{
				$str.="<a href='$php_file?class_id=$class_id&page_id=$i' class='current'>$i</a>";
			}
			else
			{
				$str.="<a href='$php_file?class_id=$class_id&page_id=$i'>$i</a>";
			}
	}
	}
	

	$str.="<input type='hidden' value='$php_file?class_id=$class_id&page_id=' id='page_hidden'/>";
	$str.="<input type='hidden' value='$page_num' id='page_max_hidden'/>";
	$str.="<input type='text' id='page_num' size='3' onkeyup=\"this.value=this.value.replace(/\D/g,'')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" />			<input type='button' value='Go' id='page_button'/>";
	$str.="</div>";
	return $str;
}
function page_str3($page_id,$pre_id,$next_id,$page_num,$url)
{
	$str="<div class='page clearfix'>";
	if($page_num>5)
	{
		$dl=$page_id-1;
		$dr=$page_id+1;
		
		if($dl>=3&&$dr<=($page_num-1))
		{
			$str.="<a href='".$url."1'>1</a>...";
			for($i=0;$i<3;$i++)
			{
				$j=$page_id-1+$i;
				if($j==$page_id)
				{
					$str.="<a href='$url$j' class='current'>$j</a>";
				}
				else
				{
					$str.="<a href='$url$j'>$j</a>";
				}
				
			}
			if($dr<$page_num-1)
			{
				$str.="...";
			}
			$str.="<a href='$url$page_num'>$page_num</a>";
		}
		else if($dl<3)
		{
			for($i=1;$i<=4;$i++)
			{
				if($i==$page_id)
				{
					$str.="<a href='$url$i' class='current'>$i</a>";
				}
				else
				{
					$str.="<a href='$url$i'>$i</a>";
				}
			}
			$last_id=$page_num-1;
			$str.="...<a href='$url$page_num'>$page_num</a>";
			
		}
		else if($dr>($page_num-1))
		{
			$str.="<a href='".$url."1'>1</a>...";
			for($i=$page_num-3;$i<=$page_num;$i++)
			{
				if($i==$page_id)
				{
					$str.="<a href='$url$i' class='current'>$i</a>";
				}
				else
				{
					$str.="<a href='$url$i'>$i</a>";
				}
			}
		}
		
	}
	else
	{
		for($i=1;$i<=$page_num;$i++)
		{
			if($i==$page_id)
			{
				$str.="<a href='$url$i' class='current'>$i</a>";
			}
			else
			{
				$str.="<a href='$url$i'>$i</a>";
			}
	}
	}
	

	$str.="<input type='hidden' value='$url' id='page_hidden'/>";
	$str.="<input type='hidden' value='$page_num' id='page_max_hidden'/>";
	$str.="<input type='text' id='page_num' size='3' onkeyup=\"this.value=this.value.replace(/\D/g,'')\"  onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" />			<input type='button' value='Go' id='page_button'/>";
	$str.="</div>";
	return $str;
}

?>