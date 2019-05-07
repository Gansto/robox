<?
	if(!empty($class_id))
	{
		$sql = "select * from info_class where id=$class_id";
        $rst = $db->query($sql);
        if($row = $db->fetch_array($rst))
		{
			$info_state	=$row['info_state'];
			$has_sub	=$row['has_sub'];
			while($has_sub==1)
			{
				$sql = "select * from info_class where id like '".$class_id."___' order by sortnum asc limit 1";
       			$rst = $db->query($sql);
				if($row = $db->fetch_array($rst))
				{
					$class_id	=$row['id'];
					$info_state	=$row['info_state'];
					$has_sub	=$row['has_sub'];
				}
				else
				{
					$has_sub	=0;
				}
			}
		}
		$base_id		=	substr($class_id,0,3);
		$second_id		=   strlen($class_id)<6?"":substr($class_id,0,6);
		$third_id		=   strlen($class_id)<9?"":substr($class_id,0,9);
		
		$base_name		=   get_class_name($db,$base_id);
		$base_en_name	=	get_class_en_name($db,$base_id);
		$second_name	=	empty($second_id)?"":get_class_name($db,$second_id);
		$third_name		=	empty($third_id)?"":get_class_name($db,$third_id);
		
		$class_name		=	get_class_name($db,$class_id);
		$class_en_name	=	get_class_en_name($db,$class_id);
		$base_pic		= 	UPLOAD_PATH.get_class_pic($db,$base_id);
		$second_pic		= 	UPLOAD_PATH.get_class_pic($db,$class_id);
	}
	
?>