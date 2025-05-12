<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Controlador de Etiquetas
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagsController extends AppController
{
    /**
     * Método Index
     *
     * @return \Cake\Http\Response|null|void Renderiza la vista
     */
    public function index()
    {
        $query = $this->Tags->find();
        $tags = $this->paginate($query);

        $this->set(compact('tags'));
    }

    /**
     * Método View
     *
     * @param string|null $id Id de la etiqueta.
     * @return \Cake\Http\Response|null|void Renderiza la vista
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function view(?string $id = null)
    {
        $tag = $this->Tags->get($id, contain: ['News']);
        $this->set(compact('tag'));
    }

    /**
     * Método Add
     *
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     */
    public function add()
    {
        $tag = $this->Tags->newEmptyEntity();
        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('La etiqueta ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La etiqueta no pudo ser guardada. Por favor, intente nuevamente.'));
        }
        $news = $this->Tags->News->find('list', limit: 200)->all();
        $this->set(compact('tag', 'news'));
    }

    /**
     * Método Edit
     *
     * @param string|null $id Id de la etiqueta.
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function edit(?string $id = null)
    {
        $tag = $this->Tags->get($id, contain: ['News']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('La etiqueta ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La etiqueta no pudo ser guardada. Por favor, intente nuevamente.'));
        }
        $news = $this->Tags->News->find('list', limit: 200)->all();
        $this->set(compact('tag', 'news'));
    }

    /**
     * Método Delete
     *
     * @param string|null $id Id de la etiqueta.
     * @return \Cake\Http\Response|null Redirige al índice.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tag = $this->Tags->get($id);
        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('La etiqueta ha sido eliminada.'));
        } else {
            $this->Flash->error(__('La etiqueta no pudo ser eliminada. Por favor, intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
