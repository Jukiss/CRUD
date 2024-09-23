<?php
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$dsn = 'mysql:host=localhost;dbname=cruda;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

switch ($method) {
    case 'POST':
        if (isset($_GET['action']) && $_GET['action'] === 'login') {
            authUser();
        } else {
            createUser();
        }
        break;
    case 'GET':
        getUser();
        break;
    case 'PATCH':
        updateUser();
        break;
    case 'DELETE':
        deleteUser();
        break;
        default:
        echo json_encode(['error' => 'Method not allowed']);
}

function createUser() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $login = $data['login'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $email = $data['email'];

    $stmt = $pdo->prepare("INSERT INTO users (login, password, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$login, $password, $email])) {
        echo json_encode(['message' => 'User created']);
    } else {
        echo json_encode(['error' => 'Failed to create user']);
    }
}

function getUser() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT id, login, email FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

function updateUser() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $_GET['id'];
    
    $updates = [];
    if (isset($data['login'])) {
        $updates[] = "login = '" . $data['login'] . "'";
    }
    if (isset($data['email'])) {
        $updates[] = "email = '" . $data['email'] . "'";
    }
    
    if (!empty($updates)) {
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            echo json_encode(['message' => 'User updated']);
        } else {
            echo json_encode(['error' => 'Failed to update user']);
        }
    } else {
        echo json_encode(['error' => 'No data to update']);
    }
}

function deleteUser() {
    global $pdo;
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['message' => 'User deleted']);
    } else {
        echo json_encode(['error' => 'Failed to delete user']);
    }
}

function authUser() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $login = $data['login'];
    $password = $data['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        echo json_encode(['message' => 'Authorization successful', 'user_id' => $user['id']]);
    } else {
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
?>
