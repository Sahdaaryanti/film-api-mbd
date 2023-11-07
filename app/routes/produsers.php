<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
// Create produser
    $app->post('/produsers', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

            if (!isset($parseBody['id_produser'], $parseBody['nama_produser'], $parseBody['asal_negara'])) {
                throw new InvalidArgumentException('Gagal menambahkan produser, harap isi data dengan benar!');
            }

            $p_id_produser = $parseBody['id_produser'];
            $p_nama_produser = $parseBody['nama_produser'];
            $p_asal_negara = $parseBody['asal_negara'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL insert_produser(:p_id_produser, :p_nama_produser, :p_asal_negara)');
            $query->bindParam(':p_id_produser', $p_id_produser, PDO::PARAM_STR);
            $query->bindParam(':p_nama_produser', $p_nama_produser, PDO::PARAM_STR);
            $query->bindParam(':p_asal_negara', $p_asal_negara, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Prosedur berhasil ditambahkan!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get All Produsers
    $app->get('/produsers', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL select_all_produsers()');
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($results));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Get Produser by id
    $app->get('/produsers/{id}', function (Request $request, Response $response, $args) {
        try {
            $p_id_produser = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL select_produser_id(:p_id_produser)');
            $query->bindParam(':p_id_produser', $p_id_produser, PDO::PARAM_STR);
    
            $query->execute();
    
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $response->getBody()->write(json_encode($results));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });
    

    // Update Produser
    $app->put('/produsers/{id}', function (Request $request, Response $response, $args) {
        try {
            $p_id_produser = $args['id'];

            if (!isset($parseBody['asal_negara_baru'])) {
                throw new InvalidArgumentException('Gagal update! harap isi data dengan lengkap');
            }

            $parseBody = $request->getParsedBody();

            $p_asal_negara_baru = $parseBody['asal_negara_baru'];

            $db = $this->get(PDO::class);

            $query = $db->prepare('CALL update_asal_negara_produser(:p_id_produser, :p_asal_negara_baru)');
            $query->bindParam(':p_id_produser', $p_id_produser, PDO::PARAM_STR);
            $query->bindParam(':p_asal_negara_baru', $p_asal_negara_baru, PDO::PARAM_STR);

            $query->execute();

            $response->getBody()->write(json_encode(['message' => 'Produser berhasil diUpdate!']));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // Delete Produser
    $app->delete('/produsers/{id}', function (Request $request, Response $response, $args) {
        try {
            $p_id_produser = $args['id'];
    
            $db = $this->get(PDO::class);
    
            $query = $db->prepare('CALL hapus_produser(:p_id_produser)');
            $query->bindParam(':p_id_produser', $p_id_produser, PDO::PARAM_STR);
    
            $query->execute();
    
            $rowCount = $query->rowCount();
    
            $response->getBody()->write(json_encode(['message' => 'Produser berhasil dihapus']));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });
};