<?php

$mailSubject = 'Thank you for sharing IZA World of Labor';
$articleTitle = preg_replace("/ {2,}/"," ",$articleTitle);
$articleAuthors = implode(",", $articleAuthors);
$mailAuthors = '';

if ($articleAuthors) {
    $mailAuthors = ' by '.$articleAuthorsFixed;
}

$mailThanks = 'Thank you for sharing '.$articleTitle.$mailAuthors.' Our aim is to bridge the gap between research and policy making and we thank you for helping us to achieve that.';
$bestWishes = 'Best wishes, %0D%0A%0D%0A The IZA World of Labor team %0D%0A%0D%0A wol.iza.org – supporting evidence-based policy making';
$socialButtons = 'twitter - http://twitter.com/IZAWorldofLabor,%0D%0A%0D%0Afacebook - http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452%0D%0A%0D%0Alinkedin - http://www.linkedin.com/groups?gid=6610789&mostPopular=&trk=tyah&trkInfo=tas%3AIZA%20wo%2Cidx%3A1-1-1';
$contactUs = 'Contact Us: %0D%0A%0D%0A wol@iza.org - IZA World of Labor, Forschungsinstitut 
zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.';
$receiving = 'You are receiving this email to XXX@XXX.com as you are opted in to IZA World of Labor updates. 
You can manage your IZA World of Labor contact details and preferences at wol.iza.org/account 
{or correct URL!} or unsubscribe {link} from all IZA World of Labor emails.';
//INFO
//Subject: Thank you for sharing IZA World of Labor
//Thank you for sharing [Content title by Author name (if available) linked with URL]. Our aim is to bridge the gap between research and policy making and we thank you for helping us to achieve that.
//Best wishes,
//The IZA World of Labor team
//wol.iza.org – supporting evidence-based policy making
//{set of social buttons – twitter, facebook, linkedin}
//You are receiving this email to XXX@XXX.com as you are opted in to IZA World of Labor updates. You can manage your IZA World of Labor contact details and preferences at wol.iza.org/account {or correct URL!} or unsubscribe {link} from all IZA World of Labor emails.
//Contact Us:
//wol@iza.org - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.
$mailBodyShare = 'mailto:?subject='.$mailSubject.'&body='.$mailThanks.'%0D%0A%0D%0A'.$bestWishes.'%0D%0A%0D%0A'.$socialButtons.'%0D%0A%0D%0A'.$receiving.'%0D%0A%0D%0A'.$contactUs;

echo $mailBodyShare;

?>
