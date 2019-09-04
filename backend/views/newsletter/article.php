<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\NewsletterLogs;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Newsletter;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Article Newsletter Log');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row content">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['id' => 'article_newsletter_logs_table'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'contentOptions' => ['style'=>'vertical-align: middle;']],
                    [
                        'attribute' => 'article.title',
                        'label' => Yii::t('app', 'Article name'),
                        'format' => 'raw',
                        'contentOptions' => ['style'=>'vertical-align: middle;'],
                        'value' => function (NewsletterLogs $log) {
                            return Html::a(
                                $log->article->title,
                                Url::to(['iza/article-view', 'id' => $log->article->id])
                            );
                        }
                    ],
                    [
                        'attribute' => 'subject',
                        'contentOptions' => ['style'=>'vertical-align: middle;'],
                        'value' => function (NewsletterLogs $log) {
                            return $log->subject;
                        }
                    ],
                    [
                        'attribute' => 'progress',
                        'format' => 'raw',
                        'contentOptions' => ['style'=>'vertical-align: middle;'],
                        'value' => function (NewsletterLogs $log) {
                            switch ($log->status) {
                                case NewsletterLogs::STATUS_IN_PROGRESS:
                                    $textColor = 'info';
                                    break;
                                case NewsletterLogs::STATUS_SUCCESS:
                                    $textColor = 'success';
                                    break;
                                case NewsletterLogs::STATUS_ERROR:
                                    $textColor = 'danger';
                                    break;
                                case NewsletterLogs::STATUS_WARNING:
                                    $textColor = 'warning';
                                    break;
                                default:
                                    $textColor = 'secondary';
                            }

                            return Html::tag('div', Html::tag('div', $log->progress . '% ' . NewsletterLogs::getStatuses()[$log->status], [
                                'class' => 'progress-bar progress-bar-' . $textColor . ' progress-bar-striped',
                                'role' => 'progressbar',
                                'aria-valuenow' => $log->progress,
                                'aria-valuemin' => '0',
                                'valuemax' => '100',
                                'style' => 'width: ' . $log->progress . '%;'
                            ]), [
                                'class' => 'progress'
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'qty',
                        'contentOptions' => ['style'=>'vertical-align: middle; text-align: center;'],
                        'value' => function (NewsletterLogs $log) {
                            return $log->qty;
                        }
                    ],
                    [
                        'attribute' => 'subscribers',
                        'label' => Yii::t('app', 'Subscribers emails (First 5)'),
                        'format' => 'raw',
                        'contentOptions' => ['style'=>'vertical-align: middle;'],
                        'value' => function (NewsletterLogs $log) {
                            if (!$log->subscribers) {
                                return Html::tag(
                                    'span',
                                    Yii::t('app', 'Nobody got emails'),
                                    ['class' => 'text-danger']
                                );
                            }

                            $subscribers = $log->subscribers;
                            if (count($log->subscribers) > 5) {
                                $subscribers = array_slice($log->subscribers, 0, 5);
                            }
                            $subscribers = ArrayHelper::getColumn($subscribers, function (Newsletter $subscriber) {
                                return Html::a($subscriber->email, 'mailto:' . $subscriber->email);
                            });
                            $subscribers[] = Html::a(
                                    Yii::t('app', 'See detailed information...'),
                                    Url::to(['newsletter/subscribers', 'logs_id' => $log->id], true),
                                    ['class' => 'text-warning']
                            );

                            return Html::ul($subscribers, ['encode' => false, 'style' => 'padding: 0; list-style:none;']);
                        }
                    ],
                    [
                        'attribute' => 'error_text',
                        'format' => 'raw',
                        'contentOptions' => ['style'=>'vertical-align: middle; text-align: center;'],
                        'value' => function (NewsletterLogs $log) {
                            if ($error = StringHelper::byteSubstr($log->error_text, 0, 150)) {
                                return Html::tag(
                                    'span',
                                    strlen($log->error_text) > 170 ? $error . '...' : $error,
                                    ['class' => 'text-danger']);
                            }
                            return Html::tag(
                                'div',
                                '',
                                ['class' => 'text-success glyphicon glyphicon-ok']
                            ) . Html::tag('div', 'No errors', ['class' => 'text-success']);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'contentOptions' => ['style'=>'vertical-align: middle; text-align: center;'],
                        'value' => function (NewsletterLogs $log) {
                            switch ($log->status) {
                                case NewsletterLogs::STATUS_IN_PROGRESS:
                                    $textColor = 'info';
                                    break;
                                case NewsletterLogs::STATUS_SUCCESS:
                                    $textColor = 'success';
                                    break;
                                case NewsletterLogs::STATUS_ERROR:
                                    $textColor = 'danger';
                                    break;
                                case NewsletterLogs::STATUS_WARNING:
                                    $textColor = 'warning';
                                    break;
                                default:
                                    $textColor = 'secondary';
                            }
                            return Html::tag(
                                'span',
                                NewsletterLogs::getStatuses()[$log->status],
                                ['class' => 'text-' . $textColor]
                            );
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                        'contentOptions' => ['style'=>'vertical-align: middle; text-align: center;']
                    ],
                ]
            ]); ?>
        </div>
    </div>
</div>
