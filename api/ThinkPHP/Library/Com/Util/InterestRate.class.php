<?php

namespace Com\Util;

/**
 * 债权转让年化收益率算法
 * （总计获利/持有天数）*当年总天数/本金
 *
 * 【卖方】sell
 * 一次性：once
 * （（（已收利息总额+溢价收益）/持有天数）*当年总天数）/本金 *
 * 每月：month
 * （（（报价价格-本金）+已到账利息）/持有天数））*当年总天数）/本金
 *
 * 【买方】buy
 * 一次性：once
 * （（预计总收益-交易金额）/剩余持有天数）*当年总天数/本金
 * （（预计总收益-报价余额）/剩余持有天数）*当年总天数/本金
 * 每月：month
 * (（（预计总收益-报价余额-已到账利息）/剩余天数）*当年天数)/本金
 *
 * --------------------------------------------------------------------------------
 * 差价 = 报价 - （本金+当前时间点所产生的利息）
 * 卖方溢价 = 差价 + 已收利息
 * 买方剩余利息 = 总利息 - 卖方溢价；
 * 买方剩余利息 = 总利息 - （差价 + 已收利息）
 * 买方剩余利息 = 总利息 - （（报价 - (本金+当前时间所产生的利息）) + 已收利息）
 *
 *
 * 卖方年收益率 = （卖方溢价 / 持有天数） * 365/ 本金
 * 买方年收益率 = （买方剩余利息 / 剩余持有天数） * 365 / 本金
 */

/**
 * 利息计算类
 * 可用于融资项目的利息计算，主要用于债权交易年化利率转换
 *
 * @author ruyi
 *        
 */
class InterestRate {
	
	// 融资项目本金
	public $principal = 0.00;
	// 融资项目年化利率
	public $year_rate = 0.00;
	// 债权交易报价、成交价格、交易价格（卖方报价）
	public $deal_price = 0.00;
	
	// 计息开始时间、认购开始时间
	public $start_date = 0;
	// 计息结束时间、认购结束时间
	public $end_date = 0;
	// 债权交易报价时间、债权认购时间
	public $deal_date = 0;
	
	// 卖方年化收益率
	public $sell_rate = 0.00;
	// 卖方年化收益率
	public $buy_rate = 0.00;
	
	// 已收利息
	public $prepaid_interest = 0.00;
	
	// 每月还款日的时间戳
	public $repay_date = 0;
	
	// 还款利息（按还款日计算）
	public $repay_interest = 0;
	
	// 精确度
	public $precision = 2;
	// 一天的秒数60*60*24
	public $_day_second = 86400;
	// 一年的天数
	public $_day_year = 365;
	// 当前时间
	public $_now_time = 0;
	// 当前时间戳转出的格式
	public $_date_format = 'Y-m-d H:i:s';
	
	// 错误信息
	private $error = array ();
	
	/**
	 * 初始化
	 *
	 * @param real $principal
	 *        	本金
	 * @param real $year_rate
	 *        	融资项目年化收益率
	 * @param real $deal_price
	 *        	债权报价
	 * @param int|string $start_date
	 *        	开始时间
	 * @param int|string $end_date
	 *        	结束时间
	 * @param int|string $deal_date
	 *        	交易时间
	 * @param float $precision
	 *        	精确度
	 */
	public function __construct() {
		// 自动设置时区为中国时区
		// date_default_timezone_set ( 'RPC' );
		// 不传值的情况下自动进行初始化
		$this->setNowTime ();
	}
	
	/**
	 * 设置当前类时间戳格式
	 *
	 * @param string $_date_format
	 *        	时间戳格式
	 * @return string
	 */
	public function setDateFormat($_date_format = 'Y-m-d H:i:s') {
		return $this->_date_format = $_date_format;
	}
	
	/**
	 * 获取当前类时间戳的格式（暂时用于debug输出）
	 *
	 * @return string
	 */
	public function getDateFormat() {
		return $this->_date_format;
	}
	
	/**
	 * 设置当前时间（时间戳）
	 *
	 * @param int $_now_time
	 *        	需要设置当前时间的时间戳，默认为0
	 * @return int 时间戳
	 */
	public function setNowTime($_now_time = 0) {
		$_now_time = intval ( $_now_time );
		$this->_now_time = $_now_time ? $_now_time : time ();
		return $this->_now_time;
	}
	
