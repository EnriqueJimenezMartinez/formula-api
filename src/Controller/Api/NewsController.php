<?php
declare(strict_types=1);

namespace App\Controller\Api;

use DateTime;

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

        $this->respond($news);
    }

    public function lastDate(?string $date){
        if(!$date){
            $this->respond(null, 'error', 'Fecha vacía', 401);
            return;
        }
        $date = new DateTime($date);
        $lastNews = $this->News->find('all', fields: ['created'])
        ->orderBy(['created' => 'ASC'])
        ->first();

        $respuesta=[$lastNews,$date];
        $this->respond($respuesta);

    }
}
