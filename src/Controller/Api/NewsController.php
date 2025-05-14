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
    /**
     * Initialize method
     *
     * @return void
     */
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
            ->contain(['Users', 'Tags']);

        $news = $this->paginate($query);

        // Añadir la URL de la imagen a cada elemento
        foreach ($news as $item) {
            $item->image_url = $this->getImageUrl($item->slug);
        }

        $this->respond($news);
    }

    /**
     * View method
     *
     * @param string|null $slug News slug.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $slug = null)
{
    $news = $this->News->find('all', [
        'where' => ['slug IS' => $slug],
        'contain' => ['Users', 'Tags'],
    ])->first();

    if ($news) {
        $news->image_url = $this->getImageUrl($news->slug);
    }

    $this->respond($news);
}


    /**
     * LastDate method
     *
     * @param string|null $date The input date string.
     * @return void
     */
   /* public function lastDate(?string $date): void
    {
        if (!$date) {
            $this->respond(null, 'error', 'Fecha vacía', 401);

            return;
        }

        $date = new DateTime($date);
        $lastNews = $this->News->find('all', fields: ['created'])
        ->orderBy(['created' => 'ASC'])
        ->first();

        $respuesta=[$lastNews,$date];
        $this->respond($respuesta);
        $lastNews = $this->News->find('all', [
            'fields' => ['created'],
        ])->orderBy(['created' => 'ASC'])->first();

        $respuesta = [$lastNews, $date];
        $this->respond($respuesta);
    }*/
     private function getImageUrl(string $slug)
{
    $webroot = $this->request->getAttribute('webroot') ?? '/';
    $server = $this->request->getServerParams();
    $host = $server['HTTP_HOST'] ?? 'localhost';

    $imageDir = WWW_ROOT . 'img/news/';
    $urlBase = 'http://' . $host . $webroot . 'img/news/';
    $extensions = ['jpg', 'png', 'webp'];

    foreach ($extensions as $ext) {
        $filename = $slug . '.' . $ext;
        $filePath = $imageDir . $filename;

        if (file_exists($filePath)) {
            return $urlBase . $filename;
        }
    }
}
}
