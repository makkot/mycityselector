<?php
/**
 * Plugin of MyCitySelector extension
 */

defined('_JEXEC') or exit(header("HTTP/1.0 404 Not Found") . '404 Not Found');

JLoader::import('joomla.plugin.plugin');
JLoader::import('plugins.system.plgmycityselector.helpers.CitiesTagsHelper', JPATH_ROOT);
JLoader::import('plugins.system.plgmycityselector.helpers.ArticleFormHelper', JPATH_ROOT);
JLoader::import('plugins.system.plgmycityselector.helpers.McsData', JPATH_ROOT);

class plgSystemPlgMycityselector extends JPlugin
{

    /**
     * @var bool
     */
    private $editMode = false;

    /**
     * Initialization
     */
    function __construct(&$subject, $params)
    {
        parent::__construct($subject, $params);
        $doc = JFactory::getDocument();
        $jInput = JFactory::getApplication()->input;

        if (isset($_GET['mcs']) && $_GET['mcs'] == 'clscookie') {
            unset($_COOKIE['MCS_CITY_CODE']);
        }

        // check for "backend mode" and "frontend edit mode"
        $this->editMode = ($jInput->get('view') == 'form' && $jInput->get('layout') == 'edit');
        if (!$this->editMode && JFactory::getApplication()->getName() != 'administrator') {
            // load data and settings
            McsData::load(true);
            // check for redirect
            if (McsData::get('needRedirectTo')) {
                exit(header('Location: ' . McsData::get('needRedirectTo')));
            }
            $baseIP = McsData::get('baseip', 'none');
            if (McsData::get('isUserHasSelected')) {
                $doc->addScriptDeclaration('window.mcs_dialog=0;');
            } else {
                if (McsData::get('let_select', '1') == '1') {
                    $doc->addScriptDeclaration('window.mcs_dialog=1;'); // show dialog window
                } else {
                    $doc->addScriptDeclaration('window.mcs_dialog=2;'); // show question for change city
                }
                // Yandex geolocation
                if ($baseIP == 'yandexgeo') {
                    $doc->addScriptDeclaration('window.mcs_yandexgeo=true;');
                }
            }

        } else {
            // backend or frontend edit mode. No actions, only settings load (except cities).
            McsData::load(false); // load all settings and cities
        }
    }


    /**
     * Event listener for content edit form
     * Adding our tab in form
     * @param $form
     * @param $data
     * @return bool
     */
    function onContentPrepareForm($form, $data) {
        // TODO ArticleFormHelper::addWidget();
    }


    /**
     * Метод для вызова системным триггером.
     * Парсинг контента и "обворачивание" текста городов спец. тегами
     */
    public function onAfterRender()
    {
        if (!$this->editMode && !JFactory::getApplication()->getName() == 'administrator') { // не делаем замену блоков в админке и в режиме редактирования статьи
            $body = $this->getPageBody();
            $body = CitiesTagsHelper::parseCitiesTags($body, $this->city, $this->citiesList); // парсим контент

            // TODO замена тегов по контенту в таблице

            $this->setPageBody($body);
        }
        return true;
    }


    /**
     * Alias for APP->getBody();
     * @return string
     */
    private function getPageBody(){
        $app = JFactory::getApplication();
        if (method_exists($app, 'getBody')) {
            return $app->getBody(); // Joomla 3.x
        }
        // joomla 2.5
        return JResponse::getBody();
    }


    /**
     * Alias for APP->setBody();
     */
    private function setPageBody($body){
        $app = JFactory::getApplication();
        if (method_exists($app, 'setBody')) {
            $app->setBody($body); // Joomla 3.x
        } else {
            // joomla 2.5
            JResponse::setBody($body);
        }
    }

}
