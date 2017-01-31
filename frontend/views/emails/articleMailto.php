<?php
    $articleDoiFixed = preg_replace("/ {2,}/"," ",$articleDoi);
    $articleElevatorPitchFixed =  preg_replace("/ {2,}/"," ",$articleElevatorPitch);
    $mailElevatorPitch = '';
    $mailDOI = '';
    $articleAuthors = implode(",", $articleAuthors);
    $mailAuthors = '';
    $articleAuthorsFixed = preg_replace("/ {2,}/"," ",$articleAuthors);
    $articleTitle = preg_replace("/ {2,}/"," ",$articleTitle);

    if ($articleDoi) {
        $mailDOI = "DOI: http://dx.doi.org/".$articleDoi;
    }

    if ($articleElevatorPitch) {
        $mailElevatorPitch = 'Elevator Pitch: '.$articleElevatorPitchFixed;
    }

    if ($articleAuthors) {
        $mailAuthors = ' by '.$articleAuthorsFixed;
    }

    $mailSubject = 'Article from IZA World of Labor';
    $mailThink = 'I think you that you would be interested in the following article from IZA World of Labor';
    $mailAuthor = '"'.$articleTitle.'"'.$mailAuthors;
    $mailBody = 'mailto:?Content-type=text/html?subject='.$mailSubject.'&body='.$mailThink.'%0D%0A%0D%0A'.$mailAuthor.'%0D%0A%0D%0A'.$mailDOI.'%0D%0A%0D%0A'.$mailElevatorPitch;
    echo $mailBody;
?>
