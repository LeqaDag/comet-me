<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="communityDetails" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="communityModalTitle"></span> Details
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
                            <span class="spanDetails" id="englishNameCommunity">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of Compounds: 
                            <span class="spanDetails" id="numberOfCompoundsCommunity">
                               
                            </span>
                        </h6> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Arabic Name: 
                            <span class="spanDetails" id="arabicNameCommunity">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of Households: 
                            <span class="spanDetails" id="numberOfPeopleCommunity">
                             
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Region Name: 
                            <span class="spanDetails" id="englishNameRegion">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of People: 
                            <span class="spanDetails" id="numberOfHouseholdCommunity">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Sub Region Name: 
                            <span class="spanDetails" id="englishNameSubRegion">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community Status: 
                            <span class="spanDetails" id="statusCommunity">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Community Representative</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4" >
                        <span class="spanDetails" id="communityRepresentative">
                            
                        </span>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4" >
                        <span class="spanDetails" id="representativeRole">
                            
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Service Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Energy Service: 
                            <span class="spanDetails" id="energyServiceCommunity">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Energy Service Beginning Year: 
                            <span class="spanDetails" id="energyServiceYearCommunity">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Service: 
                            <span class="spanDetails" id="waterServiceCommunity">
                             
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Service Beginning Year: 
                            <span class="spanDetails" id="waterServiceYearCommunity">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Sources: 
                            <span class="spanDetails" id="waterSourcesCommunity">
                             
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Internet Service: 
                            <span class="spanDetails" id="internetServiceCommunity">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Internet Service Beginning Year: 
                            <span class="spanDetails" id="internetServiceYearCommunity">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Public Structures Details</h5>
                </div>
              
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6" >
                        <span class="spanDetails" id="structuresCommunity">
                            
                        </span>
                    </div>
                </div>
                <hr>

                <div id="secondNameDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <h5>Second Name</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                English Name: 
                                <span class="spanDetails" id="secondNameEnglish">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Arabic Name: 
                                <span class="spanDetails" id="secondNameArabic">
                                
                                </span>
                            </h6> 
                        </div>
                    </div> <hr>
                </div>

               

                <div class="row">
                    <h5>Compounds Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6" >
                        <span class="spanDetails" id="compoundsCommunity">
                            
                        </span>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h5>Nearby Towns Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6" >
                        <span class="spanDetails" id="townsCommunity">
                            
                        </span>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h5>Nearby Settlements Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6" >
                        <span class="spanDetails" id="settlementsCommunity">
                            
                        </span>
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