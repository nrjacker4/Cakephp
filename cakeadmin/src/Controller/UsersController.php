<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
//use Cake\Network\Email\Email;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
 error_reporting(0);
 
class UsersController extends AppController
{

	var $helpers = array('Html'); 
	
	public function beforeFilter(\Cake\Event\Event $event)
	{
	$this->Auth->allow(['add']);
	}
	public $paginate = [
		'limit' => 5,
		'order' => [
		'users.id' => 'desc'
		]
	 ];
	 
	public function initialize(){
	
		parent::initialize();
		$this->loadComponent('Paginator');
	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$users = $this->paginate($this->Users);
		$this->set(compact('users'));
		$this->set('_serialize', ['users']);
		if ($this->request->is('post')&& $this->request->data['username']!=null) {
			//print_r( $this->request->data);
			$query = $this->Users->find();
                        //$query->where(['username like' => $this->request->data['username']])->first();
						$query->where(['OR' => ['username LIKE' => $this->request->data['username'], 'email LIKE' => $this->request->data['username']]]);
						//print_r($user_data);
						 $this->set('users', $this->paginate($query));

		}
		
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		if ( $this->Auth->user() ){ 
			$this->redirect('/users');
    	} else {
			$user = $this->Users->newEntity();
			if ($this->request->is('post')) {
				$user = $this->Users->patchEntity($user, $this->request->data);
				if ($this->Users->save($user)) {
					//email send start
					//$userName = $this->request->data['username'] . " " . $this->request->data['email'] . " " . $this->request->data['password'];
					$data	  = 'Email '   . "   (" . $this->request->data['email'] .")   ".
								'Username '. "   (" . $this->request->data['username'] .")   ".
								'Password '. "   (" . $this->request->data['password'].') ';
								
					$email = new Email();
					$email->transport('mailjet');
			
			
					try {
						$res = $email->from(['nrathore.web@gmail.com' => 'Decon India'])
							  ->to($this->request->data['email'])
							  ->subject('Account Details')                   
							  ->send($data);
			
					} catch (Exception $e) {
			
						echo 'Exception : ',  $e->getMessage(), "\n";
			
					}
					
					//email send end
					$this->Flash->success(__('Account has been created Sucessfully,Password has been sent to your Email Account'));
					return $this->redirect(['action' => 'index']);
				} else {
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			}
			$this->set(compact('user'));
			$this->set('_serialize', ['user']);
		}
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'users']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
	//In src/Controller/UsersController.php
	public function login()
	{
	
		if ( $this->Auth->user() ){ 
			$this->redirect('/users');
    	} else {
			if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
			$this->Auth->setUser($user);
			return $this->redirect($this->Auth->redirectUrl('/users'));
			}
			$this->Flash->error('Your username or password is incorrect.');
			}
		}
	}
	
	public function logout()
	{
	$this->Flash->success('You are now logged out.');
	return $this->redirect($this->Auth->logout());
	}
	
	public function forget(){
	
		//$total = 	$this->Users->find('count')->where(['email' => $this->request->data['email']]);
		$use = $this->Users->find()->where(['email' => $this->request->data['email']])->first();
		$numUsers = sizeof($use);
		//echo $numUsers;die;
		
		if ($numUsers===1) {
			$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$char = strlen($Caracteres);
			$char--;
			
				$Hash=NULL;
				for($x=1;$x<=30;$x++){
					$Posicao = rand(0,$char);
					$Hash .= substr($Caracteres,$Posicao,1);
				}
				//echo $Hash;die;

					$query = $this->Users->query();
					$token_updated	=	$query->update()->set(['token' => $Hash])->where(['email' => $this->request->data['email']])->execute();
					if($token_updated==true){
						$user_data 		= 	$this->Users->find()->where(['email' => $this->request->data['email']])->first();
								
						$data  = 'http://localhost/cakeadmin/users/password/'.$user_data['token'];	
						//echo $data;die;
						$email = new Email();
						$email->transport('mailjet');
						try{
							$res = $email->from(['nrathore.web@gmail.com' => 'Decon India'])->to($this->request->data['email'])->subject('Reset Password Link')->send($data);
							$this->Flash->success('Success,Password Reset link has been set to your email Address');
						}catch(Exception $e){
							echo 'Exception : ',  $e->getMessage(), "\n";
						}
					}
			
		}
		else{
		$this->Flash->error('Your Email is incorrect.');
		}
	}
	
	public function password($token=null){
		//print_r($_GET);die;
		$user_data 	= $this->Users->find()->where(['token' => $token])->first();
		$total		=	sizeof($user_data);
 		$user 		= $this->Users->patchEntity($user_data, ['password' => $this->request->data['password']]);
		//debug($user);
		//exit();
		if($total===1){
			if ($this->Users->save($user)) {
				$this->Flash->success('Success,New Password has been Set');
				 return $this->redirect(['action' => 'login']);
			} 
			//else {$this->Flash->error('There is problem in setting password, Please contact Administrator');}
		}else{
			$this->Flash->error('Security Token is not correct');
		}
                       
	}
	
}


?>

<?php /*?>if($this->request->is('post')){

        $userName = $this->request->data['firstname'] . " " . $this->request->data['lastname'];

        $email = new Email();
        $email->transport('mailjet');


        try {
            $res = $email->from([$this->request->data['email'] => $userName])
                  ->to(['myEmail@hotmail.com' => 'My Website'])
                  ->subject('Contact')                   
                  ->send($this->request->data['message']);

        } catch (Exception $e) {

            echo 'Exception : ',  $e->getMessage(), "\n";

        }


    }<?php */?>