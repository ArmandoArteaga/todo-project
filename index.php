<?php
    //* Set error handling for notifying user invalid input
    $html_error = "";
    $html_success = "";
    $i = 1;
    
    //*Establish database connection
    $db = mysqli_connect('localhost', 'root', '', "todolist");

    // TODO: refactor code to prevent SQL injections using PDO

    //! Error message if no established database connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
        echo ("Connection failed");
    }
    else {
        //echo "<h1>Connected sucessfully</h1>";
    }

    //? Query INSERT & Error handling
    if (isset($_POST['submit'])) {

        $task = $_POST['task'];
        
        //! Error is nothing is in the stack
        if(empty($task) or $task == NULL) {
            $html_error = '
            <div class="alert alert-danger" role="alert">
                Please type your task!
            </div>';
        }
        //! Otherwise take POST and query into database
        else {
            $sql_insert = "INSERT INTO tasks (task) VALUES ('$task')";
            if(mysqli_query($db, $sql_insert)) {
                echo '<div class="alert alert-success" role="alert">
                            Congrats! You entered a task! 
                        </div>';
            }
            else {
                echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
            }
        }
    }

    //? Query DELETE
    if (isset($_GET['del_task'])) {
        $id = $_GET['del_task'];
        $sql_delete = "DELETE FROM tasks WHERE id=$id";
        mysqli_query($db, $sql_delete);
    }

    //? Query data being brought from database
    $tasks = mysqli_query($db, "SELECT * FROM tasks");
?>

<!DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>To Do List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!--script src="main.js"></script-->
</head>
<body>

    <div class="container">
        <h2 class="text-center">To Do List Project</h2>
    </div>

    <form action="index.php" method="post">        
        <div class="container">
        <div class="form-group">

        <?php if(isset($html_error)) { ?>
            <p><?php echo $html_error; ?></p>
        <?php } ?>

        <div class="input-group">
            <input type="text" name="task" id="task" class="form-control" placeholder="Enter Task">
        <span class="input-group-btn">
            <button type="submit" name="submit" class="btn btn-primary">Add Task</button>
        </span>
        </div>
        </div>
        </div>
        
    </form>

    <div class="container">
    <table class="table">
    <thead class="thead-light">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Task</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($tasks)) { ?>
    <tr>
        <th scope="row">
            <?php echo $i; ?>
        </th>
        <td>
            <?php echo $row['task']; ?>
        </td>
        <td>
            <button type="button" class="btn btn-danger">
                <a href="index.php?del_task=<?php echo $row['id']; ?>">Delete</a>
            </button>
        </td>
    </tr>
    <?php $i++; } ?>  
    
    </tbody>
    </table>
    </div>

</body>
</html>