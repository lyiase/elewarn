<?php
define("WARN_PARCENT",5.0);
define("WARN_CAPACITY",40.0);

require_once("class/AreaCapacity.class.php");
require_once("class/Http.class.php");
require_once("class/PowerUsage.interface.php");
require_once("class/PowerUsageAbstract.class.php");
require_once("class/PowerUsageHepco.class.php");	// 北海道電力
require_once("class/PowerUsageTouhoku.class.php");	// 東北電力
require_once("class/PowerUsageTepco.class.php");	// 東京電力
require_once("class/PowerUsageCepco.class.php");	// 中部電力
require_once("class/PowerUsageRepco.class.php");	// 北陸電力
require_once("class/PowerUsageKepco.class.php");	// 関西電力
require_once("class/PowerUsageYonden.class.php");	// 四国電力
require_once("class/PowerUsageEnergia.class.php");	// 中国電力
require_once("class/PowerUsageKyuden.class.php");	// 九州電力

$obj = new PowerUsageRepco();

// 提供会社を産出
$list = array(
	new PowerUsageHepco(),
	new PowerUsageTouhoku(),
	new PowerUsageTepco(),
	new PowerUsageCepco(),
	new PowerUsageRepco(),
	new PowerUsageKepco(),
	new PowerUsageYonden(),
	new PowerUsageEnergia(),
	new PowerUsageKyuden(),
);

function filter($val){
	if(is_object($val)){
		return $val->isEnabled();
	}
	return FALSE;
}

// 有効なものだけリストアップ
$activeList = array_filter($list,'filter');

// 使用率順番通りになるようソート
$rankList = array();
foreach($activeList as $val){
	$rate = (string)($val->getMaxDemandForecast() / $val->getMaxSupplyCapacity());
	$rankList[$rate] = $val;
}
krsort($rankList);
$rankList = array_values($rankList);	// 添え字をリセット


// 逼迫度順の文言を作成
$parcents = array();
foreach($rankList as $key => $val){
	$rate = $val->getMaxDemandForecast() / $val->getMaxSupplyCapacity();
	$parcents[] = sprintf("%d.%s(%.2f%%)", $key+1, $val->getName(), $rate * 100.0);
}

// 東日本と西日本の電力量の差を求める
$eastArea = new AreaCapacity();
$westArea = new AreaCapacity();
foreach($activeList as $val){
	switch($val->getAreaType()){
		case PowerUsage::AREA_EAST:
			$eastArea->capacity += $val->getMaxSupplyCapacity();
			$eastArea->forecast += $val->getMaxDemandForecast();
			$eastArea->current  += $val->getActualDemand();
			break;
		case PowerUsage::AREA_WEST:
			$westArea->capacity += $val->getMaxSupplyCapacity();
			$westArea->forecast += $val->getMaxDemandForecast();
			$westArea->current  += $val->getActualDemand();
			break;
	}
}

// 危機に陥ってる会社を検出
function warn_filter(PowerUsage $val){
	$parcent = 100.0 - ($val->getActualDemand() / $val->getMaxSupplyCapacity() * 100.0);
	$diff = $val->getMaxSupplyCapacity() - $val->getActualDemand();
	return (($parcent < WARN_PARCENT) || ($diff <= WARN_CAPACITY)) ? TRUE : FALSE;
}
function warn_msg(PowerUsage $val){
	$blackout = $val->isRollingBlackout() ? "(輪番停電実施中)" : "";
	$c_parcent = $val->getActualDemand() / $val->getMaxSupplyCapacity() * 100.0;
	$f_parcent = $val->getMaxDemandForecast() / $val->getMaxSupplyCapacity() * 100.0;
	return sprintf("※警報※【電力需要逼迫:%s】%s%s 供給力:%d万kW 予想最大需要:%d万kW(%.2f%%:%s台) 現在需要:%d万kW(%.2f%%) 現在予備率:%d万kW(%.2f%%) #elewarn",
				date("G時i分",$val->getActualDemandTime()), $val->getLongName(), $blackout, $val->getMaxSupplyCapacity(), $val->getMaxDemandForecast(), $f_parcent, date("G時",$val->getMaxDemandForecastTime()), $val->getActualDemand(), $c_parcent, $val->getMaxSupplyCapacity()-$val->getActualDemand(), 100.0-$c_parcent );
}
$warnList = array_filter($activeList, 'warn_filter');
$warnMsgs = array_map('warn_msg',$warnList);
$debugMsgs = array_map('warn_msg',$activeList);

// 予想需給状況
function forcast(PowerUsage $val){
	return sprintf("%s(%d/%d)",$val->getName(),$val->getMaxDemandForecast(),$val->getMaxSupplyCapacity());
}
// 電気予報公開電力会社
function pub(PowerUsage $val){
	return $val->getLongName();
}

$parsent  = sprintf("【%s予想逼迫】%s #elewarn",date('m月d日'),implode(",",$parcents));
$forecast = sprintf("【%s予想需給】%s #elewarn",date('m月d日'),implode(",",array_map('forcast',$activeList)));
$public   = sprintf("【%s電気予報公開電力会社】%s #elewarn",date('m月d日'),implode(" ",array_map('pub',$activeList)));
$area     = sprintf("[%s]【東日本全体需給】供給力:%d万kW 予想:%d万kW(%.1f%%) 現在:%d万kW(%.1f%%) 【西日本全体需給】供給力:%d万kW 予想:%d万kW(%.1f%%) 現在:%d万kW(%.1f%%) #elewarn", date('m月d日H時i分現在'),
		$eastArea->capacity, $eastArea->forecast, $eastArea->forecast / $eastArea->capacity * 100, $eastArea->current, $eastArea->current / $eastArea->capacity * 100,
		$westArea->capacity, $westArea->forecast, $westArea->forecast / $westArea->capacity * 100, $westArea->current, $westArea->current / $westArea->capacity * 100
);

// 140文字超えたらハッシュタグを変換
if(mb_strlen($forecast) > 140){
	$forecast = str_replace(" #elewarn","#elewarn",$forecast);
}
if(mb_strlen($parsent) > 140){
	$parsent = str_replace(" #elewarn","#elewarn",$parsent);
}

if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="view"){
	header("Content-Type: text/plain; charset=UTF-8");
	echo "\n";
	echo "■電気予報公開電力会社\n";
	var_dump($public);
	echo "\n";
	echo "■予想需給状況\n";
	var_dump($forecast);
	echo "\n";
	echo "■予想逼迫状況\n";
	var_dump($parsent);
	echo "\n";
	echo "■周波数エリア別需給状況\n";
	var_dump($area);
	
	echo "\n";
	echo "■発令中警報\n";
	var_dump($warnMsgs);
	
	echo "\n";
	echo "■現在の状況\n";
	var_dump($debugMsgs);
}

?>