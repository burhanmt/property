<?php
require_once('partials/header.php');
?>


<div id="app">
    <!-- The Modal: Add new property -->
    <div class="modal" id="addPropertyModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Property</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" class="form-control" v-model="county" @input="$v.county.$touch()">
                        <small v-if="!$v.county.required" class="form-text text-danger">This field is required</small>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" v-model="country" @input="$v.country.$touch()">
                        <small v-if="!$v.country.required" class="form-text text-danger">This field is required</small>
                    </div>

                    <div class="form-group">
                        <label for="town">Town</label>
                        <input type="text" class="form-control" v-model="town" @input="$v.town.$touch()">
                        <small v-if="!$v.town.required" class="form-text text-danger">This field is required</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" v-model="description"></textarea>
                        <small class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                        <label for="bedrooms">Number of Bedrooms</label>
                        <select class="form-control" v-model="bedrooms">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bathrooms">Number of Bathrooms</label>
                        <select class="form-control" v-model="bathrooms">
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" v-model="price" @input="$v.price.$touch()">
                        <small v-if="!$v.price.required" class="form-text text-danger">This field is required</small>
                        <small v-if="!$v.price.numeric" class="form-text text-danger">This field must be numeric</small>
                    </div>


                    <div class="form-group">
                        <label for="propertyType">Property Type</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="propertyType" value="sale">
                        <label class="form-check-label">
                            Sale
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="propertyType" value="rent">
                        <label class="form-check-label">
                            Rent
                        </label>
                    </div>

                    <input type="hidden" id="token_" value="<?php
                    echo hash_hmac('sha256', 'AdminPanel.php', $_SESSION['csrf_token']);
                    ?>"/>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" v-bind:disabled="$v.$invalid"
                            @click="addToDb()">Add
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- The Modal: Edit  property -->
    <div class="modal" id="editPropertyModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Property</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="form-group">
                        <label for="countyEdit">County</label>
                        <input type="text" class="form-control" v-model="countyEdit">
                    </div>

                    <div class="form-group">
                        <label for="countryEdit">Country</label>
                        <input type="text" class="form-control" v-model="countryEdit">

                    </div>


                    <div class="form-group">
                        <label for="bedroomsEdit">Number of Bedrooms</label>
                        <select class="form-control" v-model="bedroomsEdit">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="propertyType">Property Type</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="propertyTypeEdit" value="sale">
                        <label class="form-check-label">
                            Sale
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="propertyTypeEdit" value="rent">
                        <label class="form-check-label">
                            Rent
                        </label>
                    </div>


                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <input type="hidden" class="form-control" v-model="uuid">
                    <button type="button" class="btn btn-primary"
                            @click="updateToDb()">Update
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-md-12 mt-5 mb-5">
                <div class="card text-black">
                    <div class="card-body">
                        <div class="text-left mb-2">
                            <form action="../index.php" method="get">
                                <button type="submit" class="btn btn-warning">< Back</button>
                            </form>
                        </div>
                        <h1 class="text-center">Admin Panel</h1>
                        <hr>
                        <div class="text-left mb-2">
                            <button type="button" class="btn btn-info" @click="showAddPropertyModal()"> Add New Record
                            </button>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-sm table-dark">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">County</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Town</th>
                                    <th scope="col">Image URL</th>
                                    <th scope="col">Thumbnail URL</th>
                                    <th scope="col">Number of bedrooms</th>
                                    <th scope="col">Number of bathrooms</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Property Type</th>
                                    <th scope="col">Source</th>
                                    <th scope="col">Actions</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $properties = Properties::showPropertiesFromDatabase();


                                $i      = 0;
                                $source = '';
                                foreach ($properties as $property) {
                                    $i++;
                                    ($property['source'] === 1) ? $source = '<div class="badge badge-primary">API</div>' : $source = '<div class="badge badge-primary">Internal</div>';
                                    echo '<tr id="row_' . $property['uuid'] . '">';
                                    echo "<td>{$i}</td>";
                                    echo "<td id='county_{$property['uuid']}'>{$property['county']}</td>";
                                    echo "<td id='country_{$property['uuid']}'>{$property['country']}</td>";
                                    echo "<td id='town_{$property['uuid']}'>{$property['town']}</td>";
                                    echo "<td id='image_full_{$property['uuid']}'>{$property['image_full']}</td>";
                                    echo "<td id='image_thumbnail_{$property['uuid']}'>{$property['image_thumbnail']}</td>";
                                    echo "<td id='num_bedrooms_{$property['uuid']}'>{$property['num_bedrooms']}</td>";
                                    echo "<td id='num_bathrooms_{$property['uuid']}'>{$property['num_bathrooms']}</td>";
                                    echo "<td id='price_{$property['uuid']}'>{$property['price']}</td>";
                                    echo "<td id='type_{$property['uuid']}'>{$property['type']}</td>";
                                    echo "<td> {$source}</td>";
                                    echo '<td><button type="button" class="btn btn-primary" @click="deleteRecord(\'' . $property['uuid'] . '\')">Del</button> </td>';
                                    echo '<td><button type="button" class="btn btn-secondary" @click="showEditModal(\'' . $property['uuid'] . '\')">Update</button> </td>';
                                    echo '</tr>';

                                }
                                ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- development version, includes helpful console warnings -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="/assets/js/vuelidate.min.js"></script>
