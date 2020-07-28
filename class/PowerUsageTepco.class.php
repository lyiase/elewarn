<?php
/**
 * 東京電力
 * @author Lyiase
 *
 */
class PowerUsageTepco extends PowerUsageAbstract {
	
	protected $area = self::AREA_EAST;
	protected $name = "東京";
	protected $fullname = "東京電力";
	
	public function __construct($url="http://www.tepco.co.jp/forecast/html/images/juyo-j.csv"){
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