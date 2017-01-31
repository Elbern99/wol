<?php

    $mailSubject = 'Thank you for sharing IZA World of Labor';
    $articleTitle = preg_replace("/ {2,}/"," ",$articleTitle);
    $articleAuthors = implode(",", $articleAuthors);
    $articleAuthorsFixed = preg_replace("/ {2,}/"," ",$articleAuthors);
    $mailAuthors = '';

    if ($articleAuthors) {
        $mailAuthors = ' by '.$articleAuthorsFixed;
    }

    $mailThanks = 'Thank you for sharing "'.$articleTitle.' - '.$siteUrl.$articleUrl.' "'.$mailAuthors.'. Our aim is to bridge the gap between research and policy making and we thank you for helping us to achieve that.';
    $bestWishes = 'Best wishes, %0D%0A%0D%0AThe IZA World of Labor team %0D%0A'.$siteUrl.' – supporting evidence-based policy making';
    $socialButtons = 'Twitter - http://twitter.com/IZAWorldofLabor,%0D%0AFacebook - http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452,%0D%0ALinkedin - https://www.linkedin.com/groups/6610789/profile';
    $contactUs = 'Contact Us: %0D%0Awol@iza.org - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.';
    $receiving = 'You are receiving this email to XXX@XXX.com as you are opted in to IZA World of Labor updates. You can manage your IZA World of Labor contact details and preferences at '.$siteUrl.'account from all IZA World of Labor emails.';
    $mailBodyShare = 'mailto:?subject='.$mailSubject.'&body='.$mailThanks.'%0D%0A%0D%0A'.$bestWishes.'%0D%0A'.$socialButtons.'%0D%0A%0D%0A'.$receiving.'%0D%0A%0D%0A'.$contactUs;

    echo $mailBodyShare;

?>
