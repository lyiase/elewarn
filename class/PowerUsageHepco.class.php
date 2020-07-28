<?php
/**
 * 北海道電力
 * @author Lyiase
 *
 */
class PowerUsageHepco extends PowerUsageAbstract {
	
	protected $area = self::AREA_EAST;
	protected $name = "北海道";
	protected $fullname = "北海道電力";
	
	public function __construct($url="http://denkiyoho.hepco.co.jp/data/juyo_hokkaidou.csv"){
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