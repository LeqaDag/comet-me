<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="householdDetails" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="householdModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h5>General Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            English Name: 
                            <span class="spanDetails" id="englishNameHousehold">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Arabic Name: 
                            <span class="spanDetails" id="arabicNameHousehold">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community: 
                            <span class="spanDetails" id="communityHousehold">
                             
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Profession: 
                            <span class="spanDetails" id="professionHousehold">
                             
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Phone Number: 
                            <span class="spanDetails" id="phoneNumberHousehold">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Male : 
                            <span class="spanDetails" id="numberOfMaleHousehold">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Female :
                            <span class="spanDetails" id="numberOfFemaleHousehold">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Children :
                            <span class="spanDetails" id="numberOfChildrenHousehold">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Adults : 
                            <span class="spanDetails" id="numberOfAdultsHousehold">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Services Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Energy Service: 
                            <span class="spanDetails" id="energyServiceHousehold">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Energy Meter: 
                            <span class="spanDetails" id="energyMeterHousehold">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Energy Status: 
                            <span class="spanDetails" id="energyStatusHousehold">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Service: 
                            <span class="spanDetails" id="waterServiceHousehold">
                             
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Internet Service: 
                            <span class="spanDetails" id="internetServiceHousehold">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Door to door details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Herd Size: 
                            <span class="spanDetails" id="herdSize">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            # of Structures: 
                            <span class="spanDetails" id="numberOfStructures">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            # of kitchens: 
                            <span class="spanDetails" id="numberOfkitchens">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            # of Animal Shelters:  
                            <span class="spanDetails" id="numberOfShelters">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Is there house in town: 
                            <span class="spanDetails" id="houseInTown">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Is there Izbih?: 
                            <span class="spanDetails" id="izbih">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            How long: 
                            <span class="spanDetails" id="howLong">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Length of stay:  
                            <span class="spanDetails" id="lengthOfStay">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            # of Cisterns: 
                            <span class="spanDetails" id="numberOfCistern">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Cistern Volume: 
                            <span class="spanDetails" id="volumeCistern">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Cistern Depth: 
                            <span class="spanDetails" id="depthCistern">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Shared Cisterns: 
                            <span class="spanDetails" id="sharedCistern">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <h6>
                            Distance from house: 
                            <span class="spanDetails" id="distanceCistern">
                                
                            </span>
                        </h6>
                    </div>
                </div>
              
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>