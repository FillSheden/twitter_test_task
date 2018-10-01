<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 21:06
 */

namespace common\shop\repositories;

use yii\data\ActiveDataProvider;
use common\models\TwitterUsers;

class TwitterUserRepository extends TwitterUsers
{
    public function getProviderUsers()
    {
        return new ActiveDataProvider([
            'query' => $this::find()->where(['=', 'is_active', $this::ACTIVE_USER]),
            'pagination' => false,
        ]);
    }

    public function trySaveUser($twitter_user)
    {

        $twitter_model = new TwitterUsers;
        $twitter_model->name = $twitter_user['name'];
        $twitter_model->is_active = $twitter_user['is_active'];
        $twitter_model->created = date('Y-m-d');
        if ($twitter_model->save()) {
            return true;
        } return false;
    }

    public function getUserScreenName($id)
    {
        $twitter_user = TwitterUsers::find()->select('name')->where(['=', 'id', $id])->one();
        return $twitter_user->name;
    }
}