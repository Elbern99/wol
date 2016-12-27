<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;

//$this->registerMetaTag([
//    'name' => 'keywords',
//    'content' => Html::encode($category->meta_keywords)
//]);
//$this->registerMetaTag([
//    'name' => 'title',
//    'content' => Html::encode($category->meta_title)
//]);

?>
<div class="header-background" style="background-image: url('../images/temp/img-04.jpg');"></div>

<div class="container event-list-page">

    <div class="article-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <h1><?= $this->title; ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <p class="small-paragraph">We have selected events that we think are relevant to policymakers and/or that involve the IZA World of
                Labor. Also included are a selection of IZA events (further IZA events can be found <a href="">here</a>)</p>

            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li class="active"><a href="">Latest events</a></li>
                    <li><a href="">Events archive</a></li>
                </ul>
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
                                             <span class="date-holder">
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
                        <?php else : ?>
                            No results found.
                        <?php endif; ?>
                    </div>
                    <div class="tab-item js-tab-hidden">
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
                </div>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
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
                </ul>
            </div>

            <div class="sidebar-widget">
                <div class="podcast-list">
                    <a href="" class="podcast-item">
                        <div class="head">
                            <div class="widget-title">podcast: brexit debate</div>
                            <div class="img">
                                <img src="/images/temp/podcasts/01-img.jpg" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="" class="podcast-item">
                        <div class="head">
                            <div class="widget-title">Focus on: Education and labor policy</div>
                            <div class="img">
                                <img src="/images/temp/podcasts/02-img.jpg" alt="">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>