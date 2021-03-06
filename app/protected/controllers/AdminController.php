<?php

class AdminController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('resetpass', 'forgot'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'download', 'user', 'useredit', 'userdelete', 'getlink', 'migrate', 'update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionUser()
	{

		if(isset($_POST['User'])){
			if($_POST['User']['id']){
				$model =  User::model()->findByPk((int)$_POST['User']['id']);
				$model->attributes=$_POST['User'];
			}else{
				$model =  new User;
				$model->attributes=$_POST['User'];
				$salt = User::generateSalt();
				$model->password = "rand";
				$model->password=User::hashPassword($model->password,$salt).':'.$salt;

			}
			$model->confirm=$model->password;

			if($model->validate())
			{
				if($model->save()){
					$subject='=?UTF-8?B?'.base64_encode(Yii::app()->name." - Reset Password").'?=';
					$headers="From: ".Yii::app()->params['adminEmail']."\r\n".
						"Reply-To: ".Yii::app()->params['adminEmail']."\r\n".
						"MIME-Version: 1.0\r\n".
						"Content-type: text/html; charset=UTF-8\r\n";
					$message = 'To reset your password, click on the link below:<br><br>'.
						Yii::app()->getBaseUrl(true).$this->createUrl('admin/resetpass').'/'.$model->id.':'.
						User::model()->hashPassword($model->password,'miranda');
					mail($model->email,$subject,$message,$headers);
					$this->redirect($this->createUrl('admin/user'));
				}else{
					$error = Yii::app()->errorHandler->error;
					print_r($error);
					die();
				}
			}else{
				$error=$model->getErrors();
				print_r($error);
					die();
			}

		}

		$dataProvider=new CActiveDataProvider('User',array(
            //'criteria'=>$criteria,
            'pagination'=>false,
        ));

		$this->render('user', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * actionUserEdit function.
	 *
	 * @access public
	 * @return void
	 */
	public function actionUserEdit(){
		if(isset($_GET['userId'])){
            if( !is_numeric($_GET['userId']) ){
                throw new CHttpException(500,"Invalid userId specified ".$_GET['userId']." !");
            }
			$model = User::model()->findByPk((int)$_GET['userId']);
        }
        else
			$model = new User;
		$this->renderPartial('_form_user', array('user'=>$model, 'ajax'=>true), false, false);
	}

	public function actionUserDelete(){
		if(isset($_GET['userId'])){
			$model = User::model()->findByPk((int)$_GET['userId']);
			$model->delete();
		}
		$dataProvider=new CActiveDataProvider('User',array(
            //'criteria'=>$criteria,
            'pagination'=>false,
        ));
		$this->renderPartial('_view_user', array('dataProvider'=>$dataProvider, 'ajax'=>true), false, false);
	}

	public function actionDownload()
	{
		$this->render('download');
	}

	public function actionGetlink()
	{
		if(Yii::app()->user->isSuperAdmin){
			if(isset($_GET['email'])){
				$users = User::model()->findAll();
				foreach($users as $user){
					if($user->email == $_GET['email']){
						echo "Password Reset Link for ". $user->name . "<br>" . Yii::app()->getBaseUrl(true).$this->createUrl('admin/resetpass').'/'.$user->id.':'.
						User::model()->hashPassword($user->password,'miranda');
					}
				}
			}
		}
	}

	public function actionForgot(){
		if(isset($_POST['email'])&&$_POST['email']!=''){
			$email = $_POST['email'];
			$users = User::model()->findAll();
			foreach($users as $user){
				if($user->email == $email)
					$model = $user;
			}
			if($model){
				$subject='=?UTF-8?B?'.base64_encode(Yii::app()->name." - Reset Password").'?=';
				$headers="From: ".Yii::app()->params['adminEmail']."\r\n".
					"Reply-To: ".Yii::app()->params['adminEmail']."\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset=UTF-8\r\n";
				$message = 'To reset your password, click on the link below:<br><br>'.
					Yii::app()->getBaseUrl(true).$this->createUrl('admin/resetpass').'/'.$model->id.':'.
					User::model()->hashPassword($model->password,'miranda');
				mail($email,$subject,$message,$headers);

				Yii::app()->user->setFlash('success','We have sent you an email with instructions on how to reset your password.  Good luck');
			}else{
				Yii::app()->user->setFlash('error','Error!  Email not found!');
			}
			$this->refresh();
		}
		// display the forgot form
		$this->render('forgot');
	}

	function actionResetpass(){
		if(!isset($_GET['id']))
			$this->redirect($this->createUrl('user/forgot'));
		list($id,$hash)=preg_split('/:/',$_GET['id']);
		$model = User::model()->findByPk($id);
		if($model && User::model()->hashPassword($model->password,'miranda')==$hash){
			$model->password='';
			if(isset($_POST['User']))
			{
				$model->attributes=$_POST['User'];
				$salt=User::model()->generateSalt();
				$password=$model->password;
				$model->password=User::model()->hashPassword($model->password,$salt).':'.$salt;
				$model->confirm=$model->password;
				if($model->save()){
    				$model->email = decrypt($model->email);
					$login=new LoginForm;
					$login->username=$model->email;
					$login->password=$password;
					if($login->validate() && $login->login()){
						$this->redirect($this->createUrl('/admin'));
					}
				}else{
					throw new CHttpException(500, print_r($model->errors));
				}
			}
			$this->render('reset',array(
				'model'=>$model,
			));
		}else{
			$this->redirect($this->createUrl('forgot'));
		}
	}

    public function actionMigrate() {
        $this->runMigrationTool();
    }

    public function actionUpdate() {
        $this->runGitUpdate();
    }
    
    private function runMigrationTool() {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('yiic', 'migrate', '--interactive=0');
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    private function runGitUpdate() {
        $commandPath = Yii::app()->getBasePath() . "/../../";
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('git', 'pull', '');
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }


}
