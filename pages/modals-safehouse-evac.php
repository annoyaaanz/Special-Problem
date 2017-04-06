
<!-- MODAL FOR ADDING SAFE HOUSE -->
<div id="add-safehouse-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-width">
        <div class="modal-content panel panel-success">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" title="Exit">&times;</button>
                <h2 class="panel-title">Add a Safehouse</h2>
            </div>
            <div class="modal-body panel-body">
                <div class="table" id="safehouses"></div>
            </div>
        </div>
    </div>
</div> <!-- MODAL -->
<!-- MODAL FOR ADDING SAFE HOUSE -->
<!-- MODAL -->
<!-- MODAL FOR ADDING EVACUATION CENTERS -->
<div id="add-evac-center-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-width">
        <div class="modal-content panel panel-success">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" title="Exit">&times;</button>
                <h2 class="panel-title">Add an Evacuation Center</h2>
            </div>
            <form role="form" class="form" method="post" action="#">
            <div class="modal-body panel-body">
                    <fieldset>
                        <div id="evac-map"></div>
                        <div class="form-group">
                            <label>Coordinates: </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" readonly name="latitude-evac" id="latitude-evac" value="" placeholder="Latitude" />
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" readonly name="longitude-evac" id="longitude-evac" value="" placeholder="Longitude" />
                        </div>
                        <div class="form-group">
                            <label>Capacity: </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="number" class="form-control" name="evac-capacity" id="evac-capacity" value="" placeholder="Capacity" />
                        </div>
                        <div class="col-lg-6 error-messages-div error-messages-transparent" id="error-messages-evac">
                        </div>
                        <div class="form-group">
                            <label>&nbsp; </label>
                        </div>
                        <input type="hidden" name="success-message" value="Successfully added the evacuation center."/>
                    </fieldset>            
            
            </div>
            <div class="modal-footer">
                <div class="form-group col-lg-4 col-lg-offset-4">
                    <input type="submit" class="form-control btn btn-outline btn-success" name="submit-evac" id="submit-evac" value="Add Evacuation Center"/>
                </div>
            </div>
            </form>
        </div>
    </div>
</div> <!-- MODAL -->