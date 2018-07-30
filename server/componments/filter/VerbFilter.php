<?php

namespace app\componments\filter;

use app\modules\v1\utils\ResponseMap;
use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

class VerbFilter extends Behavior
{
    /**
     * @var array this property defines the allowed request methods for each action.
     * For each action that should only support limited set of request methods
     * you add an entry with the action id as array key and an array of
     * allowed methods (e.g. GET, HEAD, PUT) as the value.
     * If an action is not listed all request methods are considered allowed.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => ['get', 'post'],
     *   'update' => ['get', 'put', 'post'],
     *   'delete' => ['post', 'delete'],
     *   '*' => ['get'],
     * ]
     * ```
     */
    public $actions = [];


    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }

        $verb = Yii::$app->getRequest()->getMethod();
        $allowed = array_map('strtoupper', $verbs);
        if (!in_array($verb, $allowed)) {
            $event->isValid = false;
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
            Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $allowed));
            $message = ResponseMap::Map("300002");
            $message = str_replace("#verb",implode(', ', $allowed),$message);
            throw new MethodNotAllowedHttpException($message,"300002");
        }

        return $event->isValid;
    }
}