<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
                        <?= $form->field($model, 'items')->checkboxList($model->getSubjectItems(),[
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
                <label class="def-checkbox">
                    <input type="checkbox" name="">
                    <span class="label-text">I would like to recieve updates from IZA World of Labor</span>
                </label>
            </div>

            <div class="form-line">
                <label class="def-checkbox">
                    <input type="checkbox" name="">
                    <span class="label-text">I would like to recieve new article alerts in my areas of interest</span>
                </label>
            </div>

            <div class="form-line">
                <label class="def-checkbox">
                    <input type="checkbox" name="">
                    <span class="label-text">I would like to recieve updates from IZA</span>
                </label>
            </div>
             <?= Html::submitButton('Signup', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
             <?php ActiveForm::end(); ?>
        </div>


        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">newsletter archives</a>
                        <div class="text is-open">
                            <ul class="articles-filter-list date-list">
                                <li class="submenu">
                                    <li class="item open">
                                        <div class="icon-arrow"></div>
                                        <a href="/subject-areas/program-evaluation"><strong>2016</strong></a>
                                        <ul class="submenu">
                                            <li class="item">
                                                <div class="date">July 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">June 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">May 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">April 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">March 2016</div>
                                                <a href="">IZA WoL Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">February 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">January 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="item">
                                        <div class="icon-arrow"></div>
                                        <a href="/subject-areas/program-evaluation"><strong>2015</strong></a>
                                        <ul class="submenu">
                                            <li class="item">
                                                <div class="date">July 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">June 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="item">
                                        <div class="icon-arrow"></div>
                                        <a href="/subject-areas/program-evaluation"><strong>2014</strong></a>
                                        <ul class="submenu">
                                            <li class="item">
                                                <div class="date">July 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                            <li class="item">
                                                <div class="date">June 2016</div>
                                                <a href="">IZA World of Labor Newsletter</a>
                                            </li>
                                        </ul>
                                    </li>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>