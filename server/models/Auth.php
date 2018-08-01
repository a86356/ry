<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_auth".
 *
 * @property string $auth_id 自增ID
 * @property string $auth_name 权限名称
 * @property string $parent_id 父权限ID:0顶级权限
 * @property string $module_name 模块名
 * @property string $auth_c 控制器名称
 * @property string $auth_a 方法名称
 * @property int $sort_order 排序
 * @property int $is_menu 是否为菜单
 * @property string $ico 图标
 *
 * @property GroupAuth[] $groupAuths
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_name'], 'required'],
            [['parent_id', 'sort_order', 'is_menu'], 'integer'],
            [['auth_name', 'module_name', 'auth_c', 'auth_a'], 'string', 'max' => 32],
            [['ico'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auth_id' => 'Auth ID',
            'auth_name' => 'Auth Name',
            'parent_id' => 'Parent ID',
            'module_name' => 'Module Name',
            'auth_c' => 'Auth C',
            'auth_a' => 'Auth A',
            'sort_order' => 'Sort Order',
            'is_menu' => 'Is Menu',
            'ico' => 'Ico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupAuths()
    {
        return $this->hasMany(GroupAuth::className(), ['auth_id' => 'auth_id']);
    }

    //获得权限列表
    public static function getAuthList($page='',$name=''){
        $group_id=Group::getGroupId();

        $pagesize=\Yii::$app->params['pagesize'];

        if(empty($page)){
            $page=1;
        }

        $sql='select * FROM tk_group inner join tk_group_auth on  tk_group.group_id=tk_group_auth.group_id inner join tk_auth on tk_group_auth.auth_id=tk_auth.auth_id ';

        if(!empty($name)){
            $sql.=' where tk_auth.auth_name like %'.$name."%";
        }
        $sql.=' and tk_group.group_id= '.$group_id.' limit '.($page-1)*$pagesize.','.$pagesize;


        $data = \Yii::$app->db->createCommand($sql)->queryAll();


        $total_count=count($data);
        return ['list'=>$data,'total_count'=>$total_count,'page'=>$page,'pagesize'=>$pagesize];

    }
}
