<?php

session_start();
require_once('./api/db.inc.php');

$logged = false;
if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $logged = true;
}

if(!$logged) {
    header('Location: ./login');
    exit();
} else {
    $conn = @new mysqli($host, $db_user, $db_password, $db_name);

    // GET TODOS & CATEGORIES
    $user_email = $_SESSION['user_email'];
    $get_data = $conn->query("SELECT * FROM todos WHERE email = '$user_email'");

    if($get_data->num_rows > 0) {
        $user_data = $get_data->fetch_assoc();
        $user_todos = json_decode($user_data["todos_json"]);
        $user_categories = json_decode($user_data["categories_json"]);
    } else {
        $user_todos = array(
            "todos" => array()
        );
        $user_categories = array(
            "categories" => array()
        );
    }

    $user_todos_json = json_encode($user_todos);
    $user_categories_json = json_encode($user_categories);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="head">
        <left>
            <name>ToDo!</name>
        </left>
        <centerr>
            <div class="categories">

            </div>
            <addf onclick="addCategory(this);"><t>+</t></addf>
        </centerr>
        <right>
            <login onclick="redirect('./account');"><?php if($logged) { ?>Account<?php } else { ?>Login<?php } ?></login>
        </right>
    </header>  
    <content class="content">
        <div class="items">

        </div>

        <add onclick="addTodo();"><cnt><plus>+</plus><x>Add new item</x></cnt></add>
    </content>
    <script>
        const jsonTodos = `<?php echo $user_todos_json; ?>`;
        const user_todos = JSON.parse(jsonTodos);
        const jsonCategories = `<?php echo $user_categories_json; ?>`
        const user_categories  = JSON.parse(jsonCategories);
        var selected_category;
    </script>
    <script src="assets/todo_list.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>