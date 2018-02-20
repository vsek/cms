<?php
/**
 * Created by PhpStorm.
 * User: Vsek
 * Date: 08.08.2017
 * Time: 10:02
 */

namespace App\Translate;

use App\Model\Module\Language;
use Kdyby\Translation\CatalogueCompiler;
use Kdyby\Translation\FallbackResolver;
use Kdyby\Translation\IResourceLoader;
use Kdyby\Translation\IUserLocaleResolver;
use Symfony\Component\Translation\Formatter\MessageFormatter;

class Translator extends \Kdyby\Translation\Translator{
    /**
     * Aktivni jazyk
     * @var \Nette\Database\Table\ActiveRow
     */
    private $language = null;

    /**
     *
     * @var Language
     */
    private $languages;

    public function __construct(IUserLocaleResolver $localeResolver, MessageFormatter $formatter, CatalogueCompiler $catalogueCompiler, FallbackResolver $fallbackResolver, IResourceLoader $loader, Language $languages) {
        parent::__construct($localeResolver, $formatter, $catalogueCompiler, $fallbackResolver, $loader);
        $this->languages = $languages;
    }

    /**
     * Vraci aktualni jazyk
     * @return \Nette\Database\Table\ActiveRow
     */
    public function getLanguage(){
        if(is_null($this->language)){
            $this->language = $this->languages->where('link', $this->getLocale())->fetch();
        }
        return $this->language;
    }
}