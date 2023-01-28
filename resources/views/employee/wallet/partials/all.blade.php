<div id="allRecommendationsProfitAndLoss" class="modal fade"  
    role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title w-100">
                    جميع الصفقات 
                </h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
				@if (count($futureWallets))
					<table class="table table-striped " >
						<thead>
							<tr>
								<th class="text-center">تاريخ اقتراح التوصية</th>
                                <th class="text-center">تاريخ اغلاق الصفقة</th>
								<th class="text-center">العملة</th>
								<th class="text-center">المبلغ المتداول به</th>
								<th class="text-center">الأرباح</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($futureWallets as $futureWallet)
							
							<tr> 
								<td class="text-center">
                                    {{ \Carbon\Carbon::parse($futureWallet->EmployeeRecommendation->created_at)->format('d/m/Y')}}
                                </td>

								<td class="text-center">
                                    {{ \Carbon\Carbon::parse($futureWallet->created_at)->format('d/m/Y')}}
                                </td>

								<td class="text-center">
									<img src="{{$futureWallet->EmployeeRecommendation->image}}" width=30>
									{{ $futureWallet->EmployeeRecommendation->crypto_name }}
								</td>


								<td class="text-center">
                                    {{ $futureWallet->wallet_balance}}
								</td>

								<td class="text-center">
                                    @if($futureWallet->pnl > 0) 
                                        <span style="color:green">
                                            {{ $futureWallet->pnl}}
                                        </span>
                                    @else
                                        <span style="color:red">
                                            {{ $futureWallet->pnl}}
                                        </span>
                                    @endif
								</td>

							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				@endif
			</div>
            </div>
        </div>
    </div>
</div>