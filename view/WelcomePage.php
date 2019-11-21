<?php
require_once('partials/header.php');
?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Welcome Page</h1>
            <h3 CLASS="text-center">Test Projet - Vanila PHP</h3>

            <div class="text-center mt-5">
                <form action="../index.php?uri=propery-list-from-api" method="post">
                    <button type="submit" class="btn btn-primary">Populate the database with the Properties (External
                        Source) *
                    </button>
                </form>
                <form action="../index.php?uri=admin-panel" method="post">
                    <button type="submit" class="btn btn-info">Admin Panel</button>
                </form
            </div>
            <div class="text-center mt-3">
                <h6 style="font-style: italic;">
                    * This script updates the details in the database if any changes are made to the details of the
                    property
                    in the API. Internal entries which are in the same table will not be affected by this update
                    process.
                </h6>

            </div>

        </div>
    </div>
</div>


<?php
require_once('partials/footer.php');
?>
