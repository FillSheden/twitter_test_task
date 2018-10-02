<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use common\shop\services\AlertService;

$this->title = 'Twitter app';

AlertService::printSessionMessage('twitter-user-creating');
AlertService::printSessionMessage('user_tweet_deleting');
AlertService::printSessionMessage('user_tweet_empty');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to Sheden Twitter app!</h1>
    </div>

    <div class="form" style="background-color: #c8e5bc; height: 30%">
        <div class="form-fields" style="margin: 20px 20px 20px 20px">

            <div class="fields" style="padding-top: 20px">
                <?php
                $form = ActiveForm::begin([
                    'options' => ['id' => 'twitterUserCreatingForm'],
                    'action' => ['save-twitter-user'],
                    'method' => 'post',
                ]); ?>

                <?= $form->field($twitterUserCreatingForm, 'name', [
                    'options' => ['class' => 'form__element-box input-field']
                ])
                    ->textInput(['class' => 'form__element', 'type' => 'text'])
                    ->label('User twitter-name', ['class' => 'form__label'])
                    ->error(['class' => 'help-block help-block-error form__error']); ?>

                <?php
                $statuses = ['1' => 'active', '0' => 'non-active'];
                $params = ['prompt' => 'choose user status...'];
                ?>

                <?= $form->field($twitterUserCreatingForm, 'is_active')
                    ->dropDownList($statuses)
                    ->label('User status'); ?>
            </div>

            <div class="form__element-box">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn--success']) ?>
            </div>

            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>

    <div class="grid" style="margin-top: 30px">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => 'Name',
                    'value' => function ($model) {
                        return $model->name;
                    },
                ],
                [
                    'attribute' => 'created',
                    'label' => 'created',
                    'value' => function ($model) {
                        return $model->created;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{accept}{delete}',
                    'header' => 'user tweets',
                    'buttons' => [
                        'accept' => function ($url, $model, $key) {
                            return Html::a(' Tweets', ['show', 'id' => $model->id], ['class'=>'btn btn-info'] );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('delete', ['delete', 'id' => $model->id], ['class'=>'btn btn-danger']);
                        },
                    ],
                ],
            ],
        ]); ?>
        <?= Html::endForm();?>
    </div>
</div>
