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
?>

<div class="header-background" style="background-image: url('../images/temp/img-05.jpg');"></div>

<div class="container event-page">

    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <h1><?= $model->title; ?></h1>
        </div>
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
                            <li class="event-item only-mibile-accordion-item">
                                <div class="title">SEPTEMBER 2016</div>
                                <div class="text">
                                    <div class="sub-event-item">
                                        <div class="date-part">
                                            <a href="">September 1<span class="year">, 2016</span></a><span class="to-word">TO</span>
                                            <div class="last"><a href="">September 2<span class="year">, 2016</span></a></div>
                                        </div>
                                        <div class="desc">
                                            <h2><a href="">4th IZA Conference on Labor Market Effects of Environmental Policies</a></h2>
                                            <div class="name">Bonn, Germany</div>
                                            <p>Many industrialized countries have implemented environmental policies in order to reduce emissions that are harmful to the environment and global climate. While the benefits of such policies accrue.</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="event-item only-mibile-accordion-item">
                                <div class="title">JULY 2016</div>
                                <div class="text">
                                    <div class="sub-event-item">
                                        <div class="date-part">
                                            <a href="">July 27<span class="year">, 2016</span></a><span class="to-word">TO</span>
                                            <div class="last"><a href="">July 31<span class="year">, 2016</span></a></div>
                                        </div>
                                        <div class="desc">
                                            <h2><a href="">15th IZA/SOLE Transatlantic Meeting of Labor Economists</a></h2>
                                            <div class="name">Buch/Amersee, Germany</div>
                                            <p>This year's meeting will be held in Bavaria, Germany. The deadline for submission of abstracts is February 29, 2016.</p>
                                        </div>
                                    </div>
                                    <div class="sub-event-item">
                                        <div class="date-part">
                                            <a href="">July 21<span class="year">, 2016</span></a> <span class="to-word">TO</span>
                                            <div class="last"><a href="">July 22<span class="year">, 2016</span></a></div>
                                        </div>
                                        <div class="desc">
                                            <h2><a href="">London Conference on Employer Engagement in Education and Training 2016</a></h2>
                                            <div class="name">BIS Conference Center, London</div>
                                            <p>Conference registration open and call for papers now open. What difference does it make when employers work with education and training providers?  How can employer engagement best be delivered?</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
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
                    <a href="" class="btn-border-gray-small with-icon-r">
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
</div>а