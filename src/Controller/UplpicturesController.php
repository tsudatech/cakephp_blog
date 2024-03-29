<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event; // 追加
use Cake\Log\Log;

/**
 * UploadPictures Controller
 *
 *
 * @method \App\Model\Entity\UploadPicture[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UplPicturesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('CheckExtension');
    }

    public function isAuthorized($user)
    {
      return true;
    }

    public function beforeFilter(Event $event)
  	{
  		parent::beforeFilter($event);
  		$this->Security->setConfig('unlockedActions', ['add','load']);
  		$this->Auth->allow(['add','load']);

  	}

    /**
    *
    *アップロード画像をロードするメソッド
    *
    */
    public function load(){
      $this->autoRender = FALSE;
      if ($this->request->is('ajax')) {
        //アップロード画像一覧を返す
        $uplpictures = $this->Uplpictures->find('all');
        foreach($uplpictures as $picture){
          Log::write('debug', $picture->title);
        }
        $resultJ = json_encode($uplpictures);
        $this->response->type('json');
        $this->response->body($resultJ);
        return $this->response;
      }

      $this->cakeError('error404');

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $this->autoRender = FALSE;
        if ($this->request->is('ajax')) {
          $file = $this->request->data['uploadimage'];
          $uplpicture = $this->Uplpictures->newEntity();
          $uplpicture->title = $file['name'];

          //拡張子が画像ファイル以外の場合はエラー
          $validExtension = array( "jpg", "png" );
          if(!$this->CheckExtension->chk_ext($file['name'], $validExtension)){
            Log::write('error','file extension is invalid');
            $this->response->type('text');
      			$this->response->body('file extension is invalid');
            $this->response->statusCode(404);
      			return $this->response;
          }

          if($this->Uplpictures->save($uplpicture)){
            move_uploaded_file($file['tmp_name'],'../webroot/img/uploaded/'. $file['name']);
          }else{
            Log::write('error','maybe same file has already existed');
            $this->response->type('text');
      			$this->response->body('maybe same file has already existed');
            $this->response->statusCode(404);
      			return $this->response;
          }

        }else{
          $this->cakeError('error404');
        }

        //アップロード画像一覧を返す
        $uplpictures = $this->Uplpictures->find('all');
        $resultJ = json_encode($uplpictures);
  			$this->response->type('json');
  			$this->response->body($resultJ);
  			return $this->response;

    }

//    /**
//     * Index method
//     *
//     * @return \Cake\Http\Response|void
//     */
//    public function index()
//    {
//        $uploadPictures = $this->paginate($this->UploadPictures);//

//        $this->set(compact('uploadPictures'));
//    }//

//    /**
//     * View method
//     *
//     * @param string|null $id Upload Picture id.
//     * @return \Cake\Http\Response|void
//     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
//     */
//    public function view($id = null)
//    {
//        $uploadPicture = $this->UploadPictures->get($id, [
//            'contain' => []
//        ]);//

//        $this->set('uploadPicture', $uploadPicture);
//    }



//    /**
//     * Edit method
//     *
//     * @param string|null $id Upload Picture id.
//     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
//     * @throws \Cake\Network\Exception\NotFoundException When record not found.
//     */
//    public function edit($id = null)
//    {
//        $uploadPicture = $this->UploadPictures->get($id, [
//            'contain' => []
//        ]);
//        if ($this->request->is(['patch', 'post', 'put'])) {
//            $uploadPicture = $this->UploadPictures->patchEntity($uploadPicture, $this->request->getData());
//            if ($this->UploadPictures->save($uploadPicture)) {
//                $this->Flash->success(__('The upload picture has been saved.'));//

//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The upload picture could not be saved. Please, try again.'));
//        }
//        $this->set(compact('uploadPicture'));
//    }//

//    /**
//     * Delete method
//     *
//     * @param string|null $id Upload Picture id.
//     * @return \Cake\Http\Response|null Redirects to index.
//     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
//     */
//    public function delete($id = null)
//    {
//        $this->request->allowMethod(['post', 'delete']);
//        $uploadPicture = $this->UploadPictures->get($id);
//        if ($this->UploadPictures->delete($uploadPicture)) {
//            $this->Flash->success(__('The upload picture has been deleted.'));
//        } else {
//            $this->Flash->error(__('The upload picture could not be deleted. Please, try again.'));
//        }//

//        return $this->redirect(['action' => 'index']);
//    }
}
