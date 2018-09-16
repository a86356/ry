<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/1
 * Time: 11:04
 */

namespace app\modules\v1\forms;


use app\models\BusinessInfo;
use yii\base\Model;

class BusinessInfoForm extends Model
{
    public $reg_addr; //注册地址
    public $bus_reg_name; //工商注册名
    public $bus_license;  //营业执照
    public $bus_license_start_time; //营业执照开始日期
    public $bus_license_end_time; //营业执照结束日期
    public $org_code;   //组织机构代码
    public $org_code_start_time;    //组织机构代码开始日期
    public $org_code_end_time;    //组织机构代码结束日期
    public $legal_person;   //法人名称
    public $legal_person_idcard; // 法人身份证
    public $legal_person_mobile; //法人联系电话
    public $active_scope;   //经营范围

    private $_business_info;

    public function __construct($id)
    {
        parent::__construct();

        $this->_business_info = new BusinessInfo();
        $this->_business_info->bus_id = $id;
    }

    public function rules()
    {
        return [
            [['bus_reg_name','bus_license'],'filter','filter'=>'trim'],
            ['bus_license','unique','targetClass'=>'app\models\BusinessInfo','when'=>function($model)
            {
                //在添加的时候验证
                return $model->_business_info->getIsNewRecord();
            },'message'=>"该营业执照已存在"],
            ['reg_addr','required','message'=>'注册地址不能为空'],
            ['legal_person','required','message'=>'法人不能为空'],
            ['legal_person_idcard','required','message'=>'法人身份证不能为空'],
            [['bus_license_start_time','bus_license_end_time','org_code','org_code_start_time','org_code_end_time','active_scope'],'safe']
        ];
    }

    //保存商户信息
    public function save()
    {
        return $this->validate() && $this->insert();
    }

    //添加商户入网信息
    public function insert()
    {
        $this->_business_info->reg_addr = !empty($this->reg_addr) ? $this->reg_addr : '' ;
        $this->_business_info->bus_reg_name = !empty($this->legal_person) ? "个体户".$this->legal_person : '';
        $this->_business_info->bus_license = !empty($this->bus_license) ? $this->bus_license : '';
        $this->_business_info->bus_license_start_time = !empty($this->bus_license_start_time) ? $this->bus_license_start_time : 1 ;
        $this->_business_info->bus_license_end_time = !empty($this->bus_license_end_time) ? $this->bus_license_end_time : 1 ;
        $this->_business_info->org_code = !empty($this->org_code) ? $this->org_code : '';
        $this->_business_info->org_code_start_time = !empty($this->org_code_start_time) ? $this->org_code_start_time : 1 ;
        $this->_business_info->org_code_end_time = !empty($this->org_code_end_time) ? $this->org_code_end_time : 1 ;
        $this->_business_info->legal_person =!empty($this->legal_person) ? $this->legal_person : '';
        $this->_business_info->legal_person_idcard = !empty($this->legal_person_idcard) ? $this->legal_person_idcard : '';
        $this->_business_info->legal_person_mobile = \Yii::$app->getRequest()->post('username');
        $this->_business_info->active_scope = !empty($this->active_scope) ? $this->active_scope : '';

        return $this->_business_info->save();
    }
}