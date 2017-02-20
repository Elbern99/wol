<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
    $this->registerJsFile('/js/pages/event.js', ['depends' => ['yii\web\YiiAsset']]);
    
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = ['label' => Html::encode('Events'), 'url' => Url::to(['/event/index'])];
    $this->params['breadcrumbs'][] = $this->title;
    
    if ($category) {
        $this->registerMetaTag([
        'name' => 'keywords',
        'content' => Html::encode($category->meta_keywords)
        ]);
        $this->registerMetaTag([
            'name' => 'title',
            'content' => Html::encode($category->meta_title)
        ]);
    }

    $mailMap = Yii::$app->view->renderFile('@app/views/emails/defMailto.php', [
        'articleTitle' => $model->title,
        'articleUrl' => Url::to(['/event/view', 'slug' => $model->url_key], true),
        'typeContent' => 'event'
    ]);
?>

<?php if (($imagePath = $model->getImagePath()) != null) : ?>
   <?= Html::tag('div', '', [
       'class' => 'header-background',
       'style' => "background-image: url('../uploads/events/$model->image_link');"
   ]);
?>

<?php endif; ?>

<div class="container event-page">

    <div class="article-head hide-mobile">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <h1><?= $model->title; ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">

            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="" class="js-widget">Latest events</a></li>
                    <li><a href="" class="js-widget">Events archive</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item js-tab-hidden">
                        <ul class="events-list">
                            <?php foreach ($eventGroups as $group) : ?>
                            <li class="event-item only-mibile-accordion-item">
                                <div class="title"><?= $group['heading']; ?></div>
                                <div class="text">
                                    <?php foreach ($group['events'] as $event): ?>
                                    <div class="sub-event-item">
                                        <div class="date-part">
                                             <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event->url_key])]) ?>
                                                <?= $event->date_from->format('F d'); ?><span class="year">, <?= $event->date_from->format('Y'); ?></span>
                                             <?= Html::endTag('a'); ?>
                                             <span class="to-word">TO</span>
                                             <div class="last">
                                                <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event->url_key])]) ?>
                                                    <?= $event->date_to->format('F d'); ?><span class="year">, <?= $event->date_to->format('Y'); ?></span>
                                                <?= Html::endTag('a'); ?>
                                             </div>
                                         </div>
                                        <div class="desc">
                                             <h2><?= Html::a($event->title, ['/event/view', 'slug' => $event->url_key]); ?></h2>
                                             <div class="name"><?= $event->location; ?></div>
                                             <p><?= $event->short_description; ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            
                        </ul>
                    </div>
                    <div class="tab-item blue js-tab-hidden">
                        <ul class="articles-filter-list date-list blue-list">
                            <?php foreach ($eventsTree as $key => $value) : ?>
                            <li class="item has-drop">
                                <div class="icon-arrow"></div>
                                <?= Html::a($key, ['event/index', 'year' => $key]); ?>
                                <ul class="submenu">
                                    <?php foreach ($value as $item) : ?>
                                    <li class="item">
                                        <?php $monthYear = date("F", mktime(0, 0, 0, $item, 10)) . ' ' . $key; ?>
                                        <?= Html::a($monthYear, ['event/index', 'year' => $key, 'month' => $item]) ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="clone-title hide-desktop"><?= $model->title; ?></div>
            <div class="event-date">
                <?php if ($model->date_from->format('F d, Y') != $model->date_to->format('F d, Y')) : ?>
                    <?= $model->date_from->format('F d, Y'); ?> - 
                    <?= $model->date_to->format('F d, Y'); ?>
                <?php else : ?>
                    <?= $model->date_from->format('F d, Y'); ?>
                <?php endif; ?>
            </div>

            <div class="event-buttons-top">
                <div>
                    <?= Html::a('Event site', $model->book_link, ['class' => 'btn-blue']); ?>
                </div>
                <?php if ($model->contact_link) : ?>
                <div>
                    <?= Html::a('Contact', 'mailto: ' . $model->contact_link, ['class' => 'btn-border-blue']); ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="event-text-holder">
                <div class="event-text">
                    <?= $model->body; ?>
                </div>
            </div>

            <a class="btn-gray-large align-center more-event" href="">read more</a>
            <?php if(!empty($otherEvents)) : ?>
            <div class="other-events-holder">
                <div class="other-events">
                    <!--<div class="widget-title medium">other events in <?= $model->date_from->format('F'); ?></div>-->
                    <div class="widget-title medium">upcoming events</div>
                    <ul class="other-events-list">
                        <?php foreach ($otherEvents as $event) : ?>
                        <li class="other-events-item">
                            <div class="date">
                                <?php 
                                    $dateFrom = $event->date_from->format('F d, Y');
                                    $dateTo =  $event->date_to->format('F d, Y'); 
                                    if ($dateFrom != $dateTo) {
                                        $eventDates = "$dateFrom - $dateTo";
                                    }
                                    else {
                                        $eventDates = $dateFrom;
                                    }
                                ?>
                                <?= Html::a($eventDates, ['/event/view', 'slug' => $event->url_key]); ?>
                            </div>
                            <h3>
                                <?= Html::a($event->title, ['/event/view', 'slug' => $event->url_key]); ?>
                            </h3>
                            <div class="country"><?= $event->location; ?></div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-buttons-holder">
                <div class="event-page-buttons">
                    <div>
                        <?= Html::a('Event site', $model->book_link, ['class' => 'btn-blue']); ?>
                    </div>
                    <?php if ($model->contact_link) : ?>
                    <div>
                        <?= Html::a('Contact organiser', 'mailto: ' . $model->contact_link, ['class' => 'btn-border-blue']); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="share-buttons">
                    <ul class="share-buttons-list">
                        <li class="share-facebook">
                            <!-- Sharingbutton Facebook -->
                            <a class="resp-sharing-button__link facebook-content" href="" target="_blank" aria-label="Facebook">
                                <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
                            </a>
                        </li>
                        <li class="share-twitter">
                            <!-- Sharingbutton Twitter -->
                            <a class="resp-sharing-button__link twitter-content" href="" target="_blank" aria-label="Twitter">
                                <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
                            </a>
                        </li>
                        <li class="share-ln">
                            <!-- Sharingbutton LinkedIn -->
                            <a class="resp-sharing-button__link linkedin-content" href="" target="_blank" aria-label="LinkedIn">
                                <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg></div>LinkedIn</div>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="sidebar-email-holder">
                    <a target="_blank" href="<?= $mailMap ?>" class="btn-border-gray-small with-icon-r">
                        <div class="inner">
                            <span class="icon-message"></span>
                            <span class="text">email</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-widget sidebar-widget-event-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">events archive</a>
                        <div class="text is-open">
                            <ul class="articles-filter-list date-list">
                                <?php foreach ($eventsTree as $key => $value) : ?>
                                <li class="item has-drop">
                                    <div class="icon-arrow"></div>
                                    <?= Html::a(Html::tag('strong', $key), ['event/index', 'year' => $key]); ?>
                                    <ul class="submenu">
                                        <?php foreach ($value as $item) : ?>
                                            <li class="item">
                                                <?php $monthYear = date("F", mktime(0, 0, 0, $item, 10)) . ' ' . $key; ?>
                                                <?= Html::a($monthYear, ['event/index', 'year' => $key, 'month' => $item]) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            <?php foreach ($widgets as $widget): ?>
                <?= $widget['text'] ?>
            <?php endforeach; ?>
        </aside>
    </div>
</div>