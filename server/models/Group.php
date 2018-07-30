<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_group".
 *
 * @property int $group_id 管理组id
 * @property string $name 组名称
 * @property int $status 1表示启用,0表示关闭的
 *
 * @property GroupAuth[] $groupAuths
 * @property UserGroup[] $userGroups
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupAuths()
    {
        return $this->hasMany(GroupAuth::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupMenus()
    {
        return $this->hasMany(MenuGroup::className(), ['group_id' => 'group_id']);
    }

    public static function getGroupIdByUid($id){
        $user=User::findOne($id);
        $group_id=$user->userGroups->group_id;
        return $group_id;
    }
}
