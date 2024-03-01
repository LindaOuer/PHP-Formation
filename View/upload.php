<?php
include '../Controller/StudentC.php';
$studentC = new StudentC();

$error = "";

// create student
$student = null;

if (
    isset($_POST["first_name"]) &&
    isset($_POST["last_name"]) &&
    isset($_FILES['image'])
) {
    if (
        !empty($_POST['first_name']) &&
        !empty($_POST["last_name"]) &&
        !empty($_FILES['image']['name'])
    ) {
        $original_name = $_FILES["image"]["name"];
        $new_name = uniqid() . time() . "." . pathinfo($original_name, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["image"]["tmp_name"], "./images/uploads/" . $new_name);


        $student = new Student(
            null,
            $_POST['first_name'],
            $_POST['last_name'],
            $new_name
        );
        $studentC->addStudent($student);
        header('Location:ListStudent.php');
    } else
        $error = "Missing information";
}
