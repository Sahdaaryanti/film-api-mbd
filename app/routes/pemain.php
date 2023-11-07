<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    // Create Pemain
    $app->post('/pemain', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['id_pemain'], $parseBody['nama_pemain'])) {
                throw new InvalidArgumentException('Gagal menambahkan pemain, harap isi data dengan benar!');
            }

            $a_id_pemain = $parseBody['id_pemain'];
            $a_nama_pemain = $parseBody['nama_pemain'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL insert_pemain(:a_id_pemain,:a_nama_pemain)');
            $query->bindParam(':a_id_pemain', $a_id_pemain, PDO::PARAM_STR);
            $query->bindParam(':a_nama_pemain', $a_nama_pemain, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Pemain berhasil diTambahkan!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    

    // Get All Pemain
    $app->get('/pemain', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL select_all_pemain()');
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($results));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get Pemain by id
    $app->get('/pemain/{id}', function (Request $request, Response $response, $args) {
        try {
            $a_id_pemain = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL select_pemain_id(:a_id_pemain)');
            $query->bindParam(':a_id_pemain', $a_id_pemain, PDO::PARAM_STR);
    
            $query->execute();
    
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $response->getBody()->write(json_encode($results));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });    

    // Update Pemain
    $app->put('/pemain/{id}', function (Request $request, Response $response, $args) {
        try {
            $a_id_pemain = $args['id'];

            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['nama_pemain_baru'])) {
                throw new InvalidArgumentException('Gagal update! harap isi data dengan lengkap');
            }

            $a_nama_pemain_baru = $parseBody['nama_pemain_baru'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL update_nama_pemain(:a_id_pemain, :a_nama_pemain_baru)');
            $query->bindParam(':a_id_pemain', $a_id_pemain, PDO::PARAM_STR);
            $query->bindParam(':a_nama_pemain_baru', $a_nama_pemain_baru, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Pemain berhasil diUpdate!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Delete Pemain
    $app->delete('/pemain/{id}', function (Request $request, Response $response, $args) {
        try {
            $a_id_pemain = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL hapus_pemain(:a_id_pemain)');
            $query->bindParam(':a_id_pemain', $a_id_pemain, PDO::PARAM_STR);
    
            $query->execute();
    
            $rowCount = $query->rowCount();
    
            $response->getBody()->write(json_encode(['message' => 'Pemain berhasil dihapus']));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });     
};