	/**
	 * 获取当前时间戳
	 *
	 * @return int 当前时间戳
	 */
	public function getNowTime() {
		return $this->_now_time;
	}
	
	/**
	 * 设置本金
	 *
	 * @param float $principal        	
	 * @return float
	 */
	public function setPrincipal($principal) {
		$this->principal = floatval ( $principal );
		if (! $this->principal) {
			$this->setError ( __FUNCTION__, "principal is empty!" );
		}
		return $this->principal;
	}
	
	/**
	 * 获取本金
	 *
	 * @return float
	 */
	public function getPrincipal() {
		return $this->principal;
	}
	
	/**
	 * 设置融资项目的年化收益率
	 *
	 * @param float $year_rate        	
	 * @return float
	 */
	public function setYearRate($year_rate) {
		$this->year_rate = floatval ( $year_rate );
		if (! $this->year_rate) {
			$this->setError ( __FUNCTION__, "year_rate is empty!" );
		}
		return $this->year_rate;
	}
	
	/**
	 * 获取年化收益率
	 *
	 * @return float
	 */
	public function getYearRate() {
		return $this->year_rate;
	}
	
	/**
	 * 获取每日利率
	 *
	 * @return float
	 */
	public function getDayRate() {
		// 年化利率365天
		return $this->year_rate / $this->_day_year;
	}
	
	/**
	 * 获取年收益
	 *
	 * @return float
	 */
	public function getYearInterest() {
		return $this->principal * $this->year_rate;
	}
	
	/**
	 * 获取日利息
	 *
	 * @return float
	 */
	public function getDayInterest() {
		// 本金 * 日息利率
		return $this->getPrincipal () * $this->getDayRate ();
	}
	
	/**
	 * 获取项目预期收益利息（按天）
	 *
	 * @return float
	 */
	public function getAllInterest() {
		return $this->getDayInterest () * $this->getAllDay ();
	}
	
	/**
	 * 本息合计
	 *
	 * @return float
	 */
	public function getAllPrice() {
		return $this->getPrincipal () + $this->getAllInterest ();
	}
	
	/**
	 * 获取溢价（报价-本金-当前交易时间的利息）（卖方）
	 *
	 * @return float
	 */
	public function getPremium() {
		// 报价 - 本金 - 卖方持有天数 所产生的利息
		return $this->getDealPrice() - $this->getPrincipal() - $this->getFinishInterest() + $this->getPrepaidInterest();
	}
	
	/**
	 * 设置融资开始时间
	 *
	 * @param int $start_date
	 *        	开始时间的时间戳
	 * @param bool $is_close_interval
	 *        	开始时间是否为闭区间，默认为是，（否为闭区间）
	 * @return int
	 */
	public function setStartDate($start_date, $is_close_interval = true) {
		$this->start_date = intval ( $start_date );
		$this->start_date = $this->start_date ? $this->start_date : 0;
		// 为开区间的时候减1天
		$this->start_date = $is_close_interval ? $this->start_date : $this->start_date - 86400;
		// 转成整点的时间戳
		$this->start_date = $this->changeFullDate ( $this->start_date );
		return $this->start_date;
	}
	
	/**
	 * 获取融资开始时间
	 *
	 * @return int
	 */
	public function getStartDate() {
		return $this->start_date;
	}
	
	/**
	 * 设置融资结束时间
	 *
	 * @param int $end_date
	 *        	结束时间的时间戳
	 * @param bool $is_open_interval
	 *        	结束时间是否为开区间，默认为是，（否为开区间）
	 * @return int
	 */
	public function setEndDate($end_date, $is_open_interval = true) {
		$this->end_date = intval ( $end_date );
		$this->end_date = $this->end_date ? $this->end_date : 0;
		// 为开区间的时候减1天
		$this->end_date = $is_open_interval ? $this->end_date : $this->start_date + 86400;
		// 转成整点的时间戳
		$this->end_date = $this->changeFullDate ( $this->end_date );
		return $this->end_date;
	}
	
	/**
	 * 获取融资结束时间
	 *
	 * @return int
	 */
	public function getEndDate() {
		return $this->end_date;
	}
	
