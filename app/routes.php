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
    

     // Create produser
    $app->post('/produsers', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

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

    // Create Studio
    $app->post('/studios', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

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
    
    // Create Genre
    $app->post('/genres', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

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

    // Create Pemain
    $app->post('/pemain', function (Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();

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
