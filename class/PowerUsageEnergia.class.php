<?php
/**
 * 中国電力
 * @author Lyiase
 *
 */
class PowerUsageEnergia extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "中国";
	protected $fullname = "中国電力";
	
	public function __construct($url="http://www.energia.co.jp/jukyuu/sys/juyo-j.csv"){
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