	/**
	 * 获取预期天数（融资完成的利息天数天数）
	 *
	 * @return int
	 */
	public function getAllDay() {
		$day = $this->getEndDate () - $this->getStartDate ();
		if ($day <= 0) {
			$this->setError ( __FUNCTION__, "end_date <= start_date" );
		}
		
		return $day / $this->_day_second;
	}
	
	/**
	 * 设置债权交易的报价
	 *
	 * @param float $deal_price        	
	 * @return float
	 */
	public function setDealPrice($deal_price) {
		$this->deal_price = floatval ( $deal_price );
		if (! $this->deal_price) {
			$this->setError ( __FUNCTION__, "deal_price is empty!" );
		}
		return $this->deal_price;
	}
	
	/**
	 * 获取债权交易的报价
	 *
	 * @return float
	 */
	public function getDealPrice() {
		return $this->deal_price;
	}
	
	/**
	 * 获取债权报价最大的范围
	 *
	 * @return float 报价最大范围
	 */
	public function getDealPriceMax() {
		// 本金+利息-已收利息
		return $this->getPrincipal () + $this->getAllInterest () - $this->getPrepaidInterest ();
	}
	
	/**
	 * 获取债权报价最小范围
	 *
	 * @return float 报价最小范围
	 */
	public function getDealPriceMin() {
		return $this->getPrincipal ();
	}
	
	/**
	 * 设置债权交易时间
	 * （主要用于卖方持有天数计算）
	 *
	 * @param int $deal_date
	 *        	交易时间的时间戳
	 * @param bool $is_open_interval
	 *        	交易时间是否为开区间，默认为是，（否为开区间）
	 * @return float
	 */
	public function setDealDate($deal_date = 0, $is_open_interval = true) {
		$this->deal_date = intval ( $deal_date );
		$this->deal_date = $this->deal_date ? $this->deal_date : $this->getNowTime ();
		$this->deal_date = $is_open_interval ? $this->deal_date : $this->deal_date + 86400;
		// 转成整点的时间戳
		$this->deal_date = $this->changeFullDate ( $this->deal_date );
		return $this->deal_date;
	}
	
	/**
	 * 获取债权交易时间
	 *
	 * @return float
	 */
	public function getDealDate() {
		return $this->deal_date;
	}
	
	/**
	 * 设置还款日
	 *
	 * @param int $repay_day
	 *        	还款日，例如还款日为每月15日，这里值就设置为15
	 * @return int 时间戳
	 */
	public function setRepayDate($repay_day) {
		$repay_day = intval ( $repay_day );
		if ($repay_day < 1 || $repay_day > 27) {
			$this->setError ( __FUNCTION__, "repay_day is not by (1 > repay_day > 27) day" );
		}
		
		// 获取当前时间的日期天
		$now_day = date ( 'd', $this->getNowTime () );
		$now_day = intval ( $now_day );
		
		// 构造当前月还款的日期
		$repay_day = date ( 'Y-m', $this->getNowTime () ) . '-' . $repay_day . ' 00:00:00';
		// 转换党员还款日的时间戳
		$this->repay_date = strtotime ( $repay_day );
		
		// 如果还款日大于当前时间，计算已还款的时间为当月还款日
		// 如果还款日小于等于当前时间，计算已还款的时间为上月还款日
		$this->repay_date = $repay_day > $now_day ? $this->repay_date : strtotime ( '-1 month', $this->repay_date );
		
		// 当前还款日不作为计息，为开区间
		// 转换成整点的时间戳
		$this->repay_date = $this->changeFullDate ( $this->repay_date );
		return $this->repay_date;
	}
	
	/**
	 * 获取还款日的时间戳
	 *
	 * @return int 时间戳
	 */
	public function getRepayDate() {
		return $this->repay_date;
	}
	
	/**
	 * 获取已还款天数
	 *
	 * @return int 时间戳
	 */
	public function getRepayFinishDay() {
		$repay_finish_day = $this->getRepayDate () - $this->getStartDate ();
		if ($repay_finish_day <= 0) {
			$this->setError ( __FUNCTION__, "repay_day <= start_date" );
		}
		return $repay_finish_day / $this->_day_second;
	}
	
	/**
	 * 设置已还款利息
	 */
	public function setRepayInterest($repay_interest = 0) {
		$this->repay_interest = intval ( $repay_interest );
		return $$this->repay_interest;
	}
	
