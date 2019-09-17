<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Pages</h5>
                        <p class="category"><a href="?view=page-create">Create a new page</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        $pages_list = database::list_pages();
                        ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Page Name</th>
                                <th>Page Preview</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($pages_list as $page) {
                                echo "<tr>";
                                echo "<td>" . $page["content_name"] . "</td>";
                                echo "<td>" . substr($page["content_text"], 0, 160) . "</td>";
                                echo '<td>';
                                echo '<a target="_blank" class="btn btn-sm btn-info" href="'. $config["url"] .'/?page='. $page["content_slug"] . '"><i class="fas fa-link"></i></a>';
                                echo ' <a class="btn btn-sm btn-primary" href="?view=page-edit&id=' . $page["ID"] . '"><i class="fas fa-pencil-alt"></i></a>';
                                echo ' <a class="btn btn-sm btn-danger" href="?view=page-delete&id=' . $page["ID"] . '"><i class="fas fa-trash-alt"></i></a>';
                                echo '</td>';
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>