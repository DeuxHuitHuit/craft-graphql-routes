<?php

namespace deuxhuithuit\routesapi\controllers;

use craft\web\Controller;
use yii\web\Response;
use Craft;

class RoutesController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    public function actionGet(): Response
    {
        $app = Craft::$app;
        $primarySiteUid = $app->sites->primarySite->uid;
        $sections = $app->entries->allSections;
        $sectionRoutes = [];
        foreach ($sections as $section) {
            $formattedRoute = $this->formatSection($section);
            if ($formattedRoute) {
                $sectionRoutes[] = $formattedRoute;
            }
        }
        return $this->asJson($sectionRoutes);
    }

    public function formatSection(\Craft\models\section $section)
    {
        $config = $section->config;
        $uriFormats = [];
        foreach ($config['siteSettings'] as $settings) {
            $uriFormat =  $settings['uriFormat'];
            // Ignore sections that don't have a uri
            if (!$uriFormat) {
                break;
            }
            $uriFormats[] = $uriFormat;
        }
        $uriFormats = array_unique($uriFormats);
        if (!$uriFormats) {
            return null;
        }
        $typeNames = array_map(fn ($type) => "{$section['handle']}_{$type['handle']}_entry", $section->entryTypes);
        return [
            'typeName' => $typeNames,
            'uri' => $uriFormats
        ];
    }
}
