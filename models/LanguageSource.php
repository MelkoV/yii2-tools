<?php

namespace melkov\components\models;

use Yii;
use \melkov\components\models\base\LanguageSource as BaseLanguageSource;

/**
 * This is the model class for table "language_source".
 */
class LanguageSource extends BaseLanguageSource
{

    private $translates = [];

    /**
     * Return translation for language
     *
     * @param string $lang language code, e.g. "en-US", "ru-RU"
     * @return string|null translation
     */
    public function getTranslateForLang($lang)
    {
        if (!$this->translates) {
            foreach ($this->languageTranslates as $translate) {
                $this->translates[$translate->language] = $translate->translation;
            }
        }
        return isset($this->translates[$lang]) ? $this->translates[$lang] : null;
    }

}
