/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    //添加字体
    config.font_names = ' 宋体/宋体;黑体/黑体;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;' + config.font_names;
    // 界面语言，默认为 'en' 
    config.language = 'zh-cn';
    //设置工具箱
    config.toolbar_Full = [
['Source', 'Preview','Cut', 'Copy', 'Paste', 'Print', 'Find', 'Replace', 'SelectAll', 'RemoveFormat'],
['Bold', 'Italic', 'Underline', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock','Image', 'Table', 'Smiley', 'SpecialChar', 'PageBreak', 'Font', 'FontSize','TextColor', 'BGColor']
    ];
    config.width = '900px';
    config.height = '400px';
    config.image_previewText = '图片预览';
    config.disableNativeSpellChecker = false;
    config.scayt_autoStartup = false;


    //以下是ckfinder的设置
    config.filebrowserBrowseUrl = '/Public/js/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/Public/js/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '/Public/js/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '/Public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=File';
    config.filebrowserImageUploadUrl = '/Public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/Public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

};
