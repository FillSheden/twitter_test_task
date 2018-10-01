<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 21:52
 */
?>

<h1>User <?=$user_name?> Tweets:</h1>
<hr>

<?php foreach ($user_tweets as $tweet) { ?>
        <div class="one tweet">
            <span>Created: <?=$tweet->created_at?></span><br>
            <span>Text: <?=$tweet->text?></span><br>
        </div> <br>
<?php } ?>
