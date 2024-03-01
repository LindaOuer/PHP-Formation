<?php
include '../Controller/StudentC.php';
$studentC = new StudentC();

$error = "";

// create student
$student = null;

if (
    isset($_POST["first_name"]) &&
    isset($_POST["last_name"])
) {
    if (
        !empty($_POST['first_name']) &&
        !empty($_POST["last_name"])
    ) {


        if ($_FILES["image"]["size"] != 0) {
            // rename the image before saving to database
            $original_name = $_FILES["image"]["name"];
            $new_name = uniqid() . time() . "." . pathinfo($original_name, PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["image"]["tmp_name"], "./images/uploads/" . $new_name);
            // remove the old image from uploads directory
            unlink("./images/uploads/" . $_POST["image_old"]);
        } else {
            $new_name = $_POST["image_old"];
        }

        $student = new Student(
            null,
            $_POST['last_name'],
            $_POST['first_name'],
            $new_name
        );
        echo $_GET['id'];
        $studentC->updateStudent($student, $_GET['id']);
        header('Location:ListStudent.php');
    } else
        $error = "Missing information";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Formation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Font Awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Datatables CSS  -->
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet" />
    <!-- CSS  -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php
    // Include the header content
    include 'Header.php';
    ?>

    <div class="container">


        <?php
        if (isset($_GET['id'])) {
            $student = $studentC->showStudent($_GET['id']);

        ?>


            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Update Student</h5>
            </div>
            <div class="modal-body">
                <div id="error">
                    <?php echo $error; ?>
                </div>
                <form method="POST" id="insertForm" action="" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="Your First Name Here" value="<?php echo $student['firstName']; ?>">
                        </div>
                        <div class="col">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Your Last Name Here" value="<?php echo $student['lastName']; ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="form-label">Upload Image</label>
                        <div class="col-2">
                            <img class="preview_img" src=<?php echo ("./images/uploads/" . $student['Image']); ?>>
                        </div>
                        <div class="col-10">
                            <div class="file-upload text-secondary">
                                <input type="file" class="image" name="image" accept="image/*">
                                <input type="hidden" class="image" name="image_old" value=<?php echo ($student['Image']); ?>>
                                <span class="fs-4 fw-2">Choose file...</span>
                                <span>or drag and drop file here</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary me-1" id="insertBtn">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        <?php
        }
        ?>



    </div>




    <!-- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Datatables  -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <!-- JS  -->
    <script>
        // function to display image before upload
        $("input.image").change(function() {
            var file = this.files[0];
            var url = URL.createObjectURL(file);
            $(this).closest(".row").find(".preview_img").attr("src", url);
        });
    </script>
</body>

</html>