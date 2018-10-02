<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 21:52
 */

$this->title = 'User '.$user_name. 'Tweets:';
?>

<h1><?=\yii\helpers\Html::encode($this->title)?></h1>
<hr>

<?php foreach ($user_tweets as $tweet) { ?>
        <div class="one tweet">
            <img src="<?=$tweet->user->profile_image_url?>"><br>
            <span>Screen name: <?=$tweet->user->screen_name?></span><br>
            <span>Created: <?= $tweet->created_at ?></span><br>
            <span>Text: <?=$tweet->text?></span><br>
        </div> <br>
<?php } ?>
