<?php
$servername = "localhost";
$username = "uqmwlannrmfwn";
$password = "cs20final";
$database = "db2kfob4fzh8gt";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed", "error" => $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['user']) || !isset($data['movie_id']) || !isset($data['title'])) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit;
    }

    $username = $data['user'];
    $movieId = $data['movie_id'];
    $title = $data['title'];
    $genre = $data['genre'] ?? null;
    $director = $data['director'] ?? null;
    $year = $data['year'] ?? null;
    $posterUrl = $data['poster_url'] ?? null;

    $userIdQuery = "SELECT id FROM user WHERE name = ?";
    $stmt = $conn->prepare($userIdQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertUserQuery = "INSERT INTO user (name) VALUES (?)";
        $stmt = $conn->prepare($insertUserQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $userId = $stmt->insert_id;
    } else {
        $userId = $result->fetch_assoc()['id'];
    }

    $insertFavoriteQuery = "INSERT INTO favorites (user_id, movie_id, title, genre, director, year, poster_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertFavoriteQuery);
    $stmt->bind_param("issssss", $userId, $movieId, $title, $genre, $director, $year, $posterUrl);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Movie added to favorites!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add movie to favorites", "error" => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
