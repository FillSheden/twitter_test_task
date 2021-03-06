<?php
namespace frontend\controllers;


use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use common\models\LoginForm;
use common\models\TwitterUsers;
use common\shop\services\TwitterService;
use common\shop\forms\TwitterUserCreatingForm;
use common\shop\repositories\TwitterUserRepository;

/**
 * Site controller
 */
class SiteController extends Controller
{

    private $twitterService;
    private $twitterUserRepository;

    public function __construct($id, $module, array $config = [])
    {
        $this->twitterService = new TwitterService;
        $this->twitterUserRepository = new TwitterUserRepository;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $twitterUserCreatingForm = new TwitterUserCreatingForm;
        $dataProvider = $this->twitterUserRepository->getProviderUsers();
        return $this->render('index', compact('twitterUserCreatingForm', 'dataProvider'));
    }

    public function actionSaveTwitterUser()
    {
        $twitterUserCreatingForm = new TwitterUserCreatingForm;
        if ($twitterUserCreatingForm->load(Yii::$app->request->post()) and $twitterUserCreatingForm->validate()) {
            if ($this->twitterUserRepository->trySaveUser($twitterUserCreatingForm)) {
                Yii::$app->session->setFlash('twitter-user-creating', 'User created!');
            } else {
                Yii::$app->session->setFlash('twitter-user-creating', 'Error creating!');
            }
        } return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function actionShow($id)
    {
        $user_screen_name = $this->twitterUserRepository->getUserScreenName($id);
        if ($user_tweets = $this->twitterService->getUserTimeLine($user_screen_name) and !isset($user_tweets->errors)) {
            return $this->render('show', ['user_tweets' => $user_tweets, 'user_name' => $user_screen_name]);
        } else {
            Yii::$app->session->setFlash('user_tweet_empty', 'User have not tweets yet!');
            return $this->redirect(['index']);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($deleting_user = TwitterUsers::findOne(['id', $id])) {
            if ($deleting_user->delete()) {
                Yii::$app->session->setFlash('user_tweet_deleting', 'Success user deleting!');
            }
        } else {
            Yii::$app->session->setFlash('user_tweet_deleting', 'Error user deleting!');
        } return $this->redirect(['index']);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
