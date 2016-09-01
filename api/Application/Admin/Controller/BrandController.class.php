<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Admin\Status\Status;
use Admin\Status\Success;
use Admin\Status\Param;


class BrandController extends CommonController
{
	/**
	* 添加品牌接口
	*/
	public function index()
	{
		//实例化品牌表
		$brand = M('brand');
		//接收数据
		$bra_name = IsNaN( $this -> _data , 'bra_name' );     //品牌名称
		$remark = IsNaN( $this -> _data , 'bra_remark' );      //品牌备注
		$connect = IsNaN( $this -> _data , 'bra_connect' );    //品牌简介
		$status = IsNaN( $this -> _data , 'bra_status' );      //品牌状态
		$logo = IsNaN( $this -> _data , 'bra_logo' );      //品牌logo
		if (empty($bra_name) || empty($remark) || empty($connect) || empty($logo)) 
		{
			echo $this -> errorMessage( Param::BRAND_IS_NULL , Param::BRAND_IS_NULL_MSG );die;
		}

		//验证品牌名称是否合法
		$preg='/^[a-zA-Z]+$/u';
		$preg1='/^[\x{4e00}-\x{9fa5}]+$/u';
		if (!preg_match($preg, $bra_name) && !preg_match($preg1, $bra_name)) 
		{
			echo $this -> errorMessage( Status::BRAND_NAME_ERROR , Status::BRAND_NAME_ERROR_MSG );die;
		}

		//查询品牌名称是否存在
		$res = $brand->where("bra_name = '$bra_name'")->find();
		if ($res) 
		{
			//品牌名称已存在
			 echo $this -> errorMessage( Status::BRAND_NAME , Status::BRAND_NAME_MSG );die;
		}
		else
		{
			$data['bra_name'] = $bra_name;
			$data['bra_remark'] = $remark;
			$data['bra_status'] = $status;
			$data['bra_connect'] = $connect;
			$data['bra_logo'] = $logo;
			$data['bra_date'] = date("Y-m-d",time());
			// print_r($data);die;
			$res = $brand->add($data);
			// print_r($res);die;
			if ($res) 
			{
				$this -> success( Success::BRAND_ADD_SUCCESS , Success::BRAND_ADD_SUCCESS_MSG);
			}
			else
			{
				$this -> errorMessage( Status::BRAND_ADD_ERROR , Status::BRAND_ADD_ERROR_MSG);
			}
			
		}
	}

	//品牌的启用或禁用
	public function is_lock()
	{
		//实例化表
		$brand = M('brand');
		$bra_id = IsNaN( $this -> _data ,'bra_id' );
		$v = IsNaN( $this -> _data ,'v' );
		// print_r($bra_id);die;
		$res = $brand-> where("bra_id = '$bra_id'")->setField('bra_status',$v); 
		if ($res) 
		{
			$this -> success( Success::BRAND_LOCK_SUCCESS , Success::BRAND_LOCK_SUCCESS_MSG);
		}
		else
		{
			$this -> errorMessage( Status::BRAND_LOCK_ERROR , Status::BRAND_LOCK_ERROR_MSG);
		}

	}

	//品牌的删除
	public function del()
	{
		//实例化表
		$brand = M('brand');
		$bra_id = IsNaN( $this -> _data ,'bra_id' );
		$last_id = IsNaN( $this -> _data ,'last_id' );
		$num = $brand->where("bra_id='$bra_id'")->delete();
		// print_r($num);die;
	 	if ($num) 
	 	{
	 		$arr = $brand->where("bra_id > '$last_id'")->limit("$num")->select();
	 		$this -> success( Success::BRAND_DEL_SUCCESS , Success::BRAND_DEL_SUCCESS_MSG,$arr);
	 	}
	 	else
	 	{
	 		$this -> errorMessage( Status::BRAND_DEL_ERROR , Status::BRAND_DEL_ERROR_MSG);
	 	}
	}


}
