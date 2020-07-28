<?php
/**
 * 東北電力
 * @author Lyiase
 *
 */
class PowerUsageTouhoku extends PowerUsageAbstract {
	
	protected $area = self::AREA_EAST;
	protected $name = "東北";
	protected $fullname = "東北電力";
	
	public function __construct($url="http://setsuden.tohoku-epco.co.jp/common/demand/juyo_tohoku.csv"){
		parent::__construct($url);
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(6)の結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(6);
	}
}
?>