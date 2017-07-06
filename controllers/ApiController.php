<?php

namespace melkov\tools\controllers;

use melkov\tools\exceptions\ApiException;
use Yii;
use yii\base\Exception;
use yii\base\InvalidRouteException;
use yii\base\Module;
use yii\base\UserException;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;

class ApiController extends Controller
{

    const CODE_SUCCESS = 0;
    const CODE_RUNTIME_ERROR = 10;
    const CODE_BAD_REQUEST = 400;
    const CODE_NOT_FOUND = 404;

    private $_code = 0;
    private $_result = [];
    private $_error = [];

    protected $runtimeErrorName = "_system";

    /**
     * @var string the name of the error when the exception name cannot be determined.
     * Defaults to "Error".
     */
    public $defaultName;
    /**
     * @var string the message to be displayed when the exception message contains sensitive information.
     * Defaults to "An internal server error occurred.".
     */
    public $defaultMessage;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['*'],
//                    'Access-Control-Request-Method' => ['POST', 'PUT'],
                    // Allow only POST and PUT methods
//                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow only headers 'X-Wsse'
//                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
//                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],

            ],
        ];
    }

    public function runAction($id, $params = [])
    {
        $action = $this->createAction($id);
        if ($action === null) {
            throw new InvalidRouteException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $id);
        }

        Yii::trace('Route to run: ' . $action->getUniqueId(), __METHOD__);

        if (Yii::$app->requestedAction === null) {
            Yii::$app->requestedAction = $action;
        }

        $oldAction = $this->action;
        $this->action = $action;

        $modules = [];
        $runAction = true;

        // call beforeAction on modules
        foreach ($this->getModules() as $module) {
            if ($module->beforeAction($action)) {
                array_unshift($modules, $module);
            } else {
                $runAction = false;
                break;
            }
        }

        $result = null;

        if ($runAction && $this->beforeAction($action)) {
            // run the action
            try {
                $result = $action->runWithParams($params);

                $result = $this->afterAction($action, $result);

                // call afterAction on modules
                foreach ($modules as $module) {
                    /* @var $module Module */
                    $result = $module->afterAction($action, $result);
                }
                if (is_object($result)) {
                    $c = explode("\\", get_class($result));
                    $class = array_pop($c);
                    $this->_result[$class] = [];
                    foreach ($result->exportData() as $attr) {
                        $this->_result[$class][$attr] = $result->$attr;
                    }

                } else {
                    $this->_result = $result;
                }

            } catch (ApiException $e) {
                $code = $e->getCode() ? : self::CODE_RUNTIME_ERROR;
                $error = $e->getError();
                if (!is_array($error)) {
//                    $errorArray = [];
//                    $errorArray[$this->runtimeErrorName] = [0 => $error];
                    $error = [$this->runtimeErrorName => [0 => $error]];
//                    $error = $errorArray;
                }
                $errorMessageAr = [];
                foreach ($error as $ar) {
                    if (is_array($ar) && isset($ar[0])) {
                        $errorMessageAr[] = $ar[0];
                    } else {
                        $errorMessageAr[] = $ar;
                    }
                }
                $error["_summary"] = implode(" ", $errorMessageAr);
                $this->_result = [];
                $this->_error = $error;
                $this->_code = $code;
                Yii::$app->response->statusCode = 200;
            }
        }

        $this->action = $oldAction;
//        Yii::$app->response->setStatusCode(200);
        return Json::encode(["code" => $this->_code, "result" => $this->_result, "error" => $this->_error]);
    }

    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }

        throw new ApiException($message, $code);
    }
}