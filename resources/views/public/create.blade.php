

<div id="createPublicStructure" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Public Structure
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('public')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="selectedRegion" 
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                    required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 1</label>
                                <select name="public_structure_category_id1"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 2</label>
                                <select name="public_structure_category_id2"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 3</label>
                                <select name="public_structure_category_id3"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        
                    </div> 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>

               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
   
 

</script>