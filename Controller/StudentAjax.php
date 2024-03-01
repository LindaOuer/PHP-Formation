<?php

include "../Model/Student.php";

try {
    $pdo =  new PDO(
        'mysql:host=localhost;dbname=PHPFormationTest',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Handle database connection errors
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
}

if ($_GET["action"] === "fetchData") {


    $sql = "SELECT * FROM Student";

    $stmt = $pdo->query($sql);

    // Fetch all rows as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


    header('Content-Type: application/json');
    echo json_encode([
        "data" => $data
    ]);
}

if ($_GET["action"] === "insertData") {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && $_FILES["image"]["size"] != 0) {
        // rename the image before saving to database
        $original_name = $_FILES["image"]["name"];
        $new_name = uniqid() . time() . "." . pathinfo($original_name, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../View/images/uploads/" . $new_name);
        $sql = "INSERT INTO Student  
        VALUES (NULL, :fn,:ln, :i)";
        $query = $pdo->prepare($sql);
        $result = $query->execute([
            'fn' => $_POST["first_name"],
            'ln' => $_POST["last_name"],
            'i' => $new_name
        ]);
        if ($result) {
            echo json_encode([
                "statusCode" => 200,
                "message" => "Data inserted successfully 😀"
            ]);
        } else {
            echo json_encode([
                "statusCode" => 500,
                "message" => "Failed to insert data 😓"
            ]);
        }
    } else {
        echo json_encode([
            "statusCode" => 400,
            "message" => "Please fill all the required fields 🙏"
        ]);
    }
}

// fetch data of individual user for edit form
if ($_GET["action"] === "fetchSingle") {
    $id = $_POST["id"];
    $sql = "SELECT * FROM Student WHERE `Id`=$id";

    $stmt = $pdo->query($sql);

    // Fetch all rows as an associative array
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        header("Content-Type: application/json");
        echo json_encode([
            "statusCode" => 200,
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "statusCode" => 404,
            "message" => "No student found with this id 😓"
        ]);
    }
}


if ($_GET["action"] === "updateData") {
    $id = $_POST["id"];
    if ($_FILES["image"]["size"] != 0) {
        // rename the image before saving to database
        $original_name = $_FILES["image"]["name"];
        $new_name = uniqid() . time() . "." . pathinfo($original_name, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../View/images/uploads/" . $new_name);
        // remove the old image from uploads directory
        unlink("../View/images/uploads/" . $_POST["image_old"]);
    } else {
        $new_name = $_POST["image_old"];
    }
    $sql = "UPDATE Student  SET  firstName = :fn, lastName = :ln, Image = :i  WHERE Id = :id";
    $query = $pdo->prepare($sql);
    $result = $query->execute([
        'fn' => $_POST["first_name"],
        'ln' => $_POST["last_name"],
        'i' => $new_name,
        'id' => $id
    ]);
    if ($result) {
        echo json_encode([
            "statusCode" => 200,
            "message" => "Data updated successfully 😀",
            "result" => $id
        ]);
    } else {
        echo json_encode([
            "statusCode" => 500,
            "message" => "Failed to update data 😓"
        ]);
    }
}

// function to delete data
if ($_GET["action"] === "deleteData") {
    $id = $_POST["id"];
    $delete_image = $_POST["delete_image"];

    $sql = "DELETE FROM Student WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);

    if ($stmt->rowCount() > 0) {
        // remove the image
        unlink("uploads/" . $delete_image);
        echo json_encode([
            "statusCode" => 200,
            "message" => "Data deleted successfully 😀"
        ]);
    } else {
        echo json_encode([
            "statusCode" => 500,
            "message" => "Failed to delete data 😓"
        ]);
    }
}
