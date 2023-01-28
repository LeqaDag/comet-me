<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="communityDetails{{$community->id}}" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    {{$community->english_name}} Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4>General Details</h4>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            English Name: 
                            <span class="spanDetails">
                                {{$community->english_name}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of Compounds: 
                            <span class="spanDetails">
                                {{$community->number_of_compound}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Arabic Name: 
                            <span class="spanDetails">
                                {{$community->arabic_name}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of People: 
                            <span class="spanDetails">
                                {{$community->number_of_people}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Region Name: 
                            <span class="spanDetails">
                                {{$community->Region->english_name}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Number of Households: 
                            <span class="spanDetails">
                                {{$community->number_of_households}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Sub Region Name: 
                            <span class="spanDetails">
                                {{$community->SubRegion->english_name}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community Status: 
                            <span class="spanDetails">
                                {{$community->community_status}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <h4>Service Details</h4>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Energy Service: 
                            <span class="spanDetails">
                                {{$community->energy_service}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Energy Service Beginning Year: 
                            <span class="spanDetails">
                                {{$community->energy_service_beginning_year}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Service: 
                            <span class="spanDetails">
                                {{$community->water_service}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Water Service Beginning Year: 
                            <span class="spanDetails">
                                {{$community->water_service_beginning_year}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Internet Service: 
                            <span class="spanDetails">
                                {{$community->internet_service}}
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Internet Service Beginning Year: 
                            <span class="spanDetails">
                                {{$community->internet_service_beginning_year}}
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <h4>Public Structures Details</h4>
                </div>
                <div class="row">
                    <h4>Compounds Details</h4>
                </div>
                <div class="row">
                    <h4>Nearby Towns Details</h4>
                </div>
                <div class="row">
                    <h4>Nearby Settlements Details</h4>
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