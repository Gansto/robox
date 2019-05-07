<?
/*
 *	分页函数
 *	完成时间：2004-08-14
 *	Cole modify:01/09/2009
*/

function Page($page, $pageCount, $baseURL = "", $mask = "<%PAGE%>")
{

	if (!$baseURL)
	{
		global $_POST, $_GET;

		$baseURL = $_SERVER["PHP_SELF"] . "?";

		if (is_array($_GET))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				$baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		if (is_array($_POST))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				if(!is_array($v)) $baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		$baseURL .= 'page=' . $mask;
	}
	else
	{
		$baseURL .= 'page=' . $mask;
	}

	$pageCount = $pageCount ? $pageCount : 1;

	if ($page > $pageCount) $page = $pageCount;
	if ($page < 1) $page = 1;


 	$pages  = "<font>( <font color='red'>" . $page . "</font> / " . $pageCount . " )&nbsp;&nbsp;";

	$pages .= "<a title='First Page' style='text-decoration:none;color:black;' " . ($page > 1 ? "href='" . str_replace($mask, 1, $baseURL) . "'" : "") . "><b>首页</b></a>&nbsp;&nbsp;";
	$pages .= "<a title='Provious Page' style='text-decoration:none;color:black;' " . ($page > 1 ? "href='" . str_replace($mask, $page - 1, $baseURL) . "'" : "") . "><b>上一页</b></a>&nbsp;&nbsp;";
	$pages .= "<a title='Next Page' style='text-decoration:none;color:black;' " . ($page < $pageCount ? "href='" . str_replace($mask, $page + 1, $baseURL) . "'" : "") . "><b>下一页</b></a>&nbsp;&nbsp;";


	 $pages .= "<a title='Last Page' style='text-decoration:none;color:black;' " . ($page < $pageCount ? "href='" . str_replace($mask, $pageCount, $baseURL) . "'" : "") . "><b>末页</b></a>&nbsp;&nbsp;</font>";

	$pages .= "<select name='page' onchange=\"window.location=this.options[this.selectedIndex].value\">\n";

	for ($i = 1; $i <= $pageCount; $i++)
	{
		$URL = str_replace($mask, $i, $baseURL);
		if ($page == $i)
		{
			$pages .= "<option value='$i' selected>$i</option>\n";
		}
		else
		{
			$pages .= "<option value=\"$URL\">$i</option>\n";
		}
	}

	$pages .= "</select>";

	return $pages;
}

/* 新分页函数 */
function genURL($page)
{
    $query = $_GET;
    $query['page'] = $page;
    $query = http_build_query($query);
    return $_SERVER['PHP_SELF'].'?'.$query;
}
function genPaginationBar($currentPage, $totalPages)
{
    $query = $_GET;
    $query['page'] =
    $bar = '<ul class="pagination pull-right">';
    $startPage = max(1, $currentPage-2);
    $endPage = min($currentPage+2, $totalPages);
    $previousAvailable = '';
    $nextAvailable = '';
    if ($currentPage == 1) {
        $previousAvailable = ' disabled';
    }
    if ($currentPage == $totalPages) {
        $nextAvailable = ' disabled';
    }
	$bar .= '<li class="prev'.$previousAvailable.'"><a href="'.genURL(1).'" title="First"><i class="fa fa-angle-double-left"></i></a></li>';
	$bar .= '<li class="prev'.$previousAvailable.'"><a href="'.genURL($currentPage-1).'" title="Prev"><i class="fa fa-angle-left"></i></a></li>';
    for ($i = 1; $i < $currentPage && $i < min(3, $startPage); $i++) {
        $bar .= "<li><a href='".genURL($i)."'>$i</a></li>";
    }
    if ($startPage > 3) {
        $bar .= "<li class='disabled'><a href='#'>...</a></li>";
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
              $bar .= "<li class='active'><a href='".genURL($i)."'>$i</a></li>";
      } else {
            $bar .= "<li><a href='".genURL($i)."'>$i</a></li>";
        }
    }

    if ($endPage < $totalPages-2) {
        $bar .= "<li class='disabled'><a href='#'>...</a></li>";
    }
    for ($i = max($endPage, $totalPages-2) + 1; $i <= $totalPages; $i++) {
        $bar .= "<li><a href='".genURL($i)."'>$i</a></li>";
    }
    $bar .= '<li class="next'.$nextAvailable.'"><a href="'.genURL($currentPage+1).'" title="Next"><i class="fa fa-angle-right"></i></a></li>';
	$bar .= '<li class="next'.$nextAvailable.'"><a href="'.genURL($totalPages).'" title="Last"><i class="fa fa-angle-double-right"></i></a></li>';
    $bar .= '</ul>';
    return $bar;
}

