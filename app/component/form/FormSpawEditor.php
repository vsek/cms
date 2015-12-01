<?php
namespace App\Form;

use \Nette\Utils\Html;

/**
 * Description of SpawEditor
 *
 * @author Vsek
 */
class FormSpawEditor extends \Nette\Forms\Controls\TextArea{

    private $type;
    
    /**
    * @param  string  control name
    * @param  string  label
    * @param  int  width of the control
    * @param  int  height of the control in text lines
    */
    public function __construct($label = NULL, $cols = NULL, $rows = NULL, $type = 'all')
    {
       parent::__construct($label);
       $this->control->setName('textarea');
       $this->control->cols = $cols;
       $this->control->rows = $rows;
       $this->value = '';
       $this->type = $type;
    }
    
    /**
     * Generates control"s HTML element.
     * @return Html
     */
    public function getControl()
    {
        $container = Html::el();
        $container->add(parent::getControl()->style("width: 100%;"));
        $script = Html::el();
        if($this->type == 'min'){
            $script->setHtml('<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                <script type="text/javascript">
                CKEDITOR.replace( "' . $this->getHtmlId() . '",
                    {
                        toolbar : 
                            [ 
                                { name: "clipboard", items : [ "Cut","Copy","PasteText","-","Undo","Redo" ] },
                                { name: "basicstyles", items : [ "Bold","Italic","-","RemoveFormat" ] } ,
                                { name: "document", items : [ "Source" ] },
                            ],
                        filebrowserBrowseUrl :      "/ckeditor/filemanager/browser/default/browser.html?Connector=/ckeditor/filemanager/connectors/php/connector.php",
                        filebrowserImageBrowseUrl : "/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=/ckeditor/filemanager/connectors/php/connector.php",
                        filebrowserFlashBrowseUrl : "/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=/ckeditor/filemanager/connectors/php/connector.php"
                    });
            </script>
            ');
        }else{
            $script->setHtml('<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                <script type="text/javascript">
                CKEDITOR.replace( "' . $this->getHtmlId() . '",
                    {
                        toolbar : 
                            [ 
                                { name: "clipboard", items : [ "Cut","Copy","PasteText","-","Undo","Redo" ] },
                                { name: "basicstyles", items : [ "Bold","Italic","Strike","Subscript","Superscript","-","RemoveFormat" ] } ,
                                { name: "insert", items : [ "Image","Youtube","Table","HorizontalRule" ] },
                                { name: "styles", items : [ "Styles","Format","Font","FontSize","-","JustifyLeft","JustifyCenter","JustifyRight","JustifyRight" ] },
                                { name: "paragraph", items : [ "NumberedList","BulletedList","-","Outdent","Indent","-","Blockquote" ] },
                                { name: "links", items : [ "Link","Unlink","Anchor" ] },
                                { name: "colors",      items : [ "TextColor","BGColor" ] },
                                { name: "document", items : [ "Source" ] },
                            ],
                        filebrowserBrowseUrl :      "/ckeditor/filemanager/browser/default/browser.html?Connector=/ckeditor/filemanager/connectors/php/connector.php",
                        filebrowserImageBrowseUrl : "/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=/ckeditor/filemanager/connectors/php/connector.php",
                        filebrowserFlashBrowseUrl : "/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=/ckeditor/filemanager/connectors/php/connector.php",
                        extraAllowedContent: "a span"
                    });
            </script>
            ');
        }
        $container->add($script);

        return $container;
        
    }
    
    /*public function getValue() {
        return iconv('WINDOWS-1250', 'UTF-8', strtr(iconv('UTF-8', 'WINDOWS-1250', parent::getValue()), array_flip($this->get_html_translation_table_CP1252())));
    }*/
    
    function get_html_translation_table_CP1252() {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;';    // Dagger
        $trans[chr(135)] = '&Dagger;';    // Double Dagger
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;';    // Per Mille Sign
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;    ';    // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';    // Bullet
        $trans[chr(150)] = '&ndash;';    // En Dash
        $trans[chr(151)] = '&mdash;';    // Em Dash
        $trans[chr(152)] = '&tilde;';    // Small Tilde
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        ksort($trans);
        return $trans;
    }
}