<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    // Create Studio
    $app->post('/studios', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['id_studio'], $parseBody['nama_studio'], $parseBody['tahun_berdiri'])) {
                throw new InvalidArgumentException('Gagal menambahkan studio, harap isi data dengan benar!');
            }

            $s_id_studio = $parseBody['id_studio'];
            $s_nama_studio = $parseBody['nama_studio'];
            $s_tahun_berdiri = $parseBody['tahun_berdiri'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL insert_studio(:s_id_studio,:s_nama_studio,:s_tahun_berdiri)');
            $query->bindParam(':s_id_studio', $s_id_studio, PDO::PARAM_STR);
            $query->bindParam(':s_nama_studio', $s_nama_studio, PDO::PARAM_STR);
            $query->bindParam(':s_tahun_berdiri', $s_tahun_berdiri, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Studio berhasil diTambahkan!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get All Studios
    $app->get('/studios', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL select_all_studios()');
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($results));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get Studio by id
    $app->get('/studios/{id}', function (Request $request, Response $response, $args) {
        try {
            $s_id_studio = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL select_studio_id(:s_id_studio)');
            $query->bindParam(':s_id_studio', $s_id_studio, PDO::PARAM_STR);
    
            $query->execute();
    
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $response->getBody()->write(json_encode($results));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    

    // Update Studio
    $app->put('/studios/{id}', function (Request $request, Response $response, $args) {
        try {
            $s_id_studio = $args['id'];

            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['tahun_berdiri_baru'])) {
                throw new InvalidArgumentException('Gagal update! harap isi data dengan lengkap');
            }

            $s_tahun_berdiri_baru = $parseBody['tahun_berdiri_baru'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL update_tahun_berdiri_studio(:s_id_studio, :s_tahun_berdiri_baru)');
            $query->bindParam(':s_id_studio', $s_id_studio, PDO::PARAM_STR);
            $query->bindParam(':s_tahun_berdiri_baru', $s_tahun_berdiri_baru, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Studio berhasil diUpdate!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Delete Studio
    $app->delete('/studios/{id}', function (Request $request, Response $response, $args) {
        try {
            $s_id_studio = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL hapus_studio(:s_id_studio)');
            $query->bindParam(':s_id_studio', $s_id_studio, PDO::PARAM_STR);
    
            $query->execute();
    
            $rowCount = $query->rowCount();
    
            $response->getBody()->write(json_encode(['message' => 'Studio berhasil dihapus']));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    
};