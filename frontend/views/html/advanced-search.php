<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$this->registerJsFile('/js/plugins/jqueryui.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/tag-it.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile('/css/plugins/jquery.tagit.css');
$this->registerCssFile('/css/plugins/tagit.ui-zendesk.css');
?>

<div class="container search-site">
    <div class="breadcrumbs">
        <ul class="breadcrumbs-list">
            <li><a href="">home</a></li>
            <li>Frequently asked questions and help</li>
        </ul>
    </div>
    <h1>Search the site</h1>

    <form action="#">
        <div class="search">
            <span class="icon-search"></span>
            <div class="search-holder">
                <input type="search" name="search" placeholder="Search for " class="form-control-decor">
            </div>
        </div>

        <div class="content-types">
            <h3>in these content types</h3>
            <div class="grid-line one title-checkboxes">
                <div class="grid-item form-item">
                    <div class="select-clear-all">
                        <span class="clear-all">Clear all</span>
                        <span class="select-all">Select all</span>
                    </div>
                </div>
            </div>
            <div class="grid checkboxes">
                <div class="grid-line three">
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Articles</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Biography</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Key topics</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">News</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Opinions</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Events</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">Videos</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">IZA policy paper</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="types">
                                <span class="label-text">IZA discussionpaper</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="finding-results">
            <h3>finding results that have</h3>
            
            <div class="my-tags-holder">
                <div class="label-holder">
                    <div class="label-text">all of these words</div>
                </div>
                <input name="tags" class="my-single-field" disabled="true">
                <ul class="my-tags-list all-words-tags-list"></ul>
            </div>

            <div class="form-line">
                <div class="label-holder">
                    <label for="this-exact-phrase">this exact phrase</label>
                </div>
                <div class="form-control-holder">
                    <input type="text" class="form-control" placeholder="Enter phrase without quotes" id="this-exact-phrase">
                </div>
            </div>

            <div class="my-tags-holder">
                <div class="label-holder">
                    <div class="label-text">one or more of these words</div>
                </div>
                <input name="tags" class="my-single-field" disabled="true">
                <ul class="my-tags-list one-or-more-my-tags-list"></ul>
            </div>
        </div>

        <div class="excluding-results">
            <h3>excluding results that have</h3>
            <div class="my-tags-holder">
                <div class="label-holder">
                    <div class="label-text">any of these words</div>
                </div>
                <input name="tags" class="my-single-field" disabled="true">
                <ul class="my-tags-list one-or-more-my-tags-list"></ul>
            </div>
        </div>
        <button class="btn-blue-large" type="submit">search</button>
    </form>
</div>