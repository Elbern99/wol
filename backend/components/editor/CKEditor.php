<?php
/**
 * @copyright Copyright (c) 2013-2016 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace backend\components\editor;

use yii\helpers\Json;
use dosamigos\ckeditor\CKEditor as CKEditorOriginal;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use yii\helpers\Url;
/**
 * CKEditor renders a CKEditor js plugin for classic editing.
 * @see http://docs.ckeditor.com/
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\ckeditor
 */
class CKEditor extends CKEditorOriginal
{  
    /**
     * Registers CKEditor plugin
     * @codeCoverageIgnore
     */
    protected function registerPlugin()
    {
        $js = [];

        $view = $this->getView();

        CKEditorWidgetAsset::register($view);

        $id = $this->options['id'];

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';
        
        $basePath = Url::base();
        $js[] = "CKEDITOR.replace('$id', $options);";
        $js[] = "dosamigos.ckEditorWidget.registerOnChangeHandler('$id');";
        $js[] = "
            CKEDITOR.editor.prototype.getSelectedHtml = function() {
                var selection = this.getSelection();
                if( selection ){
                   var bookmarks = selection.createBookmarks(),
                      range = selection.getRanges()[ 0 ],
                      fragment = range.clone().cloneContents();
                   selection.selectBookmarks( bookmarks );
                   var retval = '',
                      childList = fragment.getChildren(),
                      childCount = childList.count();
                   for ( var i = 0; i < childCount; i++ ){
                      var child = childList.getItem( i );
                      retval += ( child.getOuterHtml?
                         child.getOuterHtml() : child.getText() );
                   }
                   return retval;
                }
            };
            
            CKEDITOR.plugins.add('h1_b', {
                icons: 'h1_b',
                init: function( editor ) {
                    editor.addCommand('h1_b',  {
                        exec: function (editor) {
                            var html = '<h1>'+editor.getSelectedHtml()+'</h1>';

                            var newElement = CKEDITOR.dom.element.createFromHtml(html, editor.document);
                            editor.insertElement(newElement);
                        }
                    });
    
                    editor.ui.addButton( 'h1_b', {
                        label: 'Insert H1',
                        command: 'h1_b',
                        toolbar: 'basicstyles',
                        icon: '{$basePath}/images/icons/h1.png',
                    });
                }
            }); 
            CKEDITOR.plugins.add('h2_b', {
                icons: 'h2_b',
                init: function( editor ) {
                    editor.addCommand('h2_b',  {
                        exec: function (editor) {
                            var html = '<h2>'+editor.getSelectedHtml()+'</h2>';

                            var newElement = CKEDITOR.dom.element.createFromHtml(html, editor.document);
                            editor.insertElement(newElement);
                        }
                    });
    
                    editor.ui.addButton( 'h2_b', {
                        label: 'Insert Abbreviation',
                        command: 'h2_b',
                        toolbar: 'basicstyles',
                        icon: '{$basePath}/images/icons/h2.png',
                    });
                }
            }); 
            CKEDITOR.plugins.add('h3_b', {
                icons: 'h3_b',
                init: function( editor ) {
                    editor.addCommand('h3_b',  {
                        exec: function (editor) {
                            var html = '<h3>'+editor.getSelectedHtml()+'</h3>';

                            var newElement = CKEDITOR.dom.element.createFromHtml(html, editor.document);
                            editor.insertElement(newElement);
                        }
                    });
    
                    editor.ui.addButton( 'h3_b', {
                        label: 'Insert Abbreviation',
                        command: 'h3_b',
                        toolbar: 'basicstyles',
                        icon: '{$basePath}/images/icons/h3.png',
                    });
                }
            }); 
            CKEDITOR.config.allowedContent = true;
        ";

        if (isset($this->clientOptions['filebrowserUploadUrl']) || isset($this->clientOptions['filebrowserImageUploadUrl'])) {
            $js[] = "dosamigos.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
    }
}
