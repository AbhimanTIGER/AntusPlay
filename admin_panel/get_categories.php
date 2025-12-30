<?php
include 'db.php';

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    // අකුරු ගැලපෙන ඒවා 10ක් ගන්න
    $sql = "SELECT category_name FROM categories WHERE category_name LIKE '%$search%' LIMIT 10";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = htmlspecialchars($row['category_name']);
            // මෙන්න මේ div එක තමයි Suggestion එක විදිහට යන්නේ
            echo "<div class='suggestion-item' onclick='selectCategory(\"$name\")'>$name</div>";
        }
    } else {
        echo "<div class='suggestion-item' style='color:red;'>No matches found</div>";
    }
}
?>