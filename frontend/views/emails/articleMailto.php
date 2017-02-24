<?php
use yii\helpers\Url;

$mailSubject = 'Article from IZA World of Labor';
$articleTitle = trim(preg_replace('~\s+~s', ' ', $articleTitle));
$articleElevatorPitch = trim(preg_replace('~\s+~s', ' ', $articleElevatorPitch));
$articleDoi = trim(preg_replace('~\s+~s', ' ', $articleDoi));
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
    $mailDOI = 'DOI: http://dx.doi.org/'.$articleDoi;
}

if (count($articleElevatorPitch)) {
    $mailElevatorPitch = 'Elevator Pitch: '.$articleElevatorPitch;
}

$mailText = "I think that you would be interested in the following article from IZA World of Labor.\r\n\r\n";
$mailText .= "$articleTitle - $articleUrl' $mailAuthors\r\n\r\n";
$mailText .= $mailDOI."\r\n\r\n";
$mailText .= $mailElevatorPitch;
$mailText = htmlentities(rawurlencode($mailText));
echo 'mailto:?subject='.$mailSubject.'&body='.$mailText;
?>


