<script type="text/javascript">
    $(function () {
        // Named routes / URLs used by handlers
        var deleteHouseholdUrl = "{{ route('deleteHousehold') }}";
        var moveMISCHouseholdUrl = "{{ route('moveMISCHousehold') }}";
        var moveMISCPublicUrl = "{{ route('moveMISCPublic') }}";
        var backMISCHouseholdUrl = "{{ route('backMISCHousehold') }}";
        var backMISCPublicUrl = "{{ route('backMISCPublic') }}";
        var notesMISCHouseholdUrl = "{{ route('notesMISCHousehold') }}";
        var notesMISCPublicUrl = "{{ route('notesMISCPublic') }}";

        function redrawAllTables() {
            var tables = ['.data-table-initial-households', '.data-table-ac-households', '.data-table-requested-households', '.data-table-confirmed-households', '.data-table-ac-completed-households', '.data-table-served-households', '.data-table-progress-households', '.data-table-misc-households'];
            tables.forEach(function(selector) {
                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().draw(false);
                }
            });
        }

        function getResourceBaseFromTable(tableId) {
            switch(tableId) {
                case 'initialHouseholdsTable': return 'initial-household';
                case 'acHouseholdsTable': return 'household';
                case 'requestedHouseholdsTable': return 'requested-household';
                case 'confirmedHouseholdsTable': return 'misc-household';
                case 'acCompletedHouseholdsTable': return 'progress-household';
                case 'servedHouseholdsTable': return 'served-household';
                case 'progressHouseholdsTable': return 'progress-household';
                default: return 'household';
            }
        }

        // Edit / Update handlers: open edit page for the correct resource
        $(document).on('click', '.updateHousehold, .updateAcHousehold', function() {
            var id = $(this).data('id');
            var tableId = $(this).closest('table').attr('id');
            var base = getResourceBaseFromTable(tableId);
            // request editpage then open edit route
            $.ajax({
                url: base + '/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open('/' + base + '/' + id + '/edit', '_self');
                },
                error: function() {
                    // fallback to household editpage
                    $.ajax({
                        url: 'household/' + id + '/editpage',
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            window.open('/household/' + id + '/edit', '_self');
                        }
                    });
                }
            });
        });

        // Details modal handler (uses household show endpoint)
        $(document).on('click', '.detailsHouseholdButton', function() {
            var id = $(this).data('id');

            $.ajax({
                url: 'household/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    // clear and populate modal fields (same IDs as other household detail blades)
                    $('#householdModalTitle').html(response['household'].english_name || '');
                    $('#englishNameHousehold').html(response['household'].english_name || '');
                    $('#arabicNameHousehold').html(response['household'].arabic_name || '');
                    $('#communityHousehold').html((response['community'] && response['community'].english_name) || '');
                    if(response['profession']) $('#professionHousehold').html(response['profession'].profession_name);
                    $('#numberOfMaleHousehold').html(response['household'].number_of_male || '');
                    $('#numberOfFemaleHousehold').html(response['household'].number_of_female || '');
                    $('#numberOfChildrenHousehold').html(response['household'].number_of_children || '');
                    $('#numberOfAdultsHousehold').html(response['household'].number_of_adults || '');
                    $('#phoneNumberHousehold').html(response['household'].phone_number || '');
                    $('#energyServiceHousehold').html(response['household'].energy_service || '');
                    $('#energyMeterHousehold').html(response['household'].energy_meter || '');
                    $('#waterServiceHousehold').html(response['household'].water_service || '');
                    $('#energyStatusHousehold').html((response['status'] && response['status'].status) || '');

                    $('#numberOfCistern').html('');
                    if(response['cistern']) {
                        $('#numberOfCistern').html(response['cistern'].number_of_cisterns || '');
                        $('#volumeCistern').html(response['cistern'].volume_of_cisterns || '');
                        $('#depthCistern').html(response['cistern'].depth_of_cisterns || '');
                        $('#sharedCistern').html(response['cistern'].shared_cisterns || '');
                        $('#distanceCistern').html(response['cistern'].distance_from_house || '');
                    }

                    $('#herdSize').html(response['household'].size_of_herd || '');
                    $('#numberOfStructures').html('');
                    if(response['structure']) {
                        $('#numberOfStructures').html(response['structure'].number_of_structures || '');
                        $('#numberOfkitchens').html(response['structure'].number_of_kitchens || '');
                        $('#numberOfShelters').html(response['structure'].number_of_animal_shelters || '');
                    }

                    if(response['communityHousehold']) {
                        $('#izbih').html(response['communityHousehold'].is_there_izbih || '');
                        $('#houseInTown').html(response['communityHousehold'].is_there_house_in_town || '');
                        $('#howLong').html(response['communityHousehold'].how_long || '');
                        $('#lengthOfStay').html(response['communityHousehold'].length_of_stay || '');
                    }

                    $('#energySourceHousehold').html(response['household'].electricity_source || '');
                    $('#energySourceSharedHousehold').html(response['household'].electricity_source_shared || '');
                    $('#notesHousehold').html(response['household'].notes || '');
                }
            });
        });

        // Delete handlers
        $(document).on('click', '.deleteHousehold, .deleteAcHousehold', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: deleteHouseholdUrl,
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    redrawAllTables();
                                });
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        // MISC actions: move, back, notes for households and publics
        $(document).on('click', '.moveMISCHousehold', function() {
            var id = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to start working?',
                html: `
                    <div style="text-align:left;">
                        <p>Would You Like to Start With:</p>
                        <div style="margin-top:8px;">
                            <label style="display:block; margin-bottom:6px;"><input type="radio" name="meterOption" value="meter" checked> &nbsp;Meter</label>
                            <label style="display:block;"><input type="radio" name="meterOption" value="no_meter"> &nbsp;No meter</label>
                        </div>
                    </div>
                `,
                showDenyButton: true,
                confirmButtonText: 'Confirm',
                preConfirm: function() {
                    var el = document.querySelector('input[name="meterOption"]:checked');
                    return el ? el.value : null;
                }
            }).then((result) => {
                if(result.isConfirmed) {
                    var meterChoice = result.value || '';
                    $.ajax({
                        url: moveMISCHouseholdUrl,
                        type: 'get',
                        data: {id: id, meter_option: meterChoice},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.moveMISCPublic', function() {
            var id = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to start working?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: moveMISCPublicUrl,
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.backMISCHousehold', function() {
            var id = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to back this to requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: backMISCHouseholdUrl,
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.backMISCPublic', function() {
            var id = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to back this to requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: backMISCPublicUrl,
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            }
                        }
                    });
                }
            });
        });

        // Notes (Swal textarea)
        $(document).on('click', '.notesMISCHousehold', function () {
            var id = $(this).data('id');
            var currentRow = $(this).closest('tr');
            var table = $(currentRow).closest('table');
            var existingNote = '';
            try { existingNote = table.DataTable().row(currentRow).data().confirmation_notes || ''; } catch(e) {}

            Swal.fire({
                title: 'Edit Note',
                input: 'textarea',
                inputValue: existingNote,
                inputPlaceholder: 'Type your note here...',
                showCancelButton: true,
                confirmButtonText: 'Save Note'
            }).then((result) => {
                if (result.isConfirmed) {
                    var note = result.value;
                    if (!note || note.trim() === "") {
                        Swal.fire('Note cannot be empty.', '', 'error');
                        return;
                    }

                    $.ajax({
                        url: notesMISCHouseholdUrl,
                        type: 'GET',
                        data: { id: id, note: note },
                        success: function (response) {
                            if (response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            } else {
                                Swal.fire('Failed to save note.', '', 'error');
                            }
                        },
                        error: function () { Swal.fire('Server error. Please try again.', '', 'error'); }
                    });
                }
            });
        });

        $(document).on('click', '.notesMISCPublic', function () {
            var id = $(this).data('id');
            var currentRow = $(this).closest('tr');
            var table = $(currentRow).closest('table');
            var existingNote = '';
            try { existingNote = table.DataTable().row(currentRow).data().confirmation_notes || ''; } catch(e) {}

            Swal.fire({
                title: 'Edit Note',
                input: 'textarea',
                inputValue: existingNote,
                inputPlaceholder: 'Type your note here...',
                showCancelButton: true,
                confirmButtonText: 'Save Note'
            }).then((result) => {
                if (result.isConfirmed) {
                    var note = result.value;
                    if (!note || note.trim() === "") {
                        Swal.fire('Note cannot be empty.', '', 'error');
                        return;
                    }

                    $.ajax({
                        url: notesMISCPublicUrl,
                        type: 'GET',
                        data: { id: id, note: note },
                        success: function (response) {
                            if (response.success == 1) {
                                Swal.fire({icon: 'success', title: response.msg, confirmButtonText: 'Okay!'}).then(() => redrawAllTables());
                            } else {
                                Swal.fire('Failed to save note.', '', 'error');
                            }
                        },
                        error: function () { Swal.fire('Server error. Please try again.', '', 'error'); }
                    });
                }
            });
        });

    });
</script>
