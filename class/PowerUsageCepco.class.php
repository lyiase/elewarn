<?php
/**
 * 中部電力
 * @author Lyiase
 *
 */
class PowerUsageCepco extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "中部";
	protected $fullname = "中部電力";
	
	public function __construct($url="http://denki-yoho.chuden.jp/denki_yoho_content_data/juyo_cepco003.csv"){
		parent::__construct($url);
		
		$preg = mb_convert_encoding("/備考１(.*\\r\\n){4}/m", "SJIS-win", "UTF-8");
		$this->data = preg_replace($preg, "", $this->data);
		$preg = mb_convert_encoding("/備考２(.*\\r\\n){8}/m", "SJIS-win", "UTF-8");
		$this->data = preg_replace($preg, "", $this->data);
	}
	
	/**
	 * 現在の電力使用量が記述されてるブロックを取得
	 * @return getBlock(6)の結果
	 */
	protected function getActualDemandBlock(){
		return $this->getBlock(8);
	}
}
//(new PowerUsageCepco())->dumpBlock();
?>