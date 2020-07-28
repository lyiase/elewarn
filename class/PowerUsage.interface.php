<?php
interface PowerUsage {
	/**
	 * エリアタイプ(東日本)
	 * @var int
	 */
	const AREA_EAST = 1;
	
	/**
	 * エリアタイプ(西日本)
	 * @var int
	 */
	const AREA_WEST = 2;
	
	/**
	 * 電力会社名(電力は付かない)
	 * @return string 電力会社名
	 */
	public function getName();
	
	/**
	 * 電力会社名(電力が付く)
	 * @return string 電力会社名
	 */
	public function getLongName();
	
	/**
	 * 所属するエリア
	 * @return int PowerUsage::AREA_XXの何れか
	 */
	public function getAreaType();
	
	/**
	 * 電気予報が実行されているか
	 * @return bool 実施中
	 */
	public function isEnabled();
	
	/**
	 * 輪番停電実施可否
	 * @return bool 可否
	 */
	public function isRollingBlackout();
	
	/**
	 * 現在の電力使用量
	 * @return int 電力量(万kW)
	 */
	public function getActualDemand();
	
	/**
	 * 今日の予想需要時間
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getActualDemandTime();
	
	/**
	 * 今日の最大電力量
	 * @return int 電力量(万kW)
	 */
	public function getMaxSupplyCapacity();
	
	/**
	 * 今日の最大電力量更新日時
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxSupplyCapacityModified();
	
	/**
	 * 今日の予想需要
	 * @return int 電力量(万kW)
	 */
	public function getMaxDemandForecast();
	
	/**
	 * 今日の予想需要時間
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxDemandForecastTime();
	
	/**
	 * 今日の予想需要時間更新日時
	 * @return int 取得した時間のUNIXタイムスタンプ
	 */
	public function getMaxDemandForecastModified();
}