	/**
	 * 获取已还款利息，当没有设置还款利息时，自动按照还款日计算
	 *
	 * @return number
	 */
	public function getRepayInterest() {
		return $this->repay_interest ? $this->repay_interest : ($this->getRepayFinishDay () * $this->getDayInterest ());
	}
	
	/**
	 * 获取已完成天数（卖方）
	 *
	 * @return int
	 */
	public function getFinishDay() {
		$sell_hold_date = $this->getDealDate () - $this->getStartDate ();
		if ($sell_hold_date <= 0) {
			$this->setError ( __FUNCTION__, "deal_date <= start_date" );
		}
		$sell_hold_date = $sell_hold_date / $this->_day_second;
		return $sell_hold_date;
	}
	
	/**
	 * 获取已完成天数的利息（卖方持有天数所产生的利息）
	 *
	 * @return float
	 */
	public function getFinishInterest() {
		// 持有天数 * 卖方日利息
		return $this->getFinishDay () * $this->getDayInterest ();
	}
	
	/**
	 * 获取剩余计息天数（买方购买后剩余计息的天数）
	 *
	 * @return int
	 */
	public function getSurplusDay() {
		$buy_hold_date = $this->end_date - $this->deal_date;
		if ($buy_hold_date <= 0) {
			$this->setError ( __FUNCTION__, "end_date <= deal_date" );
		}
		$buy_hold_date = $buy_hold_date / $this->_day_second;
		return $buy_hold_date;
	}
	
	/**
	 * 获取剩余利息
	 *
	 * @return float
	 */
	public function getSurplusInterest() {
		// 剩余天数 * 日息
		// return $this->getSurplusDay () * $this->getDayInterest ();
		// 全部利息-当前利息
		return $this->getAllInterest () - $this->getFinishInterest ();
	}
	
	/**
	 * 获取买方的（剩余|预期）利息
	 *
	 * @return float
	 */
	public function getBuyInterest() {
		// 项目（预期）利息 - （卖方持有天数利息 + 已收利息）
		// return $this->getAllInterest () - ($this->getFinishInterest () + $this->getPrepaidInterest ());
		// return $this->getAllInterest () - $this->getFinishInterest () - $this->getPrepaidInterest ();
		return $this->getAllInterest () - $this->getSellInterest ();
	}
	
	/**
	 * 获取买方年化利率
	 *
	 * @param bool $is_round
	 *        	是否进行四舍五入
	 * @param int $precision
	 *        	精确度
	 * @return float
	 */
	public function getBuyYearRate($is_round = true, $precision = 0) {
		// 数据校验
		$is_round = ( bool ) $is_round;
		$precision ? $this->setPrecision ( $precision ) : '';
		
		$buy_year_rate = 0;
		// （（买方预期利息 / 剩余天数） * 365）/ 本金
		$buy_year_rate = $this->getBuyInterest () / $this->getSurplusDay ();
		$buy_year_rate = $buy_year_rate * $this->_day_year;
		$buy_year_rate = $buy_year_rate / $this->getPrincipal ();
		$buy_year_rate = $buy_year_rate * 100;
		
		// 是否进行四舍五入
		if ($is_round) {
			return round ( $buy_year_rate, $this->getPrecision () );
		} else {
			return $buy_year_rate;
		}
	}
	
	/**
	 * 设置已收利息（卖方）（已收金额）
	 *
	 * @param float $prepaid_interest        	
	 * @return float
	 */
	public function setPrepaidInterest($prepaid_interest) {
		return $this->prepaid_interest = floatval ( $prepaid_interest );
	}
	
	/**
	 * 获取已收利息（卖方）（已完成利息）
	 *
	 * @return float
	 */
	public function getPrepaidInterest() {
		return $this->prepaid_interest;
	}
	
	/**
	 * 获取卖方预期收入的利息
	 *
	 * @return float
	 */
	public function getSellInterest() {
		return $this->getFinishInterest () + $this->getPremium ();
	}
	
