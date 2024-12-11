<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    session_start(['cookie_httponly' => true]);
    session_regenerate_id();
    if (!isset($_SESSION["user"])) {
        header("Location:login.php");
    }
    $_SESSION["user"] = "test@make-it-all.co.uk";
    if ($_GET["error"] == "creation") {
        echo "<script defer>alert('Could not create task');</script>";
    } else if ($_GET["error"] == "deletion") {
        echo "<script defer>alert('Could not delete task');</script>";
    }
} else {
    /*header("Location : login.php");
    session_abort()f
    exit();*/
}

function displayTasks() {
    // begin server connection
    $server = "sci-project.lboro.ac.uk";
    $dbname = "team021";
    $uname = "team021";
    $pass = "RFoVurwKN3jNbqrUg9ni";

    $conn = mysqli_connect($server, $uname, $pass, $dbname);
    if (!$conn) {
        die("connection failed," . mysqli_connect_error);
    }

    $email = $_SESSION["user"];

    // GET ALL TASKS FOR CURRENT USER
    $sql = "select task.TaskID, task.Description, task.DueDate from `PersonalTask` as task, `Users` where Users.UserID = task.UserID and Users.email='".$email."'";

    $tasks_result = mysqli_query($conn, $sql);

    $html = "";

    if (mysqli_num_rows($tasks_result) > 0) {
        while ($row = mysqli_fetch_assoc($tasks_result)) {
            $html = $html.'
            <div class="spacer-small"></div>
                <div class="task-style">
                    <h2 class="task-name">'.$row["Description"].'</h1>
                    <form action="edit_personal_task.php?referrer=landing" method="post">
                        <input type="hidden" name="taskid" value="'.$row["TaskID"].'" />
                        <button type="submit" class="task-edit">
                            <svg width="25px" height="100%" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <path stroke-width="5" fill="#71717A" fill-rule="evenodd" d="M15.747 2.97a.864.864 0 011.177 1.265l-7.904 7.37-1.516.194.653-1.785 7.59-7.044zm2.639-1.366a2.864 2.864 0 00-4-.1L6.62 8.71a1 1 0 00-.26.39l-1.3 3.556a1 1 0 001.067 1.335l3.467-.445a1 1 0 00.555-.26l8.139-7.59a2.864 2.864 0 00.098-4.093zM3.1 3.007c0-.001 0-.003.002-.005A.013.013 0 013.106 3H8a1 1 0 100-2H3.108a2.009 2.009 0 00-2 2.19C1.256 4.814 1.5 7.848 1.5 10c0 2.153-.245 5.187-.391 6.81A2.009 2.009 0 003.108 19H17c1.103 0 2-.892 2-1.999V12a1 1 0 10-2 0v5H3.106l-.003-.002a.012.012 0 01-.002-.005v-.004c.146-1.62.399-4.735.399-6.989 0-2.254-.253-5.37-.4-6.99v-.003zM17 17c-.001 0 0 0 0 0zm0 0z"/>
                            </svg>
                        </button>
                    </form>
                    <form action="delete_personal_task.php?refferer=landing" method="post">
                    <input type="hidden" name="referrer" value="landing" />
                    <input type="hidden" name="taskid" value="'.$row["TaskID"].'" />
                        <button type="submit" class="task-delete">
                            <svg width="25px" height="100%" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <path fill="#71717A" fill-rule="evenodd" d="M7 1a2 2 0 00-2 2v2H2a1 1 0 000 2h.884c.036.338.078.754.12 1.213.11 1.202.218 2.664.218 3.787 0 1.47-.183 3.508-.315 4.776a2.015 2.015 0 002 2.224h10.186a2.015 2.015 0 002-2.224c-.132-1.268-.315-3.306-.315-4.776 0-1.123.107-2.585.218-3.787.042-.459.084-.875.12-1.213H18a1 1 0 100-2h-3V3a2 2 0 00-2-2H7zm6 4V3H7v2h6zM4.996 8.03c-.035-.378-.07-.728-.101-1.03h10.21a81.66 81.66 0 00-.1 1.03c-.112 1.212-.227 2.75-.227 3.97 0 1.584.194 3.714.325 4.982v.007a.02.02 0 01-.005.008l-.003.003H4.905a.024.024 0 01-.008-.01v-.008c.131-1.268.325-3.398.325-4.982 0-1.22-.115-2.758-.226-3.97zM8 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm5 1a1 1 0 10-2 0v6a1 1 0 102 0V9z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            ';
        }
    } else {
        $html = '<h1 text-align="center"> No Tasks Available </h1>';
    }

    return $html;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Document</title>
</head>
<body>
    <script>console.log(window.innerHeight);</script>
    <div class="container" id="container">
        <div class="left" id="left">
            <div class="welcome wrappers" id="welcome">
                <h1>Hello <?php echo $_SESSION["user"]; ?></h1>
                <h2>Today is <span>Wednesday <?php echo (date("d M")); ?></span></h2>
                <p>This is a sample motivational quote
written by some guy 300 years ago and
has been adapted by 15 different
accounts on Facebook
---------------------------------------Person A</p>
            </div>
            <div class="calendar-container wrappers" id="calendar-container"><p>Calendar<p></div>
        </div>
        <div class="right" id="right">
            <div class="header-container wrappers" id="header">
                <div class="header">
                    <a href="#Home">Home</a>
                    <div class="seperator"></div>
                    <a href="#Knowledge">Hub</a>
                    <div class="seperator"></div>
                    <a href="#Projects">Projects</a>
                    <div class="seperator"></div>
                    <a href="#Forum">Forum</a>
                    <div class="seperator"></div>
                    <a href="#Account">Account</a>
                </div>
                
            </div>
            <div class="personal-task-wrapper wrappers">
                <div class="spacer"></div>
                <form action="create_personal_task.php?refferer=landing" method="post" class="task-form task-style">
                    <input type="text" name="taskname" id="taskname" placeholder="Create a new personal task">
                    <input type="date" name="deadline" id="deadline">
                    <input type="submit" value="Create" id="submit">
                    <input type="hidden" name="referrer" value="landing" />
                </form>
                <div class="spacer-small"></div>
                <div class="task-style">
                    <h2 class="task-name">Sample personal task 1</h1>
                    <form action="edit_personal_task.php?refferer=landing" method="post">
                    <input type="hidden" name="taskid" value="1" />
                        <button type="submit" class="task-edit">
                            <svg width="25px" height="100%" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <path stroke-width="5" fill="#71717A" fill-rule="evenodd" d="M15.747 2.97a.864.864 0 011.177 1.265l-7.904 7.37-1.516.194.653-1.785 7.59-7.044zm2.639-1.366a2.864 2.864 0 00-4-.1L6.62 8.71a1 1 0 00-.26.39l-1.3 3.556a1 1 0 001.067 1.335l3.467-.445a1 1 0 00.555-.26l8.139-7.59a2.864 2.864 0 00.098-4.093zM3.1 3.007c0-.001 0-.003.002-.005A.013.013 0 013.106 3H8a1 1 0 100-2H3.108a2.009 2.009 0 00-2 2.19C1.256 4.814 1.5 7.848 1.5 10c0 2.153-.245 5.187-.391 6.81A2.009 2.009 0 003.108 19H17c1.103 0 2-.892 2-1.999V12a1 1 0 10-2 0v5H3.106l-.003-.002a.012.012 0 01-.002-.005v-.004c.146-1.62.399-4.735.399-6.989 0-2.254-.253-5.37-.4-6.99v-.003zM17 17c-.001 0 0 0 0 0zm0 0z"/>
                            </svg>
                        </button>
                    </form>
                    <form action="delete_personal_task.php?refferer=landing" method="post">
                    <input type="hidden" name="taskid" value="1" />
                        <button type="submit" class="task-delete">
                            <svg width="25px" height="100%" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <path fill="#71717A" fill-rule="evenodd" d="M7 1a2 2 0 00-2 2v2H2a1 1 0 000 2h.884c.036.338.078.754.12 1.213.11 1.202.218 2.664.218 3.787 0 1.47-.183 3.508-.315 4.776a2.015 2.015 0 002 2.224h10.186a2.015 2.015 0 002-2.224c-.132-1.268-.315-3.306-.315-4.776 0-1.123.107-2.585.218-3.787.042-.459.084-.875.12-1.213H18a1 1 0 100-2h-3V3a2 2 0 00-2-2H7zm6 4V3H7v2h6zM4.996 8.03c-.035-.378-.07-.728-.101-1.03h10.21a81.66 81.66 0 00-.1 1.03c-.112 1.212-.227 2.75-.227 3.97 0 1.584.194 3.714.325 4.982v.007a.02.02 0 01-.005.008l-.003.003H4.905a.024.024 0 01-.008-.01v-.008c.131-1.268.325-3.398.325-4.982 0-1.22-.115-2.758-.226-3.97zM8 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm5 1a1 1 0 10-2 0v6a1 1 0 102 0V9z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <?php echo displayTasks(); ?>
            </div>
        </div>
    </div>
</body>
</html>
// home, training, forums, projects, team members, account, CALENDAR BIG, personal todo tasks
