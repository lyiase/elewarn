<?php
/**
 * 北陸電力
 * @author Lyiase
 *
 */
class PowerUsageRepco extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "北陸";
	protected $fullname = "北陸電力";
	
	public function __construct($url="http://www.setsuden-rikuden.jp/csv/juyo-rikuden.csv"){
		parent::__construct($url);
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(6)の結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(6);
	}
	
	/**
	 * 現在の電力使用量(注：北陸電力は数字で現在の値を取れないので現在の所予想需要と同等にしておく)
	 * @return int 電力量(万kW)
	 * TODO 画像解析で実装予定
	 *//*
	public function getActualDemand(){
		preg_match('#id="num">([0-9]+)<#', $this->data, $matches);
		//$current = empty($matches[1]) ? 0 : $this->getMaxDemandForecast();
		$current = $this->getMaxSupplyCapacity() - 41;
		return $current;
	}*/
	
	/**
	 * 今日のピーク供給力
	 * @return int 電力量(万kW)
	 *//*
	public function getMaxSupplyCapacity(){
		preg_match('#id="peakNum">([0-9]+)<#', $this->data, $matches);
		return (int)$matches[1];
	}*/
	
	/**
	 * 今日の予想需要
	 * @return int 電力量(万kW)
	 *//*
	public function getMaxDemandForecast(){
		preg_match('#id="exNum">([0-9]+)<#', $this->data, $matches);
		return (int)$matches[1];
	}*/
}
?>