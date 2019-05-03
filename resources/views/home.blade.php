@extends('layouts.master')

@section('title','Home')

@section('content')
<section class="home">
		<div class="container">
				<div class="row">
					<div class="col-md-8 col-sm-12 col-xs-12" style="width: 100%">
						<div class="row">
							<div style="overflow-x:auto">
								<table style="width: 100%;font-size: 17px; font-weight: normal; border-collapse: collapse; font-family: Comic Sans MS;">
									<tr>
										{{-- <th>STT</th> --}}
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">STT</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; width: 25%; font-family: Georgia">Tiêu đề</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; width: 25%; font-family: Georgia">Link bài báo</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Thời gian</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Ngày</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Phương tiện gây tai nạn</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Tử vong</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Bị thương</th>
										<th style="border-bottom: 1px solid gray; padding: 7px; font-family: Georgia">Địa điểm</th>
									</tr>
									@foreach($accidents as $accident)
									<tr>
										{{-- <td>{{$goi->id}}</td> --}}
										<td style="border-bottom: 1px solid gray;padding: 7px">{{$accident->id}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px; width: 25%">{{$accident->title}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px; width: 25%"><a href="{{$accident->url}}" target="_blank">{{$accident->url}}</a></td>
										<td style="border-bottom: 1px solid gray;padding: 7px; text-align: left;">{{$accident->timeHappen}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px;text-align: left">{{$accident->dayHappen}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px">{{$accident->vehicle}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px">{{$accident->died}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px">{{$accident->hurt}}</td>
										<td style="border-bottom: 1px solid gray;padding: 7px">{{$accident->location}}</td>
									</tr>
									@endforeach
									{{$accidents->links()}}
								</table>
							</div>
							
						</div>
					</div>
				</div>
		</div>
</section>


@endsection
