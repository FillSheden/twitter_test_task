<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 20:30
 */

namespace common\shop\forms;

use yii\base\Model;

class TwitterUserCreatingForm extends Model
{
    public $name;
    public $is_active;
    public $created;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'is_active'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['is_active'], 'integer'],
            ['created', 'date'],
        ];
    }
}