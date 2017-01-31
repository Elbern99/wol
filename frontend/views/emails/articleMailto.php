<?php
use yii\helpers\Url;

$mailSubject = 'Thank you for sharing IZA World of Labor';
$articleTitle = trim($articleTitle);
$articleElevatorPitch = trim($articleElevatorPitch);
$articleDoi = trim($articleDoi);
$mailAuthors = '';
$mailDOI = '';
$mailElevatorPitch = '';
$siteUrl = Url::home(true);

if (count($authorsList)) {
    $authors = implode(",", array_map(function($author) {
        return ''.$author['name'].' at '.$author['url'];
    }, $authorsList));
    $mailAuthors = 'by '.$authors;
}

if (count($articleDoi)) {
    $mailDOI = 'DOI: '.$articleDoi;
}

if (count($articleElevatorPitch)) {
    $mailElevatorPitch = 'Elevator Pitch: '.$articleElevatorPitch;
}

$mailText = "I think you that you would be interested in the following article from IZA World of Labor.\r\n\r\n";
$mailText .= "$articleTitle - $articleUrl' $mailAuthors\r\n\r\n";
$mailText .= $mailDOI."\r\n\r\n";
$mailText .= $mailElevatorPitch;
$mailText = htmlentities(urlencode($mailText));
echo 'mailto:?subject='.$mailSubject.'&body='.$mailText;
?>


