<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Controlador de Noticias
 *
 * @property \App\Model\Table\NewsTable $News
 */
class NewsController extends AppController
{
    /**
     * Método Index
     *
     * @return \Cake\Http\Response|null|void Renderiza la vista
     */
    public function index()
    {
        $query = $this->News->find()
            ->contain(['Users']);
        $news = $this->paginate($query);

        $this->set(compact('news'));
    }

    /**
     * Método View
     *
     * @param string|null $id Id de la noticia.
     * @return \Cake\Http\Response|null|void Renderiza la vista
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function view(?string $id = null)
    {
        $news = $this->News->get($id, contain: ['Users', 'Tags']);
        $this->set(compact('news'));
    }

    /**
     * Método Add
     *
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     */
    public function add()
    {
        $news = $this->News->newEmptyEntity();
        if ($this->request->is('post')) {
            $news = $this->News->patchEntity($news, $this->request->getData());
            if ($this->News->save($news)) {
                $this->Flash->success(__('La noticia ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La noticia no pudo ser guardada. Por favor, intente nuevamente.'));
        }
        $users = $this->News->Users->find('list', limit: 200)->all();
        $tags = $this->News->Tags->find('list', limit: 200)->all();
        $this->set(compact('news', 'users', 'tags'));
    }

    /**
     * Método Edit
     *
     * @param string|null $id Id de la noticia.
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function edit(?string $id = null)
    {
        $news = $this->News->get($id, contain: ['Tags']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $news = $this->News->patchEntity($news, $this->request->getData());
            if ($this->News->save($news)) {
                $this->Flash->success(__('La noticia ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La noticia no pudo ser guardada. Por favor, intente nuevamente.'));
        }
        $users = $this->News->Users->find('list', limit: 200)->all();
        $tags = $this->News->Tags->find('list', limit: 200)->all();
        $this->set(compact('news', 'users', 'tags'));
    }

    /**
     * Método Delete
     *
     * @param string|null $id Id de la noticia.
     * @return \Cake\Http\Response|null Redirige al índice.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $news = $this->News->get($id);
        if ($this->News->delete($news)) {
            $this->Flash->success(__('La noticia ha sido eliminada.'));
        } else {
            $this->Flash->error(__('La noticia no pudo ser eliminada. Por favor, intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
