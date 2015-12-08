<?php

namespace App;

/**
 * Description of MacroSet
 *
 * @author Vsek
 */
class MacroSet extends \Latte\Macros\MacroSet{
    public static function install(\Latte\Compiler $compiler){
        $me = new static($compiler);
        $me->addMacro('src', NULL, NULL, function(\Latte\MacroNode $node, \Latte\PhpWriter $writer) use ($me) {
                return ' ?> src="<?php ' . $me->src($node, $writer) . ' ?>"<?php ';
        });
        return $me;
    }
    
    public function src(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->using($node, $this->getCompiler())
            ->write('echo %escape(\App\MacroSet::getSrc($_presenter, %node.word, %node.array?))');
    }
    
    public static function getSrc(FrontModule\Presenters\BasePresenter $presenter, $image, $param){
        $width = $height = null;
        $sharpen = false;
        $exact = true;
        if(isset($param[0])){
            $width = $param[0];
        }
        if(isset($param[1])){
            $height = $param[1];
        }
        if(isset($param[2])){
            $sharpen = $param[2];
        }
        if(isset($param[3])){
            $exact = $param[3];
        }
        $previewName = explode('.', $image);
        $postfix = $previewName[count($previewName) - 1];
        unset($previewName[count($previewName) - 1]);
        $previewName = implode('.', $previewName) . '_' . $width . '_' . $height;
        if($sharpen){
            $previewName .= '_sharpen';
        }
        if($exact){
            $previewName .= '_exact';
        }
        $previewName .= '.' . $postfix;
        $prefixDir = substr($image, 0, 4);
        if(file_exists($presenter->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir . '/' . $previewName)){
            return '/images/preview/' . $prefixDir . '/' . $previewName;
        }else{
            return $presenter->link('Image:preview', $image, $width, $height, $sharpen, $exact);
        }
    }
}
