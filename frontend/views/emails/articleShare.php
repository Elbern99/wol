<?php
use yii\helpers\Url;

$mailSubject = 'Quote from IZA World of Labor article';
$articleTitle = trim(preg_replace('~\s+~s', ' ', $articleTitle));
$articleDoi = trim(preg_replace('~\s+~s', ' ', $articleDoi));
$mailAuthors = '';
$mailDOI = '';
$mailElevatorPitch = '';
$siteUrl = Url::home(true);

if (count($authorsList)) {
    $authors = implode(",", array_map(function($author) {
        return ''.$author['name'].' at '.Url::toRoute($author['url'], true);
    }, $authorsList));
    $mailAuthors = 'by '.$authors;
}

if ($articleDoi) {
    $mailDOI = 'DOI: http://dx.doi.org/'.$articleDoi;
}

$mailText = "I think that you would be interested in the following quote from IZA World of Labor.\r\n\r\n";
$mailText .= "textReplace\r\n\r\n";
$mailText .= "From: $articleTitle - $articleUrl $mailAuthors\r\n\r\n";
$mailText .= $mailDOI."\r\n\r\n";
$mailText = htmlentities(rawurlencode($mailText));
echo 'mailto:?subject='.$mailSubject.'&body='.$mailText;
?>
