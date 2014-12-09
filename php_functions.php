<?php 
	/**
	*给目标字符串进行格式转换
	 * @param $mstr 源字符串
	*/
	function ui_ubb($mstr)
	{
		$mstr=getDelHtml($mstr);
		$mstr=str_replace("[b]","<b>",$mstr);
		$mstr=str_replace("[/b]","</b>",$mstr);
		$mstr=str_replace("[red]","<span style='color:#F00'>",$mstr);
		$mstr=str_replace("[/red]","</span>",$mstr);
		$mstr=str_replace("[blue]","<span style='color:blue'>",$mstr);
		$mstr=str_replace("[/blue]","</span>",$mstr);
		$mstr=preg_replace("/\[p[\s]*align\=(\w*)\]/i", "<p style='TEXT-ALIGN:\${1}'> ", $mstr);
		$mstr=str_replace("[/p]", "</p>", $mstr);
		$mstr=str_replace("[a","<a",$mstr);
		$mstr=str_replace("']","'>",$mstr);
		$mstr=str_replace("\"]","\">",$mstr);
		$mstr=str_replace("[/a]","</a>",$mstr);
		$mstr=str_replace("[img","<img",$mstr);
		$mstr=str_replace("\n","<br>",$mstr);
		$mstr=str_replace(" ","&nbsp;",$mstr);

		return $mstr;
	}

	/**
	 * 去除html格式的功能
	 * @param $str 源字符串
	 * @return 返回去掉html代码后的字符串
	 */
	function getDelHtml($str)
	{
		return preg_replace("/<[\s\S]*?>/","",$str);
	}

	//返回正则获取后的第一个值
	function getsubstr($str,$zc,$dev="")
	{
		preg_match($zc, $str."",$temp);
		return (isset($temp))?$temp[0]:$dev;
	}

	/**
	 * 正则中第一个匹配项中的第一个括号中的值
	 * @param 源字符串
	 * @param 正则表达式
	 * @return 返回正则中第一个匹配项中的第一个括号中的值
	 */
	function getkuohaostr($str,$zc)
	{
		preg_match($zc, $str."",$temp);
		return (isset($temp))?$temp[1]:"";
		
	}

	/**
	 * 替换正则中的第一个括号里的内容
	 * @param  $str 源字符串
	 * @param $replacem 替换后的内容
	 *  @param $zc 正则
	 */
	function replaceKuohaostr($str,$replacem,$zc)
	{
		preg_match($zc, $str."",$temp);
		$temp=(isset($temp))?$temp[1]:"";
		return str_replace($temp, $replacem, $str);
	}

	/**
	 * 自定义输出变量并停止执行,参数主要用于数组，也可以是字符串
	 * 主要作用就是为了方便查看输出的数组的值
	 * @param $str
	 */
	function mydie($str){
	  echo "<pre>";
	  print_r($str);
	  echo "</pre>";
	  die;
	}

	/**
	 * 自定义输出变量并停止执行,参数主要用于数组，也可以是字符串
	 * 主要作用就是为了方便查看输出的数组的值
	 * @param $str
	 */
	function myprint($str){
	  echo "<pre>";
	  print_r($str);
	  echo "</pre>";
	  
	}

	  /**
 * 返回某一个目录下的文件和文件夹列表，以一维数组返回
 * $dir 某一个目录 
 * $filetype 充许返回的文件类型，多个以|分隔 如.php|.txt
 * */
function getdirlist($dir,$filetype=""){
    
    $dir=str_replace("\\","/",$dir);
    $a=array();
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            $i=0;
            if($filetype!="") $filetype=str_replace(".","\.",$filetype);
            
            while (($file = readdir($dh))) {
                if(preg_match("/^\./",$file)) continue;
                
                //如果是文件夹则显示不执行后面的了，跳入下一步的循环
                if(is_dir($dir."/".$file)) {
                    $a[$i]=$dir."/".$file;  $i++;
                    continue;
                }
                
                
                if($filetype!=""){
                     if(preg_match("/(".$filetype.")$/",$file)){
                         $a[$i]=$dir."/".$file;  $i++;
                     }
                }else{
                    $a[$i]=$dir."/".$file; $i++;
                }
             }
            closedir($dh);
        }
    }
    return $a;
}



/**
 * 将一个数组转化成单选框的html内容
 */
function html_radio($arr,$elename,$dv="",$classname=""){
     $html="";
     foreach($arr as $k=>$v){
          $checked=($dv==$k)?"checked='checked'":"";
          $html.="<input type=\"radio\" name='".$elename."' class='".$classname."'  value='".$k."' ".$checked." >".$v."&nbsp;";
     }
     return $html;

}


/**
 * 生成单选框的html代码
 * $arr 要生成的数组
 * $elename radio节点值
 * $mdv 默认值
 * $classname css名
 * */
function html_checkbox($arr,$elename,$mdv="",$classname=""){
     $html="";
    foreach($arr as $k=>$v){
        $checked="";
        if(preg_match('|'.$k.'|',$mdv)) $checked="checked='checked'";
          $html.="<input class='".$classname."' type='checkbox' name='".$elename."[]' ".$checked." value='".$k."'>".$v."&nbsp;";
    }

    return $html;


}


/**
 * 根据数组生成select的代码
 * @param  [type] $arr         数据源
 * @param  [type] $elename     id名称
 * @param  string $mdv         默认值
 * @param  string $classname   类别名称
 * @param  string $kv          值的类型,默认为键名
 * @param  string $othershushi 其它属性
 * @return [type]              html
 */
function ui_select($arr,$elename,$mdv="",$classname="",$kv="v",$othershushi=""){
    $html="<select name='".$elename."' id='".$elename."' class='".$classname."' ".$othershushi.">";
    foreach($arr as $k=>$v){
        
        $selected="";
        if($mdv==$k) $selected="selected='selected'";
        if($kv=="k"){
            $html.="<option ".$selected." value='".$k."'>".$v."</option>";
        }else{
            $html.="<option ".$selected." value='".$v."'>".$v."</option>";
        }
        
    }
    $html.="</select>";
    return $html;
}



 ?>