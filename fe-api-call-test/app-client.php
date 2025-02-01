<?php

// API Base URL
$api_url = "http://127.0.0.1:5000/my_table";

// Function to GET all messages
function getMessages() {
    global $api_url;
    $response = file_get_contents($api_url);
   $result = json_decode($response, true);
   if ($result) {
    echo "<h3>Messages:</h3>";
    echo "<ul>";
    foreach ($result as $message) {
        echo "<li>ID: " . htmlspecialchars($message['id']) . 
             " - Message: " . htmlspecialchars($message['data']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No messages found or error occurred";
}

return $result;

}

// Function to POST (Insert) a new message
function addMessage($data) {
    global $api_url;
    $data = json_encode(["data" => $data]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json",
            "method" => "POST",
            "content" => $data
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    return json_decode($response, true);
}

// Function to PATCH (Update) an existing message
function updateMessage($id, $data) {
    global $api_url;
    $data = json_encode($data);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json",
            "method" => "PATCH",
            "content" => $data
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents("$api_url/$id", false, $context);
    return json_decode($response, true);
}

// Function to DELETE a message
function deleteMessage($id) {
    global $api_url;

    $options = [
        "http" => [
            "method" => "DELETE"
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents("$api_url/$id", false, $context);
    return json_decode($response, true);
}

// TESTING THE FUNCTIONS
// echo "GET all messages:\n";
// print_r(getMessages());

// echo "\nPOST a new message:\n";
// print_r(addMessage("Hello from PHP!"));

// echo "\nPATCH (Update) message:\n";
// $update_id = "<script>var id = prompt('Enter ID to update:'); document.write(id);</script>";
// if ($update_id) {
//     print_r(updateMessage($update_id, "Updated from PHP"));
// }

// echo "\nDELETE message:\n";
// $delete_id = "<script>var id = prompt('Enter ID to delete:'); document.write(id);</script>";
// if ($delete_id) {
//     print_r(deleteMessage($delete_id));
// }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Message Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .operation { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Message Management System</h2>
        
        <div class="operation">
            <h3>View Messages</h3>
            <?php getMessages(); ?>
        </div>

        <div class="operation">
            <h3>Add New Message</h3>
            <input type="text" id="newMessage" placeholder="Enter new message">
            <button onclick="addNewMessage()">Add Message</button>
        </div>

        <div class="operation">
            <h3>Update Message</h3>
            <input type="number" id="updateId" placeholder="Enter message ID">
            <input type="text" id="updateMessage" placeholder="Enter updated message">
            <button onclick="updateExistingMessage()">Update Message</button>
        </div>

        <div class="operation">
            <h3>Delete Message</h3>
            <input type="number" id="deleteId" placeholder="Enter message ID">
            <button onclick="deleteExistingMessage()">Delete Message</button>
        </div>
    </div>

    <script>
        function addNewMessage() {
            const message = document.getElementById('newMessage').value;
            if (message) {
                window.location.href = `?action=add&message=${encodeURIComponent(message)}`;
            }
        }

        function updateExistingMessage() {
            const id = document.getElementById('updateId').value;
            const message = document.getElementById('updateMessage').value;
            if (id && message) {
                window.location.href = `?action=update&id=${id}&message=${encodeURIComponent(message)}`;
            }
        }

        function deleteExistingMessage() {
            const id = document.getElementById('deleteId').value;
            if (id) {
                if (confirm('Are you sure you want to delete this message?')) {
                    window.location.href = `?action=delete&id=${id}`;
                }
            }
        }
    </script>
</body>
</html>
<?php
// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            if (isset($_GET['message'])) {
                addMessage($_GET['message']);
            }
            break;
        case 'update':
            if (isset($_GET['id']) && isset($_GET['message'])) {
                updateMessage($_GET['id'], ["data" => $_GET['message']]);
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                deleteMessage($_GET['id']);
            }
            break;
    }
    // Redirect back to clear the URL
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}
