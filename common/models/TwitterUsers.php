<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "twitter_users".
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property string $created
 */
class TwitterUsers extends \yii\db\ActiveRecord
{

    const ACTIVE_USER = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'twitter_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'created'], 'required'],
            [['is_active'], 'integer'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created' => Yii::t('app', 'Created'),
        ];
    }
}
