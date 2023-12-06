<?php

namespace deuxhuithuit\routesapi\controllers;

use craft\web\Controller;
use yii\web\Response;

class SitesController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    public function actionLocales(): Response
    {
        $app = \Craft::$app;
        return $this->asJson(
            array_map(fn ($site) => $site->locale->id, $app->sites->allSites)
        );
    }
}
