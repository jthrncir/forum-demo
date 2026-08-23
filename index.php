<?php

require 'db.php';

// Handle a submitted post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title !== '' && $content !== '') {
        $stmt = $conn->prepare(
            "INSERT INTO posts (title, content)
             VALUES (?, ?)"
        );

        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        $stmt->close();

        // Prevent duplicate submission if the page is refreshed
        header("Location: index.php");
        exit;
    }
}

// Load all existing posts
$result = $conn->query(
    "SELECT id, title, content, created_at
     FROM posts
     ORDER BY created_at DESC"
);

if (!$result) {
    die('Query failed: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Forum</title>
</head>
<body>

    <h1>Simple Forum - Modified by WH</h1>

    <h2>Create a Post</h2>

    <form method="post">
        <p>
            <label for="title">Title:</label><br>
            <input
                type="text"
                id="title"
                name="title"
                maxlength="255"
                required
            >
        </p>

        <p>
            <label for="content">Content:</label><br>
            <textarea
                id="content"
                name="content"
                rows="6"
                cols="60"
                required
            ></textarea>
        </p>

        <button type="submit">Post</button>
    </form>

    <hr>

    <h2>Posts</h2>

    <?php if ($result->num_rows === 0): ?>

        <p>No posts yet.</p>

    <?php else: ?>

        <?php while ($post = $result->fetch_assoc()): ?>

            <article>
                <h3>
                    <?= htmlspecialchars($post['title']) ?>
                </h3>

                <p>
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </p>

                <small>
                    Posted <?= date(
                        'F j, Y g:i A',
                        strtotime($post['created_at'])
                    ) ?>
                </small>
            </article>

            <hr>

        <?php endwhile; ?>

    <?php endif; ?>

</body>
</html>