<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <link rel="stylesheet"
                              href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.4/css/froala_editor.pkgd.min.css"/>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.4/js/froala_editor.pkgd.min.js"></script>
                        <h5 class="title">Create Page</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            $content["name"] = $_POST["content_name"];
                            $content["slug"] = $_POST["content_slug"];
                            $content["text"] = $_POST["content_text"];
                            $content["meta"] = $_POST["content_description"];
                            $content["type"] = 0;
                            if (database::slug_exists($content["slug"]) === 0) {
                                database::create_content($content);
                                echo '<br><p class="alert alert-success">Page created.</p>';
                            } else {
                                echo '<br><p class="alert alert-warning">Slug exists. Try with different.</p>';
                            }
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="content_name">Page Name</label>
                                <input class="form-control" type="text" name="content_name" id="content_name" required>
                                <label for="content_slug">Page Slug</label>
                                <input class="form-control" type="text" name="content_slug" id="content_slug"
                                       required>
                                <label for="content_description">Meta Description</label>
                                <input class="form-control" type="text" name="content_description"
                                       id="content_description">
                            </div>
                            <div class="form-group">
                                <label for="content_text">Page Content</label>
                                <textarea name="content_text"
                                          id="content_text"></textarea>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-info btn-fill btn-wd">Save</button>
                            </div>
                        </form>
                        <script>
                            $('textarea').froalaEditor();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>