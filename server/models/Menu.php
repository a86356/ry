<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_menu".
 *
 * @property int $menu_id
 * @property string $name 菜单名
 * @property string $router 路由
 * @property int $pid 父id
 * @property int $sort 排序id
 * @property string $moudule 模块名
 * @property string $controller 控制器
 * @property string $action 方法
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'router', 'pid', 'moudule', 'controller', 'action'], 'required'],
            [['pid', 'sort'], 'integer'],
            [['name', 'router', 'moudule', 'controller', 'action'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'name' => 'Name',
            'router' => 'Router',
            'pid' => 'Pid',
            'sort' => 'Sort',
            'moudule' => 'Moudule',
            'controller' => 'Controller',
            'action' => 'Action',
        ];
    }
    //获得后台菜单
    public static function getMenu($id){

        $group_id=User::findOne($id)->userGroups->group_id;

        $menuarr=Group::findOne($group_id)->groupMenus;

        $menuids='';
        for($i=0;$i<count($menuarr);$i++){
            $menuids.=$menuarr[$i]->menu_id.',';
        }
        $menu_ids=substr($menuids,0,strlen($menuids)-1);


        $sql='select * FROM tk_menu order by sort desc';
        if(!empty($menu_ids)){
            $sql=   'select * FROM tk_menu where menu_id in ('.$menu_ids.') order by sort desc';
        }
        $data = \Yii::$app->db->createCommand($sql)->queryAll();

        $pidarr=[];
        $temp=[];
        for($i=0;$i<count($data);$i++){
            $pid=$data[$i]['pid'];
            $menu_id=$data[$i]['menu_id'];
            if($pid==0){
                $data[$i]['child']=[];
                array_push($temp,$data[$i]);
                array_push($pidarr,$menu_id);
            }
        }
        for($i=0;$i<count($data);$i++){
            $pid=$data[$i]['pid'];
            if($pid==0){continue;}

            for($j=0;$j<count($temp);$j++){
                if($pid==$temp[$j]['menu_id']){
                    array_push($temp[$j]['child'],$data[$i]);
                }
            }

        }
        return $temp;
    }
}
