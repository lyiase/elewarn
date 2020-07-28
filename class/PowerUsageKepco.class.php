<?php
/**
 * 関西電力
 * @author Lyiase
 *
 */
class PowerUsageKepco extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "関西";
	protected $fullname = "関西電力";
	
	public function __construct($url="http://www.kepco.co.jp/yamasou/juyo1_kansai.csv"){
		parent::__construct($url);
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(8)の結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(8);
	}
}
?>