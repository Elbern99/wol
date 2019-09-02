<?php

use backend\helpers\AdminFunctionHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\grid\GridView;
use common\models\NewsletterLogs;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Newsletter;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Article Newsletter');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row content">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'article.title',
                        'format' => 'raw',
                        'value' => function (NewsletterLogs $log) {
                            return Html::a(
                                $log->article->title,
                                Url::to(['iza/article-view', 'id' => $log->article->id])
                            );
                        }
                    ],
                    'subject',
                    [
                        'attribute' => 'progress',
                        'format' => 'raw',
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
                    'qty',
                    [
                        'attribute' => 'subscribers',
                        'format' => 'raw',
                        'value' => function (NewsletterLogs $log) {
                            if (!$log->subscribers) {
                                return Html::tag(
                                    'span',
                                    Yii::t('app', 'Nobody got emails'),
                                    ['class' => 'text-danger']
                                );
                            }
                            return Html::ul(ArrayHelper::getColumn($log->subscribers, function (Newsletter $subscriber) {
                                return Html::a($subscriber->email, 'mailto:' . $subscriber->email);
                            }), ['encode' => false]);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
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
                ]
            ]); ?>
        </div>
    </div>
</div>
