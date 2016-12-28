<?php use yii\helpers\Url; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Newsletter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>
        <p><?= $subscriber->first_name ?> <?= $subscriber->last_name ?></p>
        <p>New Article</p>
        <p><a href="<?= Url::to('/'.$article->url, true) ?>"><?= $article->title ?></a></p>
    </body>
</html>

