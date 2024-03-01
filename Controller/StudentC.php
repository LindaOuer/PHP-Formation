<?php
include '../config.php';
include '../Model/Student.php';

class StudentC
{
    public function listStudents()
    {
        $sql = "SELECT * FROM Student";
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);


            // Fetch all rows as an associative array
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function deleteStudent($id)
    {
        $sql = "DELETE FROM Student WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function addStudent($student)
    {
        $sql = "INSERT INTO Student  
        VALUES (NULL, :fn,:ln, :i)";
        $db = config::getConnexion();
        try {
            var_dump($student);
            $query = $db->prepare($sql);
            $query->execute([
                'fn' => $student->getFirstName(),
                'ln' => $student->getLastName(),
                'i' => $student->getImage()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updateStudent($student, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE Student SET 
                    firstName = :firstName, 
                    lastName = :lastName, 
                    image = :i
                WHERE id= :id'
            );
            $query->execute([
                'id' => $id,
                'firstName' => $student->getFirstName(),
                'lastName' => $student->getLastName(),
                'i' => $student->getImage(),
                'id' => $id
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    function showStudent($id)
    {
        $sql = "SELECT * from Student where id = $id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();

            $student = $query->fetch();
            return $student;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
