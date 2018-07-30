<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_article".
 *
 * @property int $article_id 文章id
 * @property int $dateline 发布时间
 * @property string $title 发布标题
 * @property string $content 发布内容
 * @property int $view 阅读量
 *
 * @property ArticleCategory[] $articleCategories
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateline', 'title', 'content', 'view'], 'required'],
            [['dateline', 'view'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'dateline' => 'Dateline',
            'title' => 'Title',
            'content' => 'Content',
            'view' => 'View',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategories()
    {
        return $this->hasMany(ArticleCategory::className(), ['article_id' => 'article_id']);
    }
}
