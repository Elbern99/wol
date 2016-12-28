<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
    $this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);
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

$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: ' . $model->title .
    '\n\n View the article: '. Url::to(['/event/view', 'slug' => $model->url_key], true) .'\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';
?>

<?php if (($imagePath = $model->getImagePath()) != null) : ?>
   <?= Html::tag('div', '', [
       'class' => 'header-background',
       'style' => "background-image: url('../uploads/events/$model->image_link');"
   ]);
?>

<?php endif; ?>

<div class="container event-page">

    <div class="article-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <h1 class="hide-mobile"><?= $model->title; ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">

            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="">Latest events</a></li>
                    <li><a href="">Events archive</a></li>
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
                    <div class="tab-item js-tab-hidden">
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

            <h1 class="hide-desktop"><?= $model->title; ?></h1>
            <div class="event-date">
                <?= $model->date_from->format('F d, Y'); ?> - 
                <?= $model->date_to->format('F d, Y'); ?>
            </div>

            <div class="event-buttons-top">
                <div>
                    <?= Html::a('Book tickets', $model->book_link, ['class' => 'btn-blue']); ?>
                </div>
                <div>
                    <?= Html::a('Contact', $model->contact_link, ['class' => 'btn-border-blue']); ?>
                </div>
            </div>

            <div class="event-text-holder">
                <div class="event-text">
                    <p><?= $model->body; ?></p>
                </div>
            </div>

            <a class="btn-gray-large align-center more-event" href="">read more</a>
            <?php if(!empty($otherEvents)) : ?>
            <div class="other-events-holder">
                <div class="other-events">
                    <div class="widget-title medium">other events in <?= $model->date_from->format('F'); ?></div>
                    <ul class="other-events-list">
                        <?php foreach ($otherEvents as $event) : ?>
                        <li class="other-events-item">
                            <div class="date">
                                <?php 
                                    $dateFrom = $event->date_from->format('F d, Y');
                                    $dateTo =  $event->date_to->format('F d, Y'); 
                                    $eventDates = "$dateFrom - $dateTo";
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
                        <?= Html::a('Book your ticket', $model->book_link, ['class' => 'btn-blue']); ?>
                    </div>
                    <div>
                        <?= Html::a('Contact organiser', $model->contact_link, ['class' => 'btn-border-blue']); ?>
                    </div>
                </div>

                <ul class="share-buttons-list">
                    <li class="share-facebook">
                        <div id="fb-root"></div>
                        <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
                    </li>
                    <li class="share-twitter">
                        <a class="twitter-share-button" href="https://twitter.com/intent/tweet">Tweet</a>
                    </li>
                    <li class="share-ln">
                        <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                        <script type="IN/Share"></script>
                    </li>
                </ul>

                <div class="sidebar-email-holder">
                    <a href="mailto:?subject=<?= Html::encode('Article from IZA World of Labor') ?>&body=<?= Html::encode($mailBody) ?>" class="btn-border-gray-small with-icon-r">
                        <div class="inner">
                            <span class="icon-message"></span>
                            <span class="text">email</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-widget sidebar-widget-articles-filter">
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

            <div class="sidebar-widget">
                <div class="podcast-list">
                    <?php foreach ($widgets as $widget): ?>
                        <?= $widget['text'] ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
</div>