function Page3($page, $pageCount, $baseURL = "", $mask = "<%PAGE%>")
{

	if (!$baseURL)
	{
		global $_POST, $_GET;

		$baseURL = $_SERVER["PHP_SELF"] . "?";

		if (is_array($_GET))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				$baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		if (is_array($_POST))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				if(!is_array($v)) $baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		$baseURL .= 'page=' . $mask;
	}
	else
	{
		$baseURL .= 'page=' . $mask;
	}

	$pageCount = $pageCount ? $pageCount : 1;

	if ($page > $pageCount) $page = $pageCount;
	if ($page < 1) $page = 1;


/*	$pages  = "<font style='font-family:Arial;'>( <font color='red'>" . $page . "</font> / " . $pageCount . " )&nbsp;&nbsp;";

	$pages .= "<a title='First Page' style='text-decoration:none;color:black;font-family:Arial;' " . ($page > 1 ? "href='" . str_replace($mask, 1, $baseURL) . "'" : "") . "><b>首页</b></a>&nbsp;&nbsp;";*/
	$pages .= "<a title='Provious Page'" . ($page > 1 ? "href='" . str_replace($mask, $page - 1, $baseURL) . "'" : "") . ">Previous</a>";


	for ($i = $page - 5; $i <= $page + 5; $i++)
	{
		if ($i >= 1 && $i <= $pageCount)
		{
			$pages .= "<a href='" . str_replace($mask, $i, $baseURL) . "'" . (($i == $page) ? " class='current'" : "") . ">" . $i . "</a>";
		}
	}



	$pages .= "<a title='Next Page'" . ($page < $pageCount ? "href='" . str_replace($mask, $page + 1, $baseURL) . "'" : "") . ">Next</a>";


	/*$pages .= "<a title='Last Page' style='text-decoration:none;color:black;font-family:Arial;' " . ($page < $pageCount ? "href='" . str_replace($mask, $pageCount, $baseURL) . "'" : "") . "><b>末页</b></a>&nbsp;&nbsp;</font>";

	$pages .= "<select name='page' onchange=\"window.location=this.options[this.selectedIndex].value\">\n";

	for ($i = 1; $i <= $pageCount; $i++)
	{
		$URL = str_replace($mask, $i, $baseURL);
		if ($page == $i)
		{
			$pages .= "<option value='$i' selected>$i</option>\n";
		}
		else
		{
			$pages .= "<option value=\"$URL\">$i</option>\n";
		}
	}

	$pages .= "</select>";*/

	return $pages;
}


function page2($page, $pageCount, $pageSize, $baseURL = "", $mask = "<%PAGE%>")
{
	if (!$baseURL)
	{
		global $_POST, $_GET;

		$baseURL = $_SERVER["PHP_SELF"] . "?";

		if (is_array($_GET))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				$baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		$baseURL .= "page=" . $mask;
	}
	else
	{
		$baseURL .= "page=" . $mask;
	}

	$pageCount = $pageCount ? $pageCount : 1;
	if ($page > $pageCount) $page = $pageCount;
	if ($page < 1) $page = 1;


	$pages .= "<a href='" . ($page > 1 ? str_replace($mask, 1, $baseURL) : "javascript:void(0);") . "''>首页</a>&nbsp;";
	$pages .= "<a href='" . ($page > 1 ? str_replace($mask, $page - 1, $baseURL) : "javascript:void(0);") . "''>上一页</a>&nbsp;";

	for ($i = $page - 5; $i <= $page + 5; $i++)
	{
		if ($i >= 1 && $i <= $pageCount)
		{
			$pages .= "<a href='" . str_replace($mask, $i, $baseURL) . "'" . (($i == $page) ? " class='current'" : "") . ">" . $i . "</a>&nbsp;";
		}
	}

	$pages .= "<a href='" . ($page < $pageCount ? str_replace($mask, $page + 1, $baseURL) : "javascript:void(0);") . "''>下一页</a>&nbsp;";
	$pages .= "<a href='" . ($page < $pageCount ? str_replace($mask, $pageCount, $baseURL) : "javascript:void(0);") . "''  >尾页</a>";

	return $pages;
}
?>