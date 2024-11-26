<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file
include_once __DIR__ . '/../config/database.php';

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Check if the database connection was successful
if (!$db) {
    echo json_encode(["message" => "Database connection failed."]);
    exit;
}

// Set HTTP headers for API requests
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Content-Type: application/json"); // Response content type
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE"); // Allowed HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Retrieve the HTTP method of the request
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Handle API requests based on the HTTP method
switch ($requestMethod) {
    case "GET":
        // Retrieve all contacts
        $query = "SELECT * FROM contacts";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($contacts); // Return the result as JSON
        break;

    case "POST":
        // Add a new contact
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data["name"]) && !empty($data["phone"])) {
            $query = "INSERT INTO contacts (name, phone) VALUES (:name, :phone)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":name", $data["name"]);
            $stmt->bindParam(":phone", $data["phone"]);
            $stmt->execute();
            echo json_encode(["message" => "Contact created"]);
        } else {
            echo json_encode(["message" => "Incomplete data"]);
        }
        break;

    case "PUT":
        // Update an existing contact
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data["id"]) && !empty($data["name"]) && !empty($data["phone"])) {
            $query = "UPDATE contacts SET name = :name, phone = :phone WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $data["id"]);
            $stmt->bindParam(":name", $data["name"]);
            $stmt->bindParam(":phone", $data["phone"]);
            $stmt->execute();
            echo json_encode(["message" => "Contact updated"]);
        } else {
            echo json_encode(["message" => "Incomplete data"]);
        }
        break;

    case "DELETE":
        // Delete an existing contact
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data["id"])) {
            $query = "DELETE FROM contacts WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $data["id"]);
            $stmt->execute();
            echo json_encode(["message" => "Contact deleted"]);
        } else {
            echo json_encode(["message" => "Incomplete data"]);
        }
        break;

    default:
        // Handle unsupported HTTP methods
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>