<script src="/assets/js/validators.min.js"></script>

<script type="text/javascript">


    Vue.use(window.vuelidate.default);
    const {required, numeric} = window.validators;


    var app = new Vue({
        el: '#app',
        data: {
            county: '',
            country: '',
            town: '',
            description: '',
            image: '',
            bedrooms: '1',
            bathrooms: '1',
            price: '',
            propertyType: 'sale',
            uuid: '',
            countyEdit: '',
            countryEdit: '',
            bedroomsEdit: '',
            propertyTypeEdit: '',

        },
        validations: {
            county: {
                required: required
            },
            country: {
                required: required
            },
            town: {
                required: required
            },
            price: {
                required: required,
                numeric: numeric
            }
        },
        methods: {

            resetFields: function () {
                this.county = '';
                this.country = '';
                this.town = '';
                this.description = '';
                this.image = '';
                this.bedrooms = '1';
                this.bathrooms = '1';
                this.price = '';
                this.propertyType = 'sale';

                this.bedroomsEdit = '';
                this.countyEdit = '';
                this.countryEdit = '';
                this.propertyTypeEdit = '';
            },

            showAddPropertyModal: function () {

                this.resetFields();
                $('#addPropertyModal').modal('show');

            },
            updateToDb: function () {

                /*This is mandatory to use in closure, because of the scope of the Jquery AJAX */
                var vm = app;

                $.ajax({
                    type: 'POST',
                    url: 'index.php?uri=update-property',
                    data: {
                        token_: $('#token_').val(),
                        id: this.uuid,
                        county: this.countyEdit,
                        country: this.countryEdit,
                        num_bedrooms: this.bedroomsEdit,
                        type: this.propertyTypeEdit
                    },
                    dataType: 'json',

                    success: function () {

                        $('#county_' + vm.uuid).text(vm.countyEdit);
                        $('#country_' + vm.uuid).text(vm.countryEdit);
                        $('#num_bedrooms_' + vm.uuid).text(vm.bedroomsEdit);
                        $('#type_' + vm.uuid).text(vm.propertyTypeEdit);
                    },
                    error: function (data) {
                        alert('Error!' + JSON.stringify(data));
                    }
                });

                $('#editPropertyModal').modal('hide');

            },

            showEditModal: function (id) {

                this.resetFields();

                /*This is mandatory to use in closure scope of the Jquery AJAX */
                var vm = app;

                $.ajax({
                    type: 'GET',
                    url: 'index.php?uri=get-property',
                    data: {
                        id: id
                    },
                    dataType: 'json',

                    success: function (data) {

                        vm.bedroomsEdit = data['num_bedrooms'];
                        vm.countyEdit = data['county'];
                        vm.countryEdit = data['country'];
                        vm.propertyTypeEdit = data['type'];
                        vm.uuid = data['uuid'];
                    },
                    error: function (data) {
                        alert('Error!' + JSON.stringify(data));
                    }
                });

                $('#editPropertyModal').modal('show');

            },

            deleteRecord: function (id) {

                $.ajax({
                    type: 'POST',
                    url: 'index.php?uri=delete-property',
                    data: {
                        token_: $('#token_').val(),
                        id: id
                    },
                    dataType: 'json',

                    success: function (data) {

                        $('#row_' + id).hide("slow");

                    },
                    error: function (data) {
                        alert('Error!' + JSON.stringify(data));
                    }
                });
            },

            addToDb: function () {

                $.ajax({
                    type: 'POST',
                    url: 'index.php?uri=add-property',
                    data: {
                        data: {
                            county: this.county,
                            country: this.country,
                            town: this.town,
                            description: this.description,
                            image: '',
                            bedrooms: this.bedrooms,
                            bathrooms: this.bathrooms,
                            price: this.price,
                            propertyType: this.propertyType,
                            token_: $('#token_').val()
                        }

                    },
                    dataType: 'json',

                    beforeSend: function () {

                    },
                    success: function (data) {


                        $('#addPropertyModal').modal('hide');
                        window.location = window.location;

                    },
                    error: function (data) {
                        alert(JSON.stringify(data));
                    }
                });
            }
        }
    });

</script>
<?php
require_once('partials/footer.php');
?>
