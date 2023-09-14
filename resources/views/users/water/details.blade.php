<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewWaterUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="WaterUserModalTitle"></span> Details
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
                            User Name: 
                            <span class="spanDetails" id="englishNameUser">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community: 
                            <span class="spanDetails" id="communityUser">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            # of People: 
                            <span class="spanDetails" id="holderPeople">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            # of Male: 
                            <span class="spanDetails" id="holderMale">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            # of Female: 
                            <span class="spanDetails" id="holderFemale">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            # of Adults: 
                            <span class="spanDetails" id="holderAdult">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            # of Children: 
                            <span class="spanDetails" id="holderChildren">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Main Holder: 
                            <span class="spanDetails" id="mainHolder">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div id="energyDetails">
                    <div class="row" >
                        <h5>Energy Service Details</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Energy Service: 
                                <span class="spanDetails" id="dataEnergyService">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Energy Date: 
                                <span class="spanDetails" id="dataEnergyDate">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Energy Meter: 
                                <span class="spanDetails" id="dataEnergyMeter">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Meter Number: 
                                <span class="spanDetails" id="dataMeterNumber">
                                    
                                </span>
                            </h6>
                        </div>
                    </div>
                </div>

                <hr>
                <div id="h2oDetails" style="visiblity:hidden; display:none">
                    <div class="row" >
                        <h5>Old H2O Details</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Request Date: 
                                <span class="spanDetails" id="dateH2oUser">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Installation Year: 
                                <span class="spanDetails" id="yearH2oUser">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Number of H2O: 
                                <span class="spanDetails" id="numberH2oUser">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                H2O Status: 
                                <span class="spanDetails" id="statusH2oUser">
                                    
                                </span>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Number of BSF: 
                                <span class="spanDetails" id="numberBsfUser">
                                
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                BSF Status: 
                                <span class="spanDetails" id="statusBsfUser">
                                
                                </span>
                            </h6>
                        </div>
                    </div>
                    <hr>
                </div>
               
                <div id="gridDetails" style="visiblity:hidden; display:none">
                    <div class="row">
                        <h5>Grid Integration Details</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Request Date: 
                                <span class="spanDetails" id="dateGridUser">
                                    
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Number of Grid Large: 
                                <span class="gridLargeNumber spanDetails" id="gridLargeNumber">
                                
                                </span>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Number of Grid Small: 
                                <span class="gridSmallNumber spanDetails" id="gridSmallNumber">
                                
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Grid Large Date: 
                                <span class="gridLargeDateNumber spanDetails" id="gridLargeDateNumber">
                                
                                </span>
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Grid Small Date: 
                                <span class="gridSmallDateNumber spanDetails" id="gridSmallDateNumber">
                                
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Delivery: 
                                <span class="spanDetails" id="gridDelivery">
                                
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Paid: 
                                <span class="spanDetails" id="gridPaid">
                                
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h6>
                                Complete: 
                                <span class="spanDetails" id="gridComplete">
                                
                                </span>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Donors: 
                            <div class="spanDetails" id="donorsDetailsWaterHolder">
                              
                            </div>
                        </h6>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Incident: 
                            <span class="spanDetails" id="incidentUser">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Incident Date: 
                            <span class="spanDetails" id="incidentDate">
                              
                            </span>
                        </h6>
                    </div>
                </div> 

                <div class="modal-footer">
                    <button id="closeDetailsModel" type="button" 
                        class="closeDetailsModel btn btn-secondary" 
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>