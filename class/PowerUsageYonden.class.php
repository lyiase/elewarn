<?php
/**
 * 四国電力
 * @author Lyiase
 *
 */
class PowerUsageYonden extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "四国";
	protected $fullname = "四国電力";
	
	public function __construct($url="http://www.yonden.co.jp/denkiyoho/juyo_yonden.csv"){
		parent::__construct($url);
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(6)の結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(5);
	}
}
?>