	/**
	 * 获取卖方年化收益率
	 *
	 * @param bool $is_round
	 *        	是否进行四舍五入
	 * @param int $precision
	 *        	精确度
	 * @return float
	 */
	public function getSellYearRate($is_round = true, $precision = 0) {
		
		// 数据校验
		$is_round = ( bool ) $is_round;
		$precision ? $this->setPrecision ( $precision ) : '';
		
		$sell_year_rate = 0;
		// （（（持有天数利息 +溢价 + 已收利息）/持有天数）*365）/本金
		// $sell_year_rate = $this->getFinishInterest () + $this->getPremium () + $this->getPrepaidInterest ();
		$sell_year_rate = $this->getSellInterest () / $this->getFinishDay ();
		$sell_year_rate = $sell_year_rate * $this->_day_year;
		$sell_year_rate = $sell_year_rate / $this->getPrincipal ();
		$sell_year_rate = $sell_year_rate * 100;
		
		// 是否进行四舍五入
		if ($is_round) {
			return round ( $sell_year_rate, $this->getPrecision () );
		} else {
			return $sell_year_rate;
		}
	}
	
	/**
	 * 设置年化收益率的精确度
	 *
	 * @param int $precision        	
	 * @return int
	 */
	public function setPrecision($precision = 2) {
		$this->precision = intval ( $precision );
		return $this->precision;
	}
	
	/**
	 * 设置年化收益率的精确度
	 *
	 * @return float
	 */
	public function getPrecision() {
		return $this->precision;
	}
	
	/**
	 * 校验开始时间、成交时间、结束时间的准确性
	 *
	 * @return boolean
	 */
	public function verifyDate() {
		if ($this->getEndDate () - $this->getStartDate () <= 0) {
			$this->setError ( __FUNCTION__, "end_date <= start_date" );
			return false;
		} elseif ($this->getDealDate () - $this->getStartDate () <= 0) {
			$this->setError ( __FUNCTION__, "deal_date <= start_date" );
			return false;
		}
		return true;
	}
	
	/**
	 * 校验利息误差
	 *
	 * @return float;
	 */
	public function verifyInterest() {
		// （已完成利息 + 剩余利息 ） - 全部利息
		// echo($this->getAllInterest ());echo "|";
		// echo ($this->getFinishInterest ());echo "|";
		// echo ($this->getSurplusInterest ());
		$verify_interest = $this->getAllInterest () - ($this->getFinishInterest () + $this->getSurplusInterest ());
		if ($verify_interest != 0) {
			$this->setError ( __FUNCTION__, "verify interest is miss! the value is {$verify_interest}" );
		}
		return $verify_interest;
	}
	
	/**
	 * 设置错误输出
	 *
	 * @param string $key        	
	 * @param string $info        	
	 * @return array
	 */
	private function setError($key, $info) {
		$this->error [$key] = $info;
		return $this->error;
	}
	public function getError() {
		return $this->error;
	}
	
	/**
	 * 获取全部的计算结果集
	 *
	 * @return multitype:string number
	 */
	public function getResult() {
		return array (
				"principal" => $this->getPrincipal (),
				"year_rate" => $this->getYearRate (),
				"year_rate_percent" => $this->getYearRate () * 100 . ' %',
				"deal_price" => $this->getDealPrice (),
				"prepaid_interest" => $this->getPrepaidInterest (),
				"start_date" => $this->getStartDate (),
				"start_date_format : " => date ( $this->getDateFormat (), $this->getStartDate () ),
				"deal_date" => $this->getDealDate (),
				"deal_date format" => date ( $this->getDateFormat (), $this->getDealDate () ),
				"end_date" => $this->getEndDate (),
				"end_date_format" => date ( $this->getDateFormat (), $this->getEndDate () ),
				"repay_date" => $this->getRepayDate (),
				"repay_date_format" => date ( $this->getDateFormat (), $this->getRepayDate () ),
				"repay_finish_day" => $this->getRepayFinishDay (),
				"repay_interest" => $this->getRepayInterest (),
				
				"day_interest" => $this->getDayInterest (),
				"all_day" => $this->getAllDay (),
				"all_interest" => $this->getAllInterest (),
				"all_price" => $this->getAllPrice (),
				
				"finish_day" => $this->getFinishDay (),
				"finish_interest" => $this->getFinishInterest (),
				"premium" => $this->getPremium (),
				"max_deal_price" => $this->getDealPriceMax (),
				"min_deal_price" => $this->getDealPriceMin (),
				
				"surplus_day" => $this->getSurplusDay (),
				"surplus_interest" => $this->getSurplusInterest (),
				
				"sell_interest" => $this->getSellInterest (),
				"sell_year_rate_round" => $this->getSellYearRate () . ' %',
				"sell_year_rate" => $this->getSellYearRate ( false ) . ' %',
				
				"buy_interest" => $this->getBuyInterest (),
				"buy_year_rate_round" => $this->getBuyYearRate () . ' %',
				"buy_year_rate" => $this->getBuyYearRate ( false ) . ' %' 
		);
	}
	
