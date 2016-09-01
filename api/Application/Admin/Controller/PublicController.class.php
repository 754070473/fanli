<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
use Admin\Status\Param;

/**
 * Class PublicController
 * @package Admin\Controller
 *
 */
class PublicController extends CommonController
{
    public function index()
	{
		//表名
		$sql = IsNaN( $this -> _data , 'sql' );
		if( empty( $sql ) ){
			$this -> errorMessage( Param::SELECT_TABLE_NAME_IS_NULL , Param::SELECT_TABLE_NAME_IS_NULL_MSG );
		}
		//查询字段
		$field = IsNaN( $this -> _data , 'field' );
		if( empty( $field ) ){
			$this -> errorMessage( Param::SELECT_FIELD_NAME_IS_NULL , Param::SELECT_FIELD_NAME_IS_NULL_MSG );
		}

		//当前页码
		$page = IsNaN( $this -> _data , 'p' );
		if( empty( $page ) ){
			$page = 1;
		}

		//排序
		$order = IsNaN( $this -> _data , 'order' );
		if( empty( $order ) ){
			$order = 1;
		}

		//每页显示数据条数
		$data_size = IsNaN( $this -> _data , 'num' );
		if( empty( $data_size ) ){
			$data_size = 0;
		}

		//条件
		$where = IsNaN( $this -> _data , 'where' );
		if( empty( $where ) ) {
			$where = 1;
		}

		$User = M( "" );
		if( $data_size == 0 ){

			$arr = $User -> field( "$field" ) -> table( "$sql" ) -> where( "$where" ) -> order( "$order" ) -> select();

		}elseif ( $data_size == 1 ){

			$arr = $User -> field( "$field" ) -> table( "$sql" ) -> where( "$where" ) -> order( "$order" ) -> find();

		}else {
			//计算偏移量
			$n = ( $page - 1 ) * $data_size;

			$arr = $User -> field( "$field" ) -> table( "$sql" ) -> where( "$where" ) -> order( "$order" ) -> limit( $n , $data_size ) -> select();
		}
		if( empty( $arr ) ){
			$this -> errorMessage(
				Status::SELECT_DATA_ERROR ,
				Status::SELECT_DATA_ERROR_MSG
			);
			exit;
		}else {
			$this->success(
				Success::SELECT_SUCCESS ,
				Success::SELECT_SUCCESS_MSG ,
				$arr
			);
			exit;
		}
	}
}