<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Skar.Skfbalbums',
    'Fbalbumsdisplay',
    'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:pi1_title'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('skfbalbums');
$pluginSignature = strtolower($extensionName) . '_fbalbumsdisplay'; 


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 
    'FILE:EXT:skfbalbums/Configuration/FlexForms/FlexForm_fbalbumsdisplay.xml');



