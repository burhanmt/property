<?php

require_once('partials/header.php');
?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">The Properties from External API Source</h1>
            <h5 class="text-center">The script has populated the database from the external API
                source. I did not implement pagination feature  due to time limitation of the
                test
                project. This script updates the details in the database if any changes are made to the details of the
                property
                in the API. Internal entries which are in the same table  will not be affected by this update process. (It was a little bit challenge for me) </h5>
            <div class="text-left mb-2">
                <form action="../index.php" method="get">
                    <button type="submit" class="btn btn-warning">< Back</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-dark">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">County</th>
                        <th scope="col">Country</th>
                        <th scope="col">Town</th>
                        <th scope="col">Description</th>
                        <th scope="col">Image URL</th>
                        <th scope="col">Thumbnail URL</th>
                        <th scope="col">Latitude</th>
                        <th scope="col">Longitude</th>
                        <th scope="col">Number of bedrooms</th>
                        <th scope="col">Number of bathrooms</th>
                        <th scope="col">Price</th>
                        <th scope="col">Property Type</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $properties = App\models\Properties::populateDatabaseFromApi();


                    $i = 0;
                    foreach ($properties as $property) {
                        $i++;

                        echo '<tr>';
                        echo "<td>{$i}</td>";
                        echo "<td>{$property['county']}</td>";
                        echo "<td>{$property['country']}</td>";
                        echo "<td>{$property['town']}</td>";
                        echo "<td>{$property['description']}</td>";
                        echo "<td>{$property['image_full']}</td>";
                        echo "<td>{$property['image_thumbnail']}</td>";
                        echo "<td>{$property['latitude']}</td>";
                        echo "<td>{$property['longitude']}</td>";
                        echo "<td>{$property['num_bedrooms']}</td>";
                        echo "<td>{$property['num_bathrooms']}</td>";
                        echo "<td>{$property['price']}</td>";
                        echo "<td>{$property['type']}</td>";
                        echo '</tr>';


                    }
                    ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
require_once('partials/footer.php');
?>
