<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\components\filters\NewsletterArchiveWidget;

$this->title = 'Subscribe to newsletter';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container subscribe-to-newsletter">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1><?= $this->title ?></h1>
        <p>Stay up to date with IZA World of Labor articles, news and commentary sent directly to your inbox.</p>
        <p class="small-paragraph">Fill in your details below to register for the IZA World of Labor newsletter</p>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <?php $form = ActiveForm::begin(); ?>
            <div class="grid">
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'first_name', ['options' => ['class' => 'form-item']]) ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'last_name', ['options' => ['class' => 'form-item']]) ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <div class="form-item">
                            <?= $form->field($model, 'email', ['options' => ['class' => 'form-item']]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="checkboxes">

                    <div class="grid-line one title-checkboxes">
                        <div class="grid-item form-item">
                            <div class="select-clear-all">
                                <span class="clear-all">Clear all</span>
                                <span class="select-all">Select all</span>
                                <div class="tooltip-dropdown dropdown left">
                                    <div class="icon-question tooltip"></div>
                                    <div class="tooltip-content drop-content">
                                        <div class="icon-close"></div>
                                        <strong>At vero eos et accusamus</strong>
                                        Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis volupt atibus maiores alias consequatur aut per ferendis recusan ciedae doloribus asperiores.
                                    </div>
                                </div>
                            </div>
                            <div class="label-holder">
                                <span class="label-text">areas of interest</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid-line four">
                        <?= $form->field($model, 'areas_interest')->checkboxList($model->getSubjectItems(),[
                            'item'=> function($index, $label, $name, $checked, $value) {
                                return '<div class="grid-item"><div class="form-item"><label class="custom-checkbox">'.
                                Html::checkbox($name, $checked, [
                                    'value' => $value,
                                ]).'<span class="label-text">'.$label.'</span></label></div></div>';
                            }
                        ])->label('');
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-line">
                <?= Html::activeCheckbox($model, 'iza_world', 
                    ['labelOptions' => ['class'=>'def-checkbox'],
                    'label' => '<span class="label-text">I would like to receive updates from IZA World of Labor</span>']
                ) ?>
            </div>

            <div class="form-line">
                <?= Html::activeCheckbox($model, 'interest', 
                    ['labelOptions' => ['class'=>'def-checkbox'],
                    'label' => '<span class="label-text">I would like to receive new article alerts in my areas of interest</span>']
                ) ?>
            </div>

            <div class="form-line">
                <?= Html::activeCheckbox($model, 'iza', 
                    ['labelOptions' => ['class'=>'def-checkbox'],
                    'label' => '<span class="label-text">I would like to receive updates from IZA</span>']
                ) ?>
            </div>
            
             <?= Html::submitButton('Signup', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
             <?php ActiveForm::end(); ?>
        </div>


        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">newsletter archives</a>
                        <div class="text">
                            <?= NewsletterArchiveWidget::widget(['data' => $newsletterArchive]); ?>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>

    </div>
</div>