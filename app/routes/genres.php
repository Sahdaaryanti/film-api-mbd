<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    // Create Genre
    $app->post('/genres', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['id_fgenre'], $parseBody['genre'])) {
                throw new InvalidArgumentException('Gagal menambahkan genre, harap isi data dengan benar!');
            }

            $g_id_genre = $parseBody['id_genre'];
            $g_genre = $parseBody['genre'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL insert_genre(:g_id_genre,:g_genre)');
            $query->bindParam(':g_id_genre', $g_id_genre, PDO::PARAM_STR);
            $query->bindParam(':g_genre', $g_genre, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Genre berhasil diTambahkan!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    

    // Get All Genres
    $app->get('/genres', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL select_all_genres()');
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($results));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get Genre by id
    $app->get('/genres/{id}', function (Request $request, Response $response, $args) {
        try {
            $g_id_genre = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL select_genre_id(:g_id_genre)');
            $query->bindParam(':g_id_genre', $g_id_genre, PDO::PARAM_STR);
    
            $query->execute();
    
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $response->getBody()->write(json_encode($results));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });
    

    // Update Genre
    $app->put('/genres/{id}', function (Request $request, Response $response, $args) {
        try {
            $g_id_genre = $args['id'];

            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['genre_baru'])) {
                throw new InvalidArgumentException('Gagal update! harap isi data dengan lengkap');
            }

            $g_genre_baru = $parseBody['genre_baru'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL update_genre(:g_id_genre, :g_genre_baru)');
            $query->bindParam(':g_id_genre', $g_id_genre, PDO::PARAM_STR);
            $query->bindParam(':g_genre_baru', $g_genre_baru, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Genre berhasil diUpdate!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Delete Genre
    $app->delete('/genres/{id}', function (Request $request, Response $response, $args) {
        try {
            $g_id_genre = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL hapus_genre(:g_id_genre)');
            $query->bindParam(':g_id_genre', $g_id_genre, PDO::PARAM_STR);
    
            $query->execute();
    
            $rowCount = $query->rowCount();
    
            $response->getBody()->write(json_encode(['message' => 'Genre berhasil dihapus']));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });  
};