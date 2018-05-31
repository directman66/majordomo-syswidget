<?php
/**
* author Sannikov Dmitriy sannikovdi@yandex.ru
* support page 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 09:04:00 [Apr 04, 2016])
*/
//
//
class syswidget extends module {
/**
*
* Module class constructor
*
* @access private
*/
function syswidget() {
  $this->name="syswidget";
  $this->title="Виджет Системный";
  $this->module_category="<#LANG_SECTION_SYSTEM#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
 function edit_classes(&$out, $id) {
  require(DIR_MODULES.$this->name.'/classes_edit.inc.php');
 }
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;

	
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }

	
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
// global $type;	
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
	
	
	

	
	
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
	*/
function admin(&$out) {
///	echo "admin";
//echo $this->view_mode;	
 $this->getConfig();

        if ((time() - gg('cycle_syswidgetRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}
	
 $out['EVERY']=$this->config['EVERY'];
 $out['EVERYHOUR']=$this->config['EVERYHOUR'];	
 
 if (!$out['UUID']) {
	 $out['UUID'] = md5(microtime() . rand(0, 9999));
	 $this->config['UUID'] = $out['UUID'];
	 $this->saveConfig();
 }
//	echo $this->view_mode;
 //$this->config['ENABLE_EVENTS']=123;	 	 
 if ($this->view_mode=='update_settings') 
 {
	global $duuid;
	$this->config['DUUID']=$duuid;	 
	global $deviceid;
	$this->config['DEVICEID']=$deviceid;	 
	 
	global $enable_events;
	$this->config['ENABLE_EVENTS']=$enable_events;	 
//	$this->config['ENABLE_EVENTS']=123;	 	 
   
   $this->saveConfig();
   $this->redirect("?");
 }
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 
	
// if ($this->tab=='' || $this->tab=='outdata') {
//   $this->outdata_search($out);
// }  
 if ($this->tab=='' || $this->tab=='config' || $this->tab=='widgets') {
$today = $this->today;		 
    $this->indata_search($out); 
 }
	
 	
	
 if ($this->view_mode=='get') {
setGlobal('cycle_syswidgetControl','start'); 
	$this->updatefnc();
	$this->diskfree();
	$this->getipadr();	 
	$this->hddtemp();	   	 

 }
	
	
 
	
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
 
 function indata_search(&$out) {	 



   $out['lastSayMessage']=gg('lastSayMessage');
   $out['minmgslevel']=gg('ThisComputer.minMsgLevel');
   $out['SysUptime']=gg('syswidget.SysUptime');
   $out['CPUusage']=gg('syswidget.CPUusage');
   $out['CPUload1']=gg('syswidget.CPUload1');
   $out['CPUload5']=gg('syswidget.CPUload5');
   $out['CPUload15']=gg('syswidget.CPUload15');
   $out['SysMem']=gg('syswidget.SysMem');
   $out['CPUtemp']=gg('syswidget.CPUtemp');
   $out['DiskFree']=gg('syswidget.DiskFree');
//   $out['DiskFreeMB']=round(gg('syswidget.DiskFreeMB')/1024/1024);
   $out['DiskFreeMB']=dataSize(gg('syswidget.DiskFreeMB'));
   $out['proccountn']=gg('syswidget.proccountn');
   $out['proccount']=gg('syswidget.proccount');
   $out['nsocketn']=gg('syswidget.nsocketn');
   $out['nsocket']=gg('syswidget.nsocket');
   $out['volumeLevel']=gg('ThisComputer.volumeLevel');

   $out['extip']=gg('syswidget.extip');
   $out['localip']=gg('syswidget.localip');	
   $out['provider']=gg('syswidget.provider');		
	 
   $out['hdd1tempc']=gg('syswidget.hdd1tempc'); 
   $out['hdd1temp']=gg('syswidget.hdd1temp');  
   $out['hdd2tempc']=gg('syswidget.hdd2tempc'); 
   $out['hdd2temp']=gg('syswidget.hdd2temp');  	 
	 
   
	 


	 
	 






 }



 function processCycle() {
   $this->getConfig();
   $every=$this->config['EVERY'];
   $tdev = time()-$this->config['LATEST_UPDATE'];
   $has = $tdev>$every*60;
   if ($tdev < 0) {$has = true;}
   
   if ($has) {  
	$this->updatefnc();
	$this->hddtemp();	   	   
	$this->config['LATEST_UPDATE']=time();
	$this->saveConfig();
   } 
	 
   $every=$this->config['EVERYHOUR'];
   $tdev = time()-$this->config['LATEST_UPDATEHOUR'];	 
	 
   $has = $tdev>$every*60*60;
   if ($tdev < 0) {$has = true;  }
   
   if ($has) { 
	$this->diskfree();
	$this->getipadr();


	
	$this->config['LATEST_UPDATEHOUR']=time();
	$this->saveConfig();
   } 
	 
	 
	 
  }
/**
* InData edit/add
*
* @access public
*/
 
/**
* InData delete record
*
* @access public
*/
/**
* InData delete record
*
* @access public
*/

/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
 function updatefnc() {



//AverageCPU

//CPU 1 5 15
$cpu_load = shell_exec('cat /proc/loadavg');
$pos1 = strpos($cpu_load, ' ');
$pos2 = strpos($cpu_load, ' ', $pos1+1);
$pos3 = strpos($cpu_load, ' ', $pos2+1);
$cpu_load1 = substr($cpu_load, 0, $pos1);
$cpu_load5 = substr($cpu_load, $pos1+1, $pos2-$pos1-1);
$cpu_load15 = substr($cpu_load, $pos2+1, $pos3-$pos2-1);
//if(gg('CPUload1') != $cpu_load1) {
 sg('syswidget.CPUload1', $cpu_load1);
//}
//if(gg('CPUload5') != $cpu_load5) {
 sg('syswidget.CPUload5', $cpu_load5);
//}
//if(gg('CPUload15') != $cpu_load15) {
 sg('syswidget.CPUload15', $cpu_load15);
//}



///number of proccess
	$proc_count = 0;
	$dh = opendir('/proc');
	
	while ($dir = readdir($dh)) {
		if (is_dir('/proc/' . $dir)) {
			if (preg_match('/^[0-9]+$/', $dir)) {
				$proc_count ++;
			}
		}
	}

//echo $proc_count;
if ($proc_count>gg('syswidget.proccount_max')){sg('syswidget.proccount_max', $proc_count);}
$pr=round($proc_count/gg('syswidget.proccount_max')*100);
if (gg('syswidget.proccount')<>$pr){
 sg('syswidget.proccount', $pr);
sg('syswidget.proccountn', $proc_count); 
}



///numer of socket

if (function_exists('exec')) {
		
		$www_total_count = 0;
		@exec ('netstat -an | egrep \':80|:443\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);
		
		foreach ($results as $result) {
			$array = explode(':', $result);
			$www_total_count ++;
			
			if (preg_match('/^::/', $result)) {
				$ipaddr = $array[3];
			} else {
				$ipaddr = $array[0];
			}
			
			if (!in_array($ipaddr, $unique)) {
				$unique[] = $ipaddr;
				$www_unique_count ++;
			}
		}
		
		unset ($results);
		
		//echo count($unique);
 $nsocket=count($unique);
		
	}
if ($nsocket>gg('syswidget.nsocket_max')){sg('syswidget.nsocket_max', $nsocket);}
$pr=round($nsocket/gg('syswidget.nsocket_max')*100);
if (gg('syswidget.nsocket')<>$pr){
 sg('syswidget.nsocket_max', $pr);}
if (gg('syswidget.nsocket')<>$pr){
 sg('syswidget.nsocketn', $nsocket); 
sg('syswidget.nsocket', $pr);  }

//sysinfo

//CPU temp
$cpu_temp = shell_exec('cat /sys/class/thermal/thermal_zone0/temp')/1000;
$cpu_temp = round($cpu_temp, 1);
//if(gg('CPUtemp') != $cpu_temp) {
 sg('syswidget.CPUtemp', $cpu_temp);
//}
//CPU usage
$cpu_usage = exec("top -bn 1 | awk '{print $9}' | tail -n +8 | awk '{s+=$1} END {print s}'");
$cpu_usage = round($cpu_usage/4, 1);
//if(gg('CPUusage') != $cpu_usage) {
 sg('syswidget.CPUusage', $cpu_usage);
//}
//Memory usage/total
$mem_total = exec("cat /proc/meminfo | grep MemTotal | awk '{print $2}'");
$mem_usage = $mem_total - exec("cat /proc/meminfo | grep MemFree | awk '{print $2}'");
$sys_memory = round($mem_usage*100/$mem_total, 1);
if(gg('syswidget.SysMem') != $sys_memory) {
 sg('syswidget.SysMem', $sys_memory);
}





//System uptime
$sys_uptime = shell_exec('uptime');
$sys_uptime = explode(' up ', $sys_uptime);
$sys_uptime = explode(',', $sys_uptime[1]);
$sys_uptime = $sys_uptime[0] . ', ' . $sys_uptime[1];
sg('syswidget.SysUptime', $sys_uptime);

}


function diskfree() {
	
//diskfree
$disktotal = disk_total_space ('/');
	$diskfree  = disk_free_space  ('/');
 sg('syswidget.DiskFreeMB', $diskfree);
	$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';
$df=substr($diskuse,0,-1);
//echo $df;
 sg('syswidget.DiskFree', $df);

	
	
}	
	
	
function hddtemp() {
$hddtemp=exec("sudo hddtemp /dev/sda1 ");      

$arr = explode(':', $hddtemp);
$hdd1= substr($arr[2],0,-1);

if ($hdd1>gg('hdd1temp_max')){sg('hdd1temp_max', $hdd1);}
//$pr=round($hdd1/gg('hdd1temp_max')*100);
$pr=round($hdd1/65*100);
if (gg('hdd1temp')<>$pr){
sg('syswidget.hdd1tempc', $hdd1); 
sg('syswidget.hdd1temp', $pr);  
}	

	////hdd2
	
$hddtemp=exec("sudo hddtemp /dev/sdb1 ");      

$arr = explode(':', $hddtemp);
$hdd2= substr($arr[2],0,-1);

if ($hdd2>gg('hdd2temp_max')){sg('hdd2temp_max', $hdd2);}
//$pr=round($hdd1/gg('hdd1temp_max')*100);
$pr=round($hdd2/65*100);
if (gg('hdd2temp')<>$pr){
sg('syswidget.hdd2tempc', $hdd2); 
sg('syswidget.hdd2temp', $pr);  
}		
	
	
	
}	
	
function getipadr() {
$res=exec('hostname -I');
$ipv6_regex='/(\w{4})/is';
$res = trim(preg_replace($ipv6_regex,'',$res));
$ipv6_regex='/:(\w+)/is';
$res = trim(preg_replace($ipv6_regex,'',$res));
$res = trim(str_replace(':','',$res));


//say ('Про интернет к которому я подключена.');
//$url="http://api.2ip.com.ua/provider.json";
  $url="http://api.2ip.ua/provider.json";

//Работаем со строкой JSON
$data = json_decode(file_get_contents($url), true);
    $ip=$data["ip"]; // что искали
    $name_ripe=$data["name_ripe"]; 
    $name_rus=$data["name_rus"]; 
    $site=$data["site"]; 
//echo $ip;
//say ("Сайт провайдера ".$site,5);

$url2="http://api.2ip.com.ua/geo.json?ip=".$ip;

//Работаем со строкой JSON
$data = json_decode(file_get_contents($url2), true);
    $country_rus=$data["country_rus"]; // что искали
    $region_rus=$data["region_rus"]; 
    $city_rus=$data["city_rus"]; 


sg('syswidget.extip', $ip);
sg('syswidget.localip', $res);	
sg('syswidget.provider', $name_rus);		
}

////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////	

	
	

	
	
	
 
 
///////////////////////////////////
  
  
  
 
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
      SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id = (select id from classes where title = 'syswidget')))");
      SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'syswidget'))");
      SQLExec("delete from objects where class_id = (select id from classes where title = 'syswidget')");
      SQLExec("delete from classes where title = 'syswidget'");	 
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data) {
setGlobal('cycle_syswidgetAutoRestart','1');	 	 
$classname='syswidget';
addClass($classname); 



	 $prop_id=addClassProperty($classname, 'CPUtemp', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='CPU temperature'; //   <-----------
SQLUpdate('properties',$property); }


	 $prop_id=addClassProperty($classname, 'CPUusage', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='CPU Usage'; //   <-----------
SQLUpdate('properties',$property); }


	 
addClassObject('syswidget','syswidget');	 	 
	 
	 
	 

  parent::dbInstall($data);
		
		
 }}
// --------------------------------------------------------------------
//////
/*
*
* TW9kdWxlIGNyZWF0ZWQgQXByIDA0LCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/



function getSymbolByQuantity($bytes) {
    $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
    $exp = floor(log($bytes)/log(1024));
     return sprintf('%.2f '.$symbol[$exp], ($bytes/pow(1024, floor($exp))));
}

function dataSize($Bytes)
{
$Type=array("", "k", "m", "g", "t");
$counter=0;
while($Bytes>=1024)
{
$Bytes/=1024;
$counter++;
}
return("".round($Bytes)." ".$Type[$counter]."b");
}
