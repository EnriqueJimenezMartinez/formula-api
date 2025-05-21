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
    public function view()
{
    $slug = $this->request->getData('slug');

    if (!$slug) {
        $this->respond(null, 'error', 'Slug no proporcionado', 400);
        return;
    }

    $news = $this->News->find()
        ->where(['slug' => $slug])
        ->contain(['Users', 'Tags'])
        ->first();

    if (!$news) {
        $this->respond(null, 'error', 'Noticia no encontrada', 404);
        return;
    }

    $news->image_url = $this->getImageUrl($news->slug);

    $this->respond($news);
}


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
    /**
     * getImageUrl method
     *
     * @param string|null.
     * @return void
     */
    private function getImageUrl(string $slug)
    {
        $webroot = $this->request->getAttribute('webroot') ?? '/';
        $server = $this->request->getServerParams();
        $host = $server['HTTP_HOST'] ?? 'localhost';

        $imageDir = WWW_ROOT . 'img/news/';
        $urlBase = 'https://' . $host . $webroot . 'img/news/';
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
