<?php
require "../../db/connection.php";

// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 20;

// Prepare the SQL statement and get records from our texts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM aboutme ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$texts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of texts, this is so we can determine whether there should be a next and previous button
$num_texts = $pdo->query('SELECT COUNT(*) FROM aboutme')->fetchColumn();
?>


<div class="content read">
<?php
require_once "../../navbars/navbaradmin.php";
?>
	<h2>Texts</h2>
    <button onclick="window.location.href='./create.php'" class="create-text btn btn-primary m-3">Create Text</button>
	<table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Filename</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($texts as $text): ?>
            <tr>
                <td><?=$text['id']?></td>
                <td><?=$text['description']?></td>
                <td><?=$text['filename']?></td>
                <td class="actions">
                <?php echo "<a href=\"update.php?id={$text['id']}\" class=\"edit\"><button type='button' class='btn btn-primary m-4'>Edit</button></a>"?>
                <?php echo "<a href=\"delete.php?id={$text['id']}\" class=\"trash\"><button type='button' class='btn btn-primary m-4'>Delete</button> </a>"?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_texts): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>
<script src="https://use.fontawesome.com/62e43a72a9.js%22%3E"></script>