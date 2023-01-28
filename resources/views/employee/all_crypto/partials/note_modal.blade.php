<?php
    $reviews = App\Models\EmployeeReview::where("employee_recommendation_id", $recommendation->id)->get();
?>
<div id="notes-RecommendationsModal{{$recommendation->id}}" class="modal fade"  
    role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <h1>{{$recommendation->crypto_name}}</h1>

                <div class="row">
                    @if (count($reviews))
                    <table class="table" >
						<thead>
							<tr>
								<th class="text-center">مدير المحفظة</th>
								<th class="text-center">تأكيد التوصية</th>
								<th class="text-center">تفاصيل التوصية</th>
                                <th class="text-center">تاريخ التأكيد / الالغاء </th>
								<th class="text-center">ملاحظات </th>
							</tr>
						</thead>
						<tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td class="text-center">
                                        {{$review->Manager->fname}} {{$review->Manager->lname}}
                                    </td>
                                    <td class="text-center">
                                        @if($review->check == 1)
                                            <i class="fa fa-check" style="color:green"></i>
                                        @else
                                            <i class="fa fa-close" style="color:red"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($review->check == 1)
                                            {{$review->approve_description}}
                                        @else
                                            {{$review->description}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($review->check == 1)
                                        تاريخ التأكيد : 
                                            {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y')}}
                                            <br>
                                            وقت التأكيد : 
                                            {{ \Carbon\Carbon::parse($review->created_at)->format('h:i a')}}
                                            @else
                                        تاريخ الالغاء : 
                                            {{ \Carbon\Carbon::parse($review->updated_at)->format('d/m/Y')}}
                                            <br>
                                            وقت الالغاء : 
                                            {{ \Carbon\Carbon::parse($review->created_at)->format('h:i a')}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($review->check == 1)
                                        
                                        @else
                                        تم تأكيد الطلبية مسبقا لسبب: 
                                        {{$review->approve_description}}
                                        بتاريخ :
                                        {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y')}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <h5 style="color:red">لا يوجد ملاحظات</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>