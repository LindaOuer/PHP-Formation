<?php
include '../Controller/StudentC.php';
$studentC = new StudentC();
$studentC->deleteStudent($_GET['id']);
header('Location:ListStudent.php');
