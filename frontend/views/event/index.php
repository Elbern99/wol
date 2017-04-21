<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Events';
if ($isArchive) {
    $this->params['breadcrumbs'][] = ['label' => Html::encode('Events'), 'url' => Url::to(['/event/index'])];
    $this->params['breadcrumbs'][] = 'Events Archive';
} else {
    $this->params['breadcrumbs'][] = 'Events';
}
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
?>

<div class="header-background" style="background-image: url('../images/temp/img-04.jpg');"></div>

<div class="container event-list-page">

    <div class="article-head-holder hide-mobile">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <?php if ($isArchive) : ?>
                <h1>Events archive</h1>
            <?php else : ?>
                <h1>Upcoming Events</h1>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <p class="small-paragraph">We have selected events that we think are relevant to policymakers and/or that involve the IZA World of
                Labor. Also included are a selection of IZA events (further IZA events can be found <a target="_blank" href="http://legacy.iza.org/en/webcontent/events/index">here</a>)</p>

            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li class="active"><a href="" class="js-widget">Events</a></li>
                    <li><a href="" class="js-widget">Events archive</a></li>
                    <?php if ($isArchive && !empty($upcomingEvents)) : ?>
                    <li><a href="" class="js-widget">Upcoming events</a></li>
                    <?php endif; ?>
                </ul>
                <h1 class="hide-desktop">Events</h1>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item active">
                        <?php if (!empty($eventGroups)): ?>
                        <ul class="events-list">
                            <?php foreach ($eventGroups as $group): ?>
                               <li class="event-item only-mibile-accordion-item">
                                <div class="title"><?= $group['heading']; ?></div>
                                <div class="text">
                                     <?php foreach ($group['events'] as $event): ?>
                                     
                                     <div class="sub-event-item">
                                         <div class="date-part">
                                             <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event->url_key])]) ?>
                                                <?= $event->date_from->format('F d'); ?><span class="year">, <?= $event->date_from->format('Y'); ?></span>
                                             <?= Html::endTag('a'); ?>
                                             <?php if ($event->date_from->format('F d, Y') != $event->date_to->format('F d, Y')) : ?>
                                             <span class="to-word">TO</span>
                                             <div class="last">
                                                <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event->url_key])]) ?>
                                                    <?= $event->date_to->format('F d'); ?><span class="year">, <?= $event->date_to->format('Y'); ?></span>
                                                <?= Html::endTag('a'); ?>
                                             </div>
                                             <?php endif; ?>
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
                        <?php else : ?>
                            No results found.
                        <?php endif; ?>
                    </div>
                    <div class="tab-item blue js-tab-hidden">
                        <ul class="articles-filter-list date-list blue-list">
                            <?php foreach ($eventsTree as $key => $value) : ?>
                            <li class="item has-drop <?php if($value['isActive']) echo 'open'; ?>">
                                <div class="icon-arrow"></div>
                                <?= Html::a($key, ['event/index', 'year' => $key]) ?>
                                <ul class="submenu">
                                    <?php foreach ($value['months'] as $month): ?>

                                        <li class="item if($month['isActive']) echo 'open'; ?>">
                                            <?php $monthYear = date("F", mktime(0, 0, 0, $month['num'], 10)) . ' ' . $key; ?>
                                            <?= Html::a($monthYear, ['event/index', 'year' => $key, 'month' => $month['num']]) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php if ($isArchive && !empty($upcomingEvents)) : ?>
                    <div class="tab-item js-tab-hidden expand-more">
                        <ul class="sidebar-news-list">
                            <?php foreach($upcomingEvents as $event) : ?>
                                <li>
                                    <h3>
                                        <?= Html::a($event->title, ['/event/view', 'slug' => $event->url_key]); ?>
                                    </h3>
                                    <div class="writer">
                                        <?php if ($event->date_from->format('F d, Y') != $event->date_to->format('F d, Y')) : ?>
                                            <?= $event->date_from->format('F d, Y'); ?> -
                                            <?= $event->date_to->format('F d, Y'); ?>
                                        <?php else : ?>
                                            <?= $event->date_from->format('F d, Y'); ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (count($upcomingEvents) > Yii::$app->params['upcoming_events_sidebar_limit']): ?>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-event-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">events archive</a>
                        <div class="text is-open">
                            <ul class="articles-filter-list date-list">
                                <?php foreach ($eventsTree as $key => $value) : ?>
                                <li class="item has-drop <?php if($value['isActive']) echo 'open'; ?>">
                                    <div class="icon-arrow"></div>
                                    <strong><?= Html::a($key, ['event/index', 'year' => $key]) ?></strong>
                                    <ul class="submenu">
                                        <?php foreach ($value['months'] as $month): ?>
                                            <li class="item <?php if($month['isActive']) echo 'open'; ?>">
                                                <?php $monthYear = date("F", mktime(0, 0, 0, $month['num'], 10)) . ' ' . $key; ?>
                                                <?= Html::a($monthYear, ['event/index', 'year' => $key, 'month' => $month['num']]) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <?php if ($isArchive && !empty($upcomingEvents)) : ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Upcoming Events</a>
                        <div class="text">
                            <ul class="sidebar-news-list">
                                <?php foreach($upcomingEvents as $event) : ?>
                                    <li>
                                        <h3>
                                            <?= Html::a($event->title, ['/event/view', 'slug' => $event->url_key]); ?>
                                        </h3>
                                        <div class="writer">
                                            <?php if ($event->date_from->format('F d, Y') != $event->date_to->format('F d, Y')) : ?>
                                                <?= $event->date_from->format('F d, Y'); ?> -
                                                <?= $event->date_to->format('F d, Y'); ?>
                                            <?php else : ?>
                                                <?= $event->date_from->format('F d, Y'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($upcomingEvents) > Yii::$app->params['upcoming_events_sidebar_limit']): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (count($widgets)): ?>
            <div class="sidebar-widget">
                <div class="podcast-list">
                    <?php foreach ($widgets as $widget): ?>
                        <?= $widget['text'] ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</div>