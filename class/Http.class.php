<?php
/**
 * HTTPの入出力処理を行う
 * @author m_ootake
 * @since 2011/05/08
 */
class Http {
	/**
	 * チェックするURL(内部)
	 * @var string
	 */
	protected $url;
	
	/**
	 * 生の$http_response_hader
	 * @var array
	 */
	protected $header;
	
	/**
	 * リクエスト結果の中身
	 * @var string
	 */
	protected $content;
	
	/**
	 * コントラクタ
	 * @param string $url チェックするURL
	 */
	public function __construct($url){
		$this->url = $url;
	}
	
	/**
	 * urlプロパティを元にリクエストを行い、ヘッダと中身を取得する
	 */
	public function request(){
		$this->content = @file_get_contents($this->url);
		$this->header = isset($http_response_header) ? $http_response_header : NULL;
	}
	
	/**
	 * HTTPステータスコードを取得
	 * @return string HTTPステータスコード
	 */
	public function getStatusCode(){
		if(is_null($this->header)) return NULL;
		list($http,$code,$msg) = explode(' ', $this->header[0], 3);
		return $code;
	}
	
	/**
	 * 生のヘッダを取得
	 * @return array http_response_headerと等価の形式
	 */
	public function getRawHeader(){
		return $this->header;
	}
	
	/**
	 * 返却内容を取得
	 */
	public function getContent(){
		return $this->content;
	}
}
/*
$obj = new Http('http://www.google.co.jp/');
$obj->request();
var_dump($obj->getStatusCode(),$obj->getRawHeader(),strlen($obj->getContent()));
*/
?>