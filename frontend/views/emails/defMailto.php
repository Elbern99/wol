<?php
    use yii\helpers\Url;
    $articleTitle = trim(preg_replace('~\s+~s', ' ', $articleTitle));
    $typeContentFirtUc = ucfirst($typeContent);
    $articleSubject = $typeContentFirtUc.' from IZA World of Labor';

    $mailText = "I think that you would be interested in the following $typeContent from IZA World of Labor.\r\n\r\n";
    $mailText .= "$articleTitle - $articleUrl";
    $mailText = htmlentities(rawurlencode($mailText));
    echo 'mailto:?subject='.$articleSubject.'&body='.$mailText;
?>