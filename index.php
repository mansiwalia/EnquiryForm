<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "student_enquiry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['Student_Name'];
    $email = $_POST['Student_email'];
    $date_of_birth = $_POST['Student_date'];
    $phone = $_POST['Student_phone'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $academic_status = $_POST['academic_status'];
    $current_school_10th = $_POST['current_school_10th'] ?? null;
    $current_class_10th = $_POST['current_class_10th'] ?? null;
    $passed_10th_institute = $_POST['passed_10th_institute'] ?? null;
    $passed_10th_marks = $_POST['marks_10th'] ?? null;
    $passed_10th_year = $_POST['year_10th'] ?? null;
    $current_school_12th = $_POST['current_school_12th'] ?? null;
    $current_class_12th = $_POST['current_class_12th'] ?? null;


    // Insert student information
    $sql = "INSERT INTO student (student_name, email, date_of_birth, phone, gender, address, academic_status, 
            current_school_10th, current_class_10th, passed_10th_institute, passed_10th_marks, passed_10th_year, 
            current_school_12th, current_class_12th) 
            VALUES ('$student_name', '$email', '$date_of_birth', '$phone', '$gender', '$address', '$academic_status',
            '$current_school_10th', '$current_class_10th', '$passed_10th_institute', '$passed_10th_marks', 
            '$passed_10th_year', '$current_school_12th', '$current_class_12th')";

    if ($conn->query($sql) === TRUE) {
        $student_id = $conn->insert_id; // Get the last inserted student's ID

        // Insert parent information
        $parent_data = [
            ['relation' => 'father', 'name' => $_POST['fathers_Name'], 'email' => $_POST['fathers_email'], 'phone' => $_POST['fathers_phone'], 'occupation' => $_POST['fathers_Occ']],
            ['relation' => 'mother', 'name' => $_POST['mothers_Name'], 'email' => $_POST['mothers_email'], 'phone' => $_POST['mothers_phone'], 'occupation' => $_POST['mothers_Occ']]
        ];

        foreach ($parent_data as $parent) {
            $sql = "INSERT INTO parent (student_id, relation, name, email, phone, occupation) 
                    VALUES ('$student_id', '{$parent['relation']}', '{$parent['name']}', '{$parent['email']}', '{$parent['phone']}', '{$parent['occupation']}')";
            $conn->query($sql);
        }

        // Insert sibling information
        $names = $_POST['sibling_name'] ?? [];
        $classes = $_POST['sibling_class'] ?? [];
        $institutes = $_POST['sibling_institute'] ?? [];
        $professions = $_POST['sibling_profession'] ?? [];
        $organizations = $_POST['sibling_organization'] ?? [];
        $designations = $_POST['sibling_designation'] ?? [];

        for ($i = 0; $i < count($names); $i++) {
            $sql = "INSERT INTO siblings (student_id, sibling_name, sibling_class, sibling_institute, sibling_profession, sibling_organization, sibling_designation) 
                    VALUES ('$student_id', '{$names[$i]}', '{$classes[$i]}', '{$institutes[$i]}', '{$professions[$i]}', '{$organizations[$i]}', '{$designations[$i]}')";
            $conn->query($sql);
        }

        echo "Form submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enquiry Form</title>
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <!-- Bootstrap CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .form-check-inline {
            margin-right: 15px; /* Adjust spacing between radio buttons */
        }
        .hidden {
            display: none;
        }
        .sibling-row {
            margin-bottom: 15px; /* Space between sibling rows */
        }
    </style>
</head>
<body>

<div class="container-fluid bg-info border border-dark p-3">
    <h3 class="text-center text-uppercase font-italic fw-bold">Student Enquiry Form</h3>
    <p class="text-center bg-dark text-white py-2">STUDENT INFORMATION:-</p>
    
    <form action="" method="post">
        <div class="row mb-2">
            <div class="col-md-6">
                <label for="Student_Name" class="form-label">Student Name:</label>
                <input type="text" name="Student_Name" id="Student_Name" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="Student_email" class="form-label">Email-id:</label>
                <input type="email" name="Student_email" id="Student_email" class="form-control">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <label for="Student_date" class="form-label">Date Of Birth:</label>
                <input type="date" name="Student_date" id="Student_date" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="Student_phone" class="form-label">Phone No.:</label>
                <input type="text" name="Student_phone" id="Student_phone" class="form-control">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label class="form-label">Gender : </label>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" class="form-check-input" value="male" id="male">
                    <label for="male" class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" class="form-check-input" value="female" id="female">
                    <label for="female" class="form-check-label">Female</label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control">
            </div>
        </div>

        <hr style="border: 0; height: 2px; background-color: #000; margin: 20px 0;">

        <!-- Academic Information -->
        <p class="text-center bg-dark text-white mx-0 py-2">ACADEMIC INFORMATION:-</p>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Current Academic Status:</label>
                <div class="form-check form-check-inline">
                    <input type="radio" name="academic_status" class="form-check-input" value="10th" id="status_10th" onclick="showFields()">
                    <label for="status_10th" class="form-check-label">Currently in 10th</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="academic_status" class="form-check-input" value="passed_10th" id="status_passed_10th" onclick="showFields()">
                    <label for="status_passed_10th" class="form-check-label">Passed 10th</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="academic_status" class="form-check-input" value="12th" id="status_12th" onclick="showFields()">
                    <label for="status_12th" class="form-check-label">Currently in 12th</label>
                </div>
            </div>
        </div>

        <!-- Fields for currently in 10th -->
        <div id="current_10th" class="hidden">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="current_school_10th" class="form-label">Current School/College:</label>
                    <input type="text" name="current_school_10th" id="current_school_10th" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="current_class_10th" class="form-label">Class:</label>
                    <input type="text" name="current_class_10th" id="current_class_10th" class="form-control">
                </div>
            </div>
        </div>

        <!-- Fields for passed out 10th -->
        <div id="passed_10th" class="hidden">
            <div class="table-responsive mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Institute</th>
                            <th>Marks Obtained</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="level_10th" class="form-control" placeholder="10th"></td>
                            <td><input type="text" name="institute_10th" class="form-control"></td>
                            <td><input type="text" name="marks_10th" class="form-control"></td>
                            <td><input type="text" name="year_10th" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Fields for currently in 12th -->
        <div id="current_12th" class="hidden">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="current_school_12th" class="form-label">Current School/College:</label>
                    <input type="text" name="current_school_12th" id="current_school_12th" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="current_class_12th" class="form-label">Class:</label>
                    <input type="text" name="current_class_12th" id="current_class_12th" class="form-control">
                </div>
            </div>
        </div>

        <hr style="border: 0; height: 2px; background-color: #000; margin: 20px 0;">
        <p class="text-center bg-dark text-white mx-0 py-2">PARENT'S INFORMATION:-</p>

        <!-- Father -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Father's Name :</label>
                <input type="text" name="fathers_Name" id="fathers_Name" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">Email-id :</label>
                <input type="text" name="fathers_email" id="fathers_email" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Phone No. :</label>
                <input type="text" name="fathers_phone" id="fathers_phone" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">Occupation :</label>
                <input type="text" name="fathers_Occ" id="fathers_Occ" class="form-control">
            </div>
        </div>

        <!-- Mother -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Mother's Name :</label>
                <input type="text" name="mothers_Name" id="mothers_Name" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">Email-id :</label>
                <input type="text" name="mothers_email" id="mothers_email" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Phone No. :</label>
                <input type="text" name="mothers_phone" id="mothers_phone" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">Occupation :</label>
                <input type="text" name="mothers_Occ" id="mothers_Occ" class="form-control">
            </div>
        </div>

        <hr style="border: 0; height: 2px; background-color: #000; margin: 20px 0;">

        <!-- Sibling Information -->
        <p class="text-center bg-dark text-white mx-0 py-2">SIBLING'S INFORMATION:</p>

        <div id="sibling-info">
            <div class="sibling-row">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="sibling_name[]" class="form-label">Sibling Name:</label>
                        <input type="text" name="sibling_name[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="sibling_class[]" class="form-label">Class:</label>
                        <input type="text" name="sibling_class[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="sibling_institute[]" class="form-label">Institute:</label>
                        <input type="text" name="sibling_institute[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="sibling_profession[]" class="form-label">Profession:</label>
                        <input type="text" name="sibling_profession[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="sibling_organization[]" class="form-label">Organization:</label>
                        <input type="text" name="sibling_organization[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="sibling_designation[]" class="form-label">Designation:</label>
                        <input type="text" name="sibling_designation[]" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="addSibling()">Add Another Sibling</button>
        <button type="submit" class="btn btn-success">Submit</button>
        
        
    </form>
</div>

<script>
    function showFields() {
        var status10th = document.getElementById("status_10th").checked;
        var statusPassed10th = document.getElementById("status_passed_10th").checked;
        var status12th = document.getElementById("status_12th").checked;

        document.getElementById("current_10th").classList.toggle("hidden", !status10th);
        document.getElementById("passed_10th").classList.toggle("hidden", !statusPassed10th);
        document.getElementById("current_12th").classList.toggle("hidden", !status12th);
    }

    function addSibling() {
        const siblingContainer = document.getElementById('sibling-info');
        const siblingRow = document.createElement('div');
        siblingRow.className = 'sibling-row';

        siblingRow.innerHTML = `
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="sibling_name[]" class="form-label">Sibling Name:</label>
                    <input type="text" name="sibling_name[]" class="form-control">         
                </div>
                <div class="col-md-3">
                    <label for="sibling_class[]" class="form-label">Class:</label>
                    <input type="text" name="sibling_class[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="sibling_institute[]" class="form-label">Institute:</label>
                    <input type="text" name="sibling_institute[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="sibling_profession[]" class="form-label">Profession:</label>
                    <input type="text" name="sibling_profession[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="sibling_organization[]" class="form-label">Organization:</label>
                    <input type="text" name="sibling_organization[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="sibling_designation[]" class="form-label">Designation:</label>
                    <input type="text" name="sibling_designation[]" class="form-control">
                </div>
            </div>
        `;
        siblingContainer.appendChild(siblingRow);
    }
</script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXlYlRDeIIc+KfI2ntbT3f75+pZTFnpGIj5i/iOVGTG+8ZZ/Itp7nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGcuNwtI7pZvDZ+MlP5Q6w5xg65ft/qZNttHvWv4BvoEP1FAdVRP8GigiZo" crossorigin="anonymous"></script>
</body>
</html>
