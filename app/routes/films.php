<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    // Create Film
    $app->post('/films', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['id_film'], $parseBody['judul_film'], $parseBody['tahun_rilis'])) {
                throw new InvalidArgumentException('Gagal menambahkan film, harap isi data dengan benar!');
            }

            $f_id_film = $parseBody['id_film'];
            $f_judul_film = $parseBody['judul_film'];
            $f_tahun_rilis = $parseBody['tahun_rilis'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL insert_film(:f_id_film,:f_judul_film,:f_tahun_rilis)');
            $query->bindParam(':f_id_film', $f_id_film, PDO::PARAM_STR);
            $query->bindParam(':f_judul_film', $f_judul_film, PDO::PARAM_STR);
            $query->bindParam(':f_tahun_rilis', $f_tahun_rilis, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Film berhasil ditambahkan!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get All Films
    $app->get('/films', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL select_all_films');
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($results));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get Film by id
    $app->get('/films/{id}', function (Request $request, Response $response, $args) {
        try {
            $f_id_film = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL select_film_id(:f_id_film)');
            $query->bindParam(':f_id_film', $f_id_film, PDO::PARAM_STR);
    
            $query->execute();
    
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $response->getBody()->write(json_encode($results));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    

    // Update Film
    $app->put('/films/{id}', function (Request $request, Response $response, $args) {
        try {
            $f_id_film = $args['id'];

            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['tahun_rilis_baru'])) {
                throw new InvalidArgumentException('Gagal update! harap isi data dengan lengkap');
            }

            $f_tahun_rilis_baru = $parseBody['tahun_rilis_baru'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL update_tahun_rilis_film(:f_id_film, :f_tahun_rilis_baru)');
            $query->bindParam(':f_id_film', $f_id_film, PDO::PARAM_STR);
            $query->bindParam(':f_tahun_rilis_baru', $f_tahun_rilis_baru, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Film berhasil diupdate!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Delete Film
    $app->delete('/films/{id}', function (Request $request, Response $response, $args) {
        try {
            $f_id_film = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL hapus_film(:f_id_film)');
            $query->bindParam(':f_id_film', $f_id_film, PDO::PARAM_STR);
    
            $query->execute();
    
            $rowCount = $query->rowCount();
    
            $response->getBody()->write(json_encode(['message' => 'Film berhasil dihapus']));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });
};