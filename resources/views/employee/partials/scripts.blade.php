   
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js" async defer></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/vendors.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyBToaaKJAoTl6Wa6ckuPP0QwTe34-6A4"  ></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/extensions/jquery.knob.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/charts/raphael-min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/charts/morris.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/extensions/unslider-min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/js/core/app-menu.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/js/core/app.min.js"></script>
<script src="{{ ('/js/infobox.js') }}"></script>

<script type="text/javascript">
    $('.menu-counter-text').text( $('.menu-counter li').length);
</script>  
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/js/scripts/forms/select/form-select2.min.js"></script>


<style>

     .bootstrap-datetimepicker-widget  .table{
    
         width:100% !important;
         padding:0px !important;
         margin:0px !important;
     }
        .bootstrap-datetimepicker-widget  .table *{
        
         width:100% !important;
         padding:0.3em !important;
        
     }

     .danger-swal {
        z-index: X!impotant;
     }
 </style>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src=" {{('/js/jquery.slider.js')}}"></script>

<script>

    function custom_template(obj){
        if (!obj.id) { return obj.text; }
        var data = $(obj.element).data();
        var text = $(obj.element).text();
        if(data && data['img_src']) {
            img_src = data['img_src'];
            width = data['img_width'];
            height = data['img_height'];
            obj = $("<div id='selectCoinId' data-id=\"" + text + "\"><img src=\"" + img_src + "\" style=\"width:"+ width +"; height:"+ height +"; /><span style=\"font-size:8pt;margin-bottom:0;float:left;\">" 
            + text + "</span></div>");

            return obj;
        }
    }

    function  templateSelection() {
        template = $("<span style=\"font-size:13px\" selected>العملة</span>");
        return template;
    }

    var options = {
        'placeholder': 'العملة',
        'templateSelection': custom_template,
        'templateResult': custom_template,
    };

    $('.cryptoCurrencySelectEmployee').select2(options);
    $('.select2-container--default .select2-selection--single').css({'height': '39px'});

    let coinPrice = 0;
    let long = "no";
    let leverage = 1;
    let entry_point = 0;
    let exit_point = 0;

    $(document).on('change', '#cryptoCurrencySelectEmployee', function () {
        let coin_name = $("#selectCoinId").attr('data-id');
        coin_name = coin_name.replace(/\s+/g, '');
        console.log(coin_name);

        
        $("#short_or_long").prop("disabled", false);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/crypto/select/'+ coin_name,
            data: {
                'coin_name': coin_name, 
            },
            success: function (data) {
                coinPrice = data.data[0]["priceQuote"];
                
                Swal.fire({
                    position: 'top-center',
                    icon: 'warning',
                    title: 'لقد قمت باختيار عملة ' + data.data[0]["baseSymbol"],
                    text: 'السعر الحالي لها هو ' + data.data[0]["priceQuote"],
                    showConfirmButton: false,
                    timer: 5500
                })
            }
        });

    });

    $(document).on('change', '#short_or_long', function () {
        long = $(this).val();

        if(long == "yes") {

            $("#movingAverageQuestion").text("هل العملة متواجدة فوق الـ MA50");
            $("#rsiQuestion").text("هل الـ RSI موجود في نموذج ايجابي");
            $("#mACDQuestion").text("هل الـ MACD موجود في نموذج ايجابي");

            $("#movingAverageQuestionInput").val("هل العملة متواجدة فوق الـ MA50");
            $("#rsiQuestionInput").val("هل الـ RSI موجود في نموذج ايجابي");
            $("#mACDQuestionInput").val("هل الـ MACD موجود في نموذج ايجابي");

        } else if(long == "no") {

            $("#movingAverageQuestion").text("هل العملة متواجدة تحت الـ MA50");
            $("#rsiQuestion").text("هل الـ RSI موجود في نموذج سلبي");
            $("#mACDQuestion").text("هل الـ MACD موجود في نموذج سلبي");

            $("#movingAverageQuestionInput").val("هل العملة متواجدة تحت الـ MA50");
            $("#rsiQuestionInput").val("هل الـ RSI موجود في نموذج سلبي");
            $("#mACDQuestionInput").val("هل الـ MACD موجود في نموذج سلبي");
        }

        $("#leverage").prop("disabled", false);
        $(document).on('change', '#leverage', function () {
            leverage = $(this).val();
            console.log(leverage);
            $("#exit_point").prop("disabled", false);
        });
        $(document).on('change', '#exit_point', function () {
            exit_point = $(this).val();
            console.log(exit_point);
            console.log(long);
            console.log(coinPrice);
            $("#entry_point").prop("disabled", false);

            if(long == "no" && exit_point < coinPrice) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'warning',
                    title: 'انتبه!',
                    text: 'يجب أن تكون نقطة الخروج أكبر من السعر الحالي!',
                    showConfirmButton: false,
                    timer: 5500
                });
            } else if(long == "yes" && exit_point > coinPrice ) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'warning',
                    title: 'انتبه!',
                    text: 'يجب أن تكون نقطة الخروج أقل من السعر الحالي!',
                    showConfirmButton: false,
                    timer: 5500
                })
            }
        });


        $(document).on('change', '#entry_point', function () {
            entry_point = $(this).val();
            console.log(entry_point);
            console.log(long);
            console.log(exit_point);

            if(long == "yes") {

                remaining = $("#sliderRangeReaming").val();
                loss = (entry_point - exit_point);
                lossPercentage = ( loss / entry_point) ;
                lossPercentage = ( lossPercentage * leverage ) * 100 ;
                loss = (remaining * lossPercentage) * -1;

                Swal.fire({
                    position: 'top-center',
                    icon: 'warning',
                    title: 'انتبه، بحال عدم نجاح الصفقة',
                    text: 'نسبة الخسارة = ' + lossPercentage.toFixed(2) + '%, الخسارة = ' + loss.toFixed(2),
                    showConfirmButton: false,
                    timer: 9000
                })

            } else if(long == "no" ) {

                remaining = $("#sliderRangeReaming").val();
                loss = (exit_point - entry_point);
                lossPercentage = ( loss / entry_point) ;
                lossPercentage = ( lossPercentage * leverage ) * 100 ;
                loss = (remaining * lossPercentage) * -1;

                Swal.fire({
                    position: 'top-center',
                    icon: 'warning',
                    title: 'انتبه، بحال عدم نجاح الصفقة',
                    text: 'نسبة الخسارة = ' + lossPercentage.toFixed(2) + '%, الخسارة = ' + loss.toFixed(2),
                    showConfirmButton: false,
                    timer: 9000
                })

            } 
        });


        $('#dynamicAddRemoveTargetPoints').on('change', '.target_point', function() {
            
            let target_value = $(this).val();
            let dataId = $(this).data("id");
            console.log(target_value);
            console.log(dataId);
            console.log(long);
            console.log(entry_point);
            
            if(long == "no" && target_value > entry_point) {
                
                $("#targetPointsNote"+dataId+"").text("نقطة الهدف يجب ان تكون اقل من السعر الحالي");
                
            } else if(long == "no" && target_value < entry_point) {
               
                remaining = $("#sliderRangeReaming").val();
                profit = (entry_point - target_value);
                profitPercentage = ( profit / entry_point) ;
                profitPercentage = ( profitPercentage * leverage ) * 100 ;
                profit = (remaining * profitPercentage);

                $("#targetPointsNote"+dataId+"").text(
                    "نسبة الربح = " + profitPercentage.toFixed(2) + "% الربح = " +
                    profit.toFixed(2)
                );
               // $("#dynamicAddRemoveTargetPoints tr").find('span#targetPointsNote')
                
            } else if(long == "yes" && target_value < entry_point ) {

                $("#targetPointsNote"+dataId+"").text("نقطة الهدف يجب ان تكون اعلى من السعر الحالي");

            } else if(long == "yes" && target_value > entry_point ) {
                remaining = $("#sliderRangeReaming").val();
                profit = (target_value - entry_point);
                profitPercentage = ( profit / entry_point) ;
                profitPercentage = ( profitPercentage * leverage ) * 100 ;
                profit = (remaining * profitPercentage);

                $("#targetPointsNote"+dataId+"").text(
                    "نسبة الربح = " + profitPercentage.toFixed(2) + "% الربح = " +
                    profit.toFixed(2)
                );
            }
        });
    });


    var danger_counter = 0;
    $(document).on('change', '#MAQuestion', function () {
        ma_answer = $(this).val();
        console.log(ma_answer);
        if(ma_answer == "no") danger_counter++;
    });

    $(document).on('change', '#RSIQuestion', function () {
        rsi_answer = $(this).val();
        console.log(rsi_answer);
        if(rsi_answer == "no") danger_counter++;
    });

    $(document).on('change', '#MACDQuestion', function () {
        macd_answer = $(this).val();
        console.log(macd_answer);
        if(macd_answer == "no") danger_counter++;
        
        if(danger_counter >= 1) {
            let description = "";
            Swal.fire({
                customClass: {
                    container: 'danger-swal'
                },
                title: 'أنت مقدم على Trade خطير، فسر رغبتك في اتخاذ هذه الصفقة؟',
                html:
                    '<textarea id="descriptionDanger" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        resolve([
                            description =  $('#descriptionDanger').val(),
                        ])
                    })
                },
            })
            .then(name => {
                $("#question_danger_answer").val(description);
            }).catch(err => { 
                Swal.fire("Error");
            });
        }
    });

    var i = 0;
    $("#addEntryPointsButton").click(function () {
        ++i;
        $("#dynamicAddRemoveEntryPoints").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsEntryPoints[][subject]" placeholder="ادخل نقطة دخول"' +
            'class="entry_point form-control" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-entry-points">حذف</button></td>' +
            '<td><span class="entryPointsNote"> </span></td>'+
            '</tr>'
            );
    });
    $(document).on('click', '.remove-input-field-entry-points', function () {
        $(this).parents('tr').remove();
    });
    
    var j = 0;
    $("#addTargetPointsButton").click(function () {
        ++j;
        $("#dynamicAddRemoveTargetPoints").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsTargetPoints[][subject]" placeholder="ادخل نقطة هدف"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">حذف</button></td>' +
            '<td><span class="targetPointsNote" id="targetPointsNote'+ j +'" > </span></td>'+
            '</tr>'
            );
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });

    // change state of activation for the user
    let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        let switchery = new Switchery(html,  { size: 'small' });
    });

    $('.js-switch').change(function () {
        let status = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('user.update.status') }}',
            data: {'status': status, 'user_id': userId},
            success: function (data) {
                
                if(data["message"] == "not found") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'تم تفعيل الحساب و لكن لم يتم ارسال الايميل بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if(data["message"] == "Great") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'تم تفعيل الحساب وارسال الايميل بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                
            }
        });
    });

    // activate/ nonactivate selected users
    $('#master').on('click', function(e) {
        if($(this).is(':checked',true))  
        {
            $(".sub_chk").prop('checked', true);  
        } else {  
            $(".sub_chk").prop('checked',false);  
        }  
    });

    $('.activate_all').on('click', function(e) {
        var allIds = []; 
        var allStatus = []; 
        $(".sub_chk:checked").each(function() {  
            allIds.push($(this).attr('data-id'));
            allStatus.push(
                $(this).prop('checked') === true ? 1 : 0
            );
        });

        if(allIds.length <=0)  
        {  
            Swal.fire({
                position: 'top-center',
                icon: 'warning',
                title: 'يرجى تحديد مستخدمين',
                showConfirmButton: false,
                timer: 1500
            }) 
        }  else {  
            var id_values = allIds.join(","); 
            var status_values = allStatus.join(","); 
            $.ajax({
                type: 'get',
                dataType: "json",
                url: '{{ route('user.status.all') }}',
                data: {'ids': id_values, 'status':status_values},
                success: function (data) {
                    if(data["message"] == "not found") {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'تم تفعيل الحسابات المحددة و لكن لم يتم ارسال الايميلات بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else if(data["message"] == "Great") {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'تم تفعيل الحسابات المحددة وارسال الايميلات بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                },
                
            });
            
        }  
    });                
    
    
    var question = 0;
    $("#addMoreQuestionsButton").click(function () {
        ++question;
        $("#containerMoreQuestions").append('</br><div class="d-flex justify-content-center row">' +
            '<div class="col-md-10 bg-white border rounded">' +
            '<div class="row p-2"><div class="col-md-8 mt-1"><div class="d-flex flex-row">' +
            '<fieldset class="form-group"><label class="col-md-4">سؤال</label>' +
            '<textarea class="form-control" name="Questions[][title]" style="resize:none" rows=3 cols=50>' +
            '</textarea></fieldset></div></div>' +
            '<div class="align-items-center align-content-center col-md-4 border-left mt-1">' +
            '<div class="d-flex flex-column mr-2"><span>صورة</span>' +
            '<input type="file" name="QuestionsImage[][image]"><span>فيديو</span>' +
            '<input type="file" name="QuestionsVideo[][video]"></div></div>' +
            '<div class="col-md-6 mt-1"><fieldset class="form-group">' +
            '<label class="col-md-4">وقت السؤال</label>' +
            '{!! Form::select("QuestionsTime[][time]",trans("main.question_time"),null,["class"=>"form-control select2l"]) !!}' +
            '</fieldset></div>' +
            '<div class="col-md-6 mt-1"><fieldset class="form-group">' +
            '<label class="col-md-4">علامة السؤال</label>' +
            '<input name="QuestionsScore[][score]" class="form-control" type="number">' +
            '</fieldset></div></div><div class="col-xl-12 col-lg-6 col-md-12">' +
            '<table class="table table-bordered dynamicAddMoreAnswers" id="dynamicAddMoreAnswers">' +
            '<tr><th>الاجابات</th><th>خيارات</th></tr><tr><td>' +
            '<div class="d-flex flex-row mr-3"><div class="col-md-2">' +
            '<input class="form-check-input" type="radio" name="QuestionsCorrect[][correct]" ' +
            'value="'+ question +'"></div><div class="col-md-10">' +
            '<fieldset class="form-group"><textarea name="addMoreInputFieldsAnswers[][subject]" id="title"' +
            'style="resize:none" placeholder="الاجابة" class="form-control" rows=10 cols=70></textarea>' +
            '</fieldset></div></div></td><td><button type="button" name="add" id="addMoreAnswers" ' +
            'class="btn btn-outline-primary addMoreAnswers"> اضافة اجابة اخرى</button></td></tr>' +
            '</table></div></div></div>'
            );
    });
    $(document).on('click', '.remove-questions', function () {
        $(this).parents('tr').remove();
    });

    
    $("#costRangeReaming").val($("#sliderRangeReaming").val());
    let sliderCost = 0;
    var selectValueQuestion1 = "";
    var selectValueQuestion2 = "";
    var profitCost = 0;
    // Add new recommendation 
    $(function () {
      $('.tt').slider({ 
        val: 30, 
        min: 1,
        max: 100,
        onChange: function(e, val) {
            sliderCost = val;
            $(this).next('span').text(val + '%');
            console.log(sliderCost);
            total = $("#totalWalletEmployee").val();
            profitCost = (total * sliderCost) / 100;
            $("#sliderRangeReaming").val(profitCost);
            $("#costRangeReaming").val(profitCost);

        },
        onLoad: function(e, val) {
            sliderCost = val;
            $(this).next('span').text(val + '%');
        }
      });

      setTimeout(()=>{
        $('.tt').slider('setVal', 18);
      }, 1000)
    });

   
    $(document).on('change', '#employeeQuestion1', function () {
        selectValueQuestion1 = $(this).val();

        if(selectValueQuestion1 == "صاعدة") {
           
            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "ما هي نسبة الصعود آخر 24 ساعة");

        } else if(selectValueQuestion1 == "هابطة") {

            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "ما هي نسبة الهبوط آخر 24 ساعة");

        } else if(selectValueQuestion1 == "مستقرة")  {

            $("#percentageInputQuestion1").css("visibility", "hidden");
        }
    });


    $('#employeeQuestion2').change(function () {
        selectValueQuestion2 = $(this).val();

        if(selectValueQuestion2 == "صاعدة") {

            $("#percentageInputQuestion2").css("visibility", "visible");
            $("#percentageInputQuestion2").css('display', 'block');
            $("#percentageInputQuestion2").attr("placeholder", "ما هي نسبة الصعود آخر 24 ساعة");
        
        } else if(selectValueQuestion2 == "هابطة") {

            $("#percentageInputQuestion2").css('display', 'block');
            $("#percentageInputQuestion2").css('display', 'block');
            $("#percentageInputQuestion2").attr("placeholder", "ما هي نسبة الهبوط آخر 24 ساعة");
        
        } else if(selectValueQuestion2 == "مستقرة")  {

            $("#percentageInputQuestion2").css("visibility", "hidden");
        }
    });
    
    // add new currency
    $("#addMoreCurrencies").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var url = '{{ url('crypto') }}';
        var data = $("#createRecoForm").serialize();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: data,

            success: function (data) {

                if(data["message"] == "Found") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: data["name"] + 'موجودة بالفعل!',
                        text: 'قم بادخال عملة اخرى' ,
                        showConfirmButton: true,
                        confirmButtonText: 'موافق',
                        timer: 5500
                    }) 
                } 
                else if(data["message"] == "Success") {
                    var o = new Option(data["name"], data["name"]);
                    
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'تم الاضافة بنجاح، يمكنك اختيار العملة الان',
                        showConfirmButton: false,
                        timer: 5500
                    })  
                
                    var origin  = window.location.origin;  
                    window.location = origin + '/employee-crypto/create';
                }
            }
        });
    });

    let elemsSetting = Array.prototype.slice.call(document.querySelectorAll('.check_recommendation_switch'));
    elemsSetting.forEach(function(html) {
        let switchery = new Switchery(html,  { size: 'small' });
    });

    $('.check_recommendation_switch').change(function () {
        let value = $(this).prop('checked') === true ? 1 : 0;
        let recommendation_id = $(this).data('id');
        let user_id = $(this).data('class');
        
        if(value == 1) {
            Swal.fire({
                title: 'هل انت متأكد من تأكيد هذه التوصية؟',
                html:
                    '<textarea id="descriptionApproved" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        resolve([
                            description =  $('#descriptionApproved').val(),
                        ])
                    })
                },
            })
            .then(name => {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '/all-crypto/check/'+ value + '/' + user_id + '/' + recommendation_id + '/' +
                        description,
                    data: {
                        'value ': value, 
                        'user_id': user_id, 
                        'recommendation_id' : recommendation_id,
                        'description' : description
                    },

                    success: function (data) {
                        if(data["message"] == "Great") {
                            Swal.fire({
                                position: 'top-center',
                                icon: 'success',
                                title: 'تم التأكيد بنجاح',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else  if(data["message"] == "Cancel") {
                            
                            let description = "";

                            Swal.fire({
                                title: 'هل انت متأكد من الغاء تأكيد هذه التوصية؟',
                                html:
                                    '<textarea id="descriptionCancel" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
                                preConfirm: function () {
                                    return new Promise(function (resolve) {
                                        resolve([
                                            description =  $('#descriptionCancel').val(),
                                        ])
                                    })
                                },
                            })
                            .then(name => {
                                $.ajax({
                                    type: "GET",
                                    dataType: "json",
                                    url: '/all-crypto/uncheck/'+ user_id + '/' + recommendation_id + '/' + description,
                                    data: {
                                        'user_id': user_id, 
                                        'recommendation_id' : recommendation_id,
                                        'description' : description
                                    },

                                    success: function (data) {
                                        if(data["message"] == "Great") {
                                            Swal.fire({
                                                position: 'top-center',
                                                icon: 'success',
                                                title: 'تم التغيير بنجاح',
                                                showConfirmButton: false,
                                                timer: 1500
                                            })
                                        }
                                    }
                                });
                            }).catch(err => { 
                                Swal.fire("Error");
                            });
                        }
                    }
                });
            }).catch(err => { 
                Swal.fire("Error");
            });
        } else {
            let description = "";

            Swal.fire({
                title: 'هل انت متأكد من الغاء تأكيد هذه التوصية؟',
                html:
                    '<textarea id="descriptionCancel" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        resolve([
                            description =  $('#descriptionCancel').val(),
                        ])
                    })
                },
            })
            .then(name => {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '/all-crypto/uncheck/'+ user_id + '/' + recommendation_id + '/' + description,
                    data: {
                        'user_id': user_id, 
                        'recommendation_id' : recommendation_id,
                        'description' : description
                    },

                    success: function (data) {
                        if(data["message"] == "Great") {
                            Swal.fire({
                                position: 'top-center',
                                icon: 'success',
                                title: 'تم التغيير بنجاح',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                });
            }).catch(err => { 
                Swal.fire("Error");
            });
        }
    });


    // Add cost for trading from wallet
    $(document).on('change', '.employee_wallet_cost', function() { 
        user_id = $(this).attr('data-class');
        employee_recommendation_id = $(this).attr('data-id');
        cost = $(this).val();
        var url = '{{ url('employee-calculation') }}';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType: "json",
            url: url,
            data: {
                'cost': cost, 
                'user_id': user_id, 
                'employee_recommendation_id': employee_recommendation_id
            },
            success: function (data) {
                if(data["message"] == "New") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'تم الاضافة بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if(data["message"] == "Update") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'تم التعديل بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                
            }
        });
    });

    
    // Select profit or loss to make the calculation
    $(document).on('change', '.employee_profit_select', function() { 
        user_id = $(this).attr('data-class');
        employee_recommendation_id = $(this).attr('data-id');
        is_profit = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}",
            },
            type: "get",
            dataType: "json",
            url: '/employee-calculation/'+ is_profit + '/' + user_id + '/' + employee_recommendation_id,
            data: {
                'is_profit ': is_profit, 
                'user_id': user_id,
                'employee_recommendation_id' : employee_recommendation_id
            },
            success: function (data) {
                
                if(data["message"] == "Profit") {
                   
                    let entry = 0;
                    let target = 0;
                    let recommendation_id = data["employee_recommendation_id"];
                    Swal.fire({
                        title: 'يرجى اختيار نقطة الدخول والخروج لهذه التوصية',
                        html:
                            '<input type="text" class="entry_point_calculation form-control" placeholder="ادخل نقطة الدخول"' +
                            'id="entry_point_calculation"><br>' +
                            '<input type="text" class="target_point_calculation form-control" placeholder="ادخل نقطة الخروج"' +
                            'id="target_point_calculation">' ,
                        preConfirm: function () {
                            return new Promise(function (resolve) {
                                resolve([
                                    entry =  $('#entry_point_calculation').val(),
                                    target = $('#target_point_calculation').val()
                                ])
                            })
                        },
                    })
                    .then(name => {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: '/employee-calculation/profit/'+ user_id + '/' + recommendation_id + '/' + entry + '/' +target,
                            data: {
                                'user_id': user_id, 
                                'recommendation_id' : recommendation_id,
                                'entry' : entry,
                                'target' : target,
                            },

                            success: function (data) {

                                $.ajax({
                                    type: "GET",
                                    dataType: "json",
                                    url: '/calculation/profit/'+ user_id + '/' + recommendation_id,
                                    data: {
                                        'user_id': user_id, 
                                        'recommendation_id' : recommendation_id
                                    },
                                    success: function (data) {
                                        if(data["message"] == "Great") {
                                            Swal.fire({
                                                position: 'top-center',
                                                icon: 'success',
                                                title: 'تم اضافة المربح بنجاح',
                                                showConfirmButton: false,
                                                timer: 1500
                                            })
                                        }
                                    }
                                });
                            }
                        });
                    }).catch(err => {
                        Swal.fire("Error");
                    });
                    
                } else  if(data["message"] == "Loss") {
                    let entry = 0;
                    let target = 0;
                    let recommendation_id = data["employee_recommendation_id"];
                    Swal.fire({
                        title: 'يرجى اختيار نقطة الدخول والخروج لهذه التوصية',
                        html:
                            '<input type="text" class="entry_point_calculation form-control" placeholder="ادخل نقطة الدخول"' +
                            'id="entry_point_calculation"><br>',
                        preConfirm: function () {
                            return new Promise(function (resolve) {
                                resolve([
                                    entry =  $('#entry_point_calculation').val()
                                ])
                            })
                        },
                    })
                    .then(name => {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: '/employee-calculation/profit/'+ user_id + '/' + recommendation_id + '/' + entry + '/' +target,
                            data: {
                                'user_id': user_id, 
                                'recommendation_id' : recommendation_id,
                                'entry' : entry,
                                'target' : target,
                            },

                            success: function (data) {
                                if(data["message"] == "Great") {
                                    Swal.fire({
                                        position: 'top-center',
                                        icon: 'success',
                                        title: 'تم توثيق الخسارة بنجاح',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            }
                        });
                    }).catch(err => {
                        Swal.fire("Error");
                    });
                }
            }
        });
    });

    // check if the entered value grater than total wallet / transfer from spot to future
    $(document).on('change', '#spotTransfer', function () {
        value = $(this).val();
        spotWallet = $("#spotTotalWallet").val();
        if(value == spotWallet) {
            Swal.fire({
                position: 'top-center',
                icon: 'info',
                title: 'انتبه!',
                text: 'أنت تقوم بتحويل كامل المبلغ!',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'تأكيد',
                timer: 5500
            })
        } if(parseFloat(value) > parseFloat(spotWallet)) {
            Swal.fire({
                position: 'top-center',
                icon: 'warning',
                title: 'انتبه!',
                text: 'المبلغ الذي قمت بادخاله أكبر من المبلغ المتوفر',
                showConfirmButton: false,
                timer: 5500
            });
            $(this).val("");
        }
    });

    // check if the entered value grater than total wallet / transfer from future to spot
    $(document).on('change', '#futureTransfer', function () {
        value = $(this).val();
        pnl = $("#pnlWallet").val();
        if(value == pnl) {
            Swal.fire({
                position: 'top-center',
                icon: 'info',
                title: 'تأكيد',
                text: 'هل انت متأكد بتحويل كامل المبلغ؟',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'تأكيد',
                timer: 5500
            })
        } if(parseFloat(value) > parseFloat(pnl)) {
            Swal.fire({
                position: 'top-center',
                icon: 'warning',
                title: 'انتبه!',
                text: 'المبلغ الذي قمت بادخاله أكبر من الربح المتوفر',
                showConfirmButton: false,
                timer: 5500
            });
            $(this).val("");
        }
    });


    // edit the entry point
    $(document).on('change', '#editEntryPoint', function () {
        let entry_point = $(this).val();
        let id = $(this).data("class");
       
        Swal.fire({
            title: 'هل انت متأكد من تغيير نقطة الدخول؟',
            html:
                '<textarea id="entry_point_description" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    resolve([
                        description =  $('#entry_point_description').val(),
                    ])
                })
            },
        })
        .then(name => {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'entry/' + entry_point + '/' + id + '/' + description,
                
                success: function (data) {
                    if(data["message"] == "Success") {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'تم التغيير بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            });
        }).catch(err => { 
            Swal.fire("Error");
        });
    });

    // edit the stop loss
    $(document).on('change', '#editExitPoint', function () {
        let exit_point = $(this).val();
        let id = $(this).data("class");
       
        Swal.fire({
            title: 'هل انت متأكد من تغيير نقطة الخروج؟',
            html:
                '<textarea id="exit_point_description" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    resolve([
                        description =  $('#exit_point_description').val(),
                    ])
                })
            },
        })
        .then(name => {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'exit/' + exit_point + '/' + id + '/' + description,
                
                success: function (data) {
                    if(data["message"] == "Success") {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'تم التغيير بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            });
        }).catch(err => { 
            Swal.fire("Error");
        });
    });

    // edit target point
    $(document).on('change', '#editTrgetPoint', function () {
        let target_point = $(this).val();
        let id = $(this).data("class");
        $(document).off('focusin.modal');
        Swal.fire({
            title: 'هل انت متأكد من تغيير نقطة الهدف؟',
            html:
                '<textarea id="target_point_description" class="swal2-input" placeholder="يرجى ذكر السبب" cols=7 style="resize: none;"></textarea>' ,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    resolve([
                        description =  $('#target_point_description').val(),
                    ])
                })
            },
        })
        .then(name => {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'target/' + target_point + '/' + id + '/' + description,
                
                success: function (data) {
                    if(data["message"] == "Success") {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'تم التغيير بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            });
        }).catch(err => { 
            Swal.fire("Error");
        });
    });
</script>