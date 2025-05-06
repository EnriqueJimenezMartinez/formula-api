<?php
declare(strict_types=1);

namespace App\Controller\Api;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 */
class NewsController extends ApiController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->News->find()
            ->contain(['Users','Tags']);
        $news = $this->paginate($query);

        $this->respond($news);
    }

    /**
     * View method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null)
    {

        $news = $this->News->find('all', where: ['slug IS' => $slug], contain: ['Users', 'Tags'])
        ->first();
        $this->set(compact('news'));
        $this->viewBuilder()->setOption('serialize', 'news');
    }
}
