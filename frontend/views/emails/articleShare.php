<?php
use yii\helpers\Url;

$mailSubject = 'Thank you for sharing IZA World of Labor';
$articleTitle = trim($articleTitle);
$mailAuthors = '';
$siteUrl = Url::home(true);
if (count($authorsList)) {
    $authors = implode(",", array_map(function($author) {
        return 'name - '.$author['name'].' at '.$author['url'];
    }, $authorsList));
    $mailAuthors = ' by '.$authors;
}

$mailText = "Thank you for sharing ".$articleTitle.' - '.$articleUrl."\n".$mailAuthors. "Our aim is to bridge the gap between research and policy making and we thank you for helping us to achieve that. \r\n";
$mailText .= "Best wishes, \r\n The IZA World of Labor team \r\n $siteUrl – supporting evidence-based policy making \r\n";
$mailText .= 'Twitter - http://twitter.com/IZAWorldofLabor,%0D%0AFacebook - http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452,%0D%0ALinkedin - https://www.linkedin.com/groups/6610789/profile'. "\r\n";
$mailText .= "Contact Us: %0D%0Awol@iza.org - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany. \r\n";
$mailText .= "You can manage your IZA World of Labor contact details and preferences at '.$siteUrl.'account from all IZA World of Labor emails. \r\n";
$mailText = htmlentities(urlencode($mailText));
echo 'mailto:?subject='.$mailSubject.'&body='.$mailText;
?>
