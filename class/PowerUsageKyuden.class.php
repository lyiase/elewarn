<?php
/**
 * 九州電力
 * @author Lyiase
 */
class PowerUsageKyuden extends PowerUsageAbstract {
	
	protected $area = self::AREA_WEST;
	protected $name = "九州";
	protected $fullname = "九州電力";
	
	public function __construct($url=NULL){
		if(is_null($url)){
			$url = sprintf("http://www.kyuden.co.jp/power_usages/csv/electric_power_usage%s.csv",date('Ymd'));
		}
		
		parent::__construct($url);
	}
}
?>