	/**
	 * 打印输出错误
	 *
	 * @param string $is_var_dump
	 *        	是否已var_dump输出
	 */
	public function printrError($is_var_dump = false) {
		$is_var_dump ? var_dump ( $this->error ) : print_r ( $this->error );
	}
	
	/**
	 * 调试输出
	 */
	public function debugPrint() {
		echo "<pre>";
		echo "<h1>This is InterestRate class comput result!</h1>";
		echo "<hr/>";
		print_r ( $this->getResult () );
		echo "<hr/>";
		var_dump ( $this->getResult () );
		echo "<hr/>";
		
		// 校验时间
		echo "<h1>This is Verify Date!</h1>";
		echo "<hr/>";
		echo "verifyDate : \n";
		$this->verifyDate ();
		echo "<hr/>";
		
		// 校验利息
		echo "<h1>This is Verify Interest!</h1>";
		echo "<hr/>";
		echo "verifyInterest : \n";
		$this->verifyInterest ();
		echo "<hr/>";
		
		echo "<h1>This is Error Info!</h1>";
		echo "<hr/>";
		$this->printrError ();
		echo "<hr/>";
		echo "</pre>";
	}
	
	/**
	 * 转换整点的时间戳
	 *
	 * @param int $date
	 *        	需要转换的时间戳
	 * @return int
	 */
	public function changeFullDate($date) {
		$date = intval ( $date );
		return strtotime ( date ( 'Y-m-d', $date ) . " 00:00:00" );
	}
}

/*
// 使用示例：
echo "<pre>";
// ThinkPHP3.2调用方式
// $ir = new \Com\Util\InterestRate;
$ir = new InterestRate ();
// echo $s = strtotime ( '2014-03-31 00:00:00' );
// echo "\n<br/>";

// echo $e = strtotime ( '2014-03-15 00:00:00' );
// echo "|" . date ( 'Y-m-d H:i:s', $s );
// echo "\n<br/>";

// echo date ( 'Y-m-d 00:00:00', 1382400 + 1394838000 );
// die ();
// 设置本金
$ir->setPrincipal ( 10000 );
// $ir->setPrincipal ( 0 );
// 设置年化收益率
// $ir->setYearRate ( 0.13 );
$ir->setYearRate ( 0.12 );
// $ir->setYearRate ( 0 );
// 设置报价
// $ir->setDealPrice ( 11200 );
$ir->setDealPrice ( 10300 );
// $ir->setDealPrice ( 10302.5 );
// $ir->setDealPrice ( 10400 );
// $ir->setDealPrice ( 10000 );
// $ir->setDealPrice ( 9000 );
// 设置还款日
$ir->setRepayDate ( 15 );
// 设置已收利息
$ir->setPrepaidInterest ( 0 );
// $ir->setPrepaidInterest ( 100 );
// $ir->setPrepaidInterest ( 300 );
// 设置融资开始时间
$ir->setStartDate ( strtotime ( '2014-03-15 00:00:00' ) );
// 设置融资结束时间
$ir->setEndDate ( strtotime ( '2014-03-31 00:00:00' ) );
// $ir->setEndDate ( strtotime ( '2014-03-15 20:00:00' ) );
// $ir->setEndDate ( strtotime ( '2016-03-15 20:00:00' ) );
// 设置交易（报价）时间
$ir->setDealDate ( strtotime ( '2014-06-14 00:00:00' ) );
// 设置精确度
$ir->setPrecision ( 4 );

print_r ( $ir->getResult () );
echo "<hr />";
var_dump ( $ir->getResult () );

// 校验时间
$ir->verifyDate ();
// 校验利息
$ir->verifyInterest ();

echo "Error info : \n";
$ir->printrError ();
die ();
*/