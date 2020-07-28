<?php
abstract class PowerUsageAbstract implements PowerUsage {
	
	/**
	 * 電力会社名(電力は付かない)
	 * @var string
	 */
	protected $name;
	
	/**
	 * 電力会社名(電力が付く)
	 * @var string
	 */
	protected $fullname;
	
	/**
	 * 所属するエリア
	 * @var int
	 */
	protected $area;
	
	/**
	 * 解析対象データ
	 * @var string
	 */
	protected $data;
	
	/**
	 * HTTPステータスコード
	 * @var string
	 */
	protected $status;
	
	/**
	 * 取得対象時間
	 * @var int
	 */
	protected $currentTime;
	
	/**
	 * 最大需要時間
	 * @var int
	 */
	protected $maxTime;
	
	/**
	 * 最大需要更新時間
	 * @var int
	 */
	protected $maxModified;
	
	/**
	 * 最大需要更新時間
	 * @var int
	 */
	protected $capacityModified;
	
	public function __construct($url){
		$obj = new HTTP($url);
		$obj->request();
		$this->status = $obj->getStatusCode();
		$this->data = $obj->getContent();
	}
	
	/**
	 * 電力会社名(電力は付かない)
	 * @return string 電力会社名
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * 電力会社名(電力が付く)
	 * @return string 電力会社名
	 */
	public function getLongName(){
		return $this->fullname;
	}
	
	/**
	 * 所属するエリア
	 * @return int PowerUsage::AREA_XXの何れか
	 */
	public function getAreaType(){
		return $this->area;
	}
	
	/**
	 * 電気予報が実行されているか
	 * @return bool 実施中
	 */
	public function isEnabled(){
		if($this->status == "200" && $this->getMaxSupplyCapacity() != 0) return TRUE;
		return FALSE;
	}
	
	/**
	 * 輪番停電実施可否
	 * @return bool 可否
	 */
	public function isRollingBlackout(){
		return FALSE;
	}
	
	/**
	 * 現在の電力使用量
	 * @return int 電力量(万kW)
	 */
	public function getActualDemand(){
		// 対象ブロックを取得
		$list = $this->getActualDemandBlock();
		unset($list[0]);
		
		$current = 0;
		foreach($list as $val){
			$arr = explode(",",$val);
			// 現在の使用量が空だったらトラッキング終了
			if(empty($arr[2])) break;
			$current = $arr;
			
		}
		
		// 取得した時間を設定
		$this->currentTime = strtotime(sprintf("%s %s",$current[0],$current[1]));
		
		return (float)$current[2];
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(2)と等価な結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(2);
	}
	
	protected function getBlock($i){
		// ブロックを取得
		$data = str_replace("\r\n","\n",$this->data);
		$list = explode("\n\n",$data);
		return explode("\n",$list[$i]);
	}
	
	/**
	 * 今日の予想需要時間
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getActualDemandTime(){
		return $this->currentTime;
	}
	
	/**
	 * 今日のピーク供給力
	 * @return int 電力量(万kW)
	 */
	public function getMaxSupplyCapacity(){
		// 対象ブロックを取得
		$list = $this->getBlock(0);
		$arr = explode(",",$list[2]);
		
		// ピーク供給力の更新日時
		$this->capacityModified = strtotime(sprintf("%s %s", $arr[2], $arr[3]));
		
		return (float)$arr[0];
	}
	
	/**
	 * 今日の最大電力量更新日時
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxSupplyCapacityModified(){
		return $this->capacityModified;
	}
	
	/**
	 * 今日の予想需要
	 * @return int 電力量(万kW)
	 */
	public function getMaxDemandForecast(){
		// 対象ブロックを取得
		$list = $this->getBlock(1);
		$arr = explode(",",$list[1]);
		
		// 最大需要時間を設定
		list($first,$last) = explode(chr(0x81).chr(0x60), $arr[1]);
		$this->maxTime = strtotime(sprintf("%s %s", $arr[2], $first));
		
		// 最大需要の更新日時
		$this->maxModified = strtotime(sprintf("%s %s", $arr[2], $arr[3]));
		
		return (float)$arr[0];
	}
	
	/**
	 * 今日の予想需要時間
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxDemandForecastTime(){
		return $this->maxTime;
	}
	
	/**
	 * 今日の予想需要更新時間
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxDemandForecastModified(){
		return $this->maxModified;
	}
	
	/** ブロック構造をダンプする */
	public function dumpBlock(){
		// ブロックを取得
		$data = str_replace("\r\n","\n",$this->data);
		$list = explode("\n\n",$data);
		var_dump($list);
	}
}
/*
class TEST extends PowerUsageAbstract {}
$obj = new TEST("http://www.tepco.co.jp/forecast/html/images/juyo-j.csv");
var_dump($obj->getActualDemand(),$obj->getMaxDemandForecast(),$obj->getMaxSupplyCapacity());
*/
?>