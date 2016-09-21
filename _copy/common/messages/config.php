<?php
/**
 * Configuration file for 'yii message/extract' command.
 *
 * This file is automatically generated by 'yii message/config' command.
 * It contains parameters for source code messages extraction.
 * You may modify this file to suit your needs.
 *
 * You can use 'yii message/config-template' command to create
 * template configuration file with detaild description for each parameter.
 */
return [
    'color' => null,
    'interactive' => true,
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..",
    'messagePath' => '@common/messages',
    'languages' => ["ru-RU"],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'markUnused' => true,
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/BaseYii.php',
    ],
    'only' => [
        '*.php',
    ],
    'format' => 'php',
//    'format' => 'db',
    'db' => 'db',
    'sourceMessageTable' => '{{%language_source}}',
    'messageTable' => '{{%language_translate}}',
    'catalog' => 'messages',
    'ignoreCategories' => [],
];
