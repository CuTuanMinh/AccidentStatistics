@extends('layouts.master')

@section('title','Home')

@section('content')

<section class="home">
		<div class="container">
				<div class="row">
					<div class="col-md-8 col-sm-12 col-xs-12" style="width: 100%">
						<div class="row">
							<div style="overflow-x:auto">
								<table style="width: 70%;font-size: 17px; font-weight: 700; border-collapse: collapse; font-family: Comic Sans MS;">
									<tr>
										<th style="border: 1px solid gray; padding: 7px; font-family: Georgia; text-align: center; background-color: #008040; color: white;">Tháng</th>
										<th style="border: 1px solid gray; padding: 7px; width: 25%; font-family: Georgia; text-align: center; background-color: #008040; color: white;">Số vụ tai nạn</th>
										<th style="border: 1px solid gray; padding: 7px; width: 25%; font-family: Georgia; text-align: center; background-color: #008040; color: white;">Số người chết</th>
										<th style="border: 1px solid gray; padding: 7px; font-family: Georgia; text-align: center; background-color: #008040; color: white;">Số người bị thương</th>
									</tr>
									@foreach($stats as $stat)
									<tr>
										<td style="border: 1px solid gray;padding: 7px; text-align: center">{{$stat->month}}</td>
										<td style="border: 1px solid gray;padding: 7px; width: 25%; text-align: center">{{$stat->accidentQuantity}}</td>
										<td style="border: 1px solid gray;padding: 7px; width: 25%; text-align: center">{{$stat->diedQuantity}}</td>
										<td style="border: 1px solid gray;padding: 7px; text-align: center;">{{$stat->hurtQuantity}}</td>
									</tr>
									@endforeach
									
								</table>
							</div>
							
						</div>
			
						<br><br><br><br>
						<div class="row">
							<div id="chart1" width="200" height="300" data-order="{{$accidents}}">
								
							</div>
							<br><br><br><br>
							<div id="chart2" width="200" height="300" data-order="{{$accidents}}">
								
							</div>
							<br><br><br><br>
							<div id="chart3" width="200" height="300" data-order="{{$accidents}}">
								
							</div>
						</div>
						
					</div>
					
				</div>
		</div>
</section>

@endsection
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript" charset="utf-8" async defer>
$(document).ready(function(){
    var order1 = $('#chart1').data('order');
    var listOfQuantity = [];
    var listOfMonth = [];
    var listOfDied = [];
    var listOfHurt = [];
    order1.forEach(function(element){
        listOfMonth.push("Tháng "+element.month);
        listOfQuantity.push(element.quantity);
    });
    var order2 = $('#chart2').data('order');
    order2.forEach(function(element){
        listOfMonth.push("Tháng "+element.month);
        listOfDied.push(element.died);
    });
    var order3 = $('#chart3').data('order');
    order3.forEach(function(element){
        listOfMonth.push("Tháng "+element.month);
        listOfHurt.push(element.hurt);
    });
    // console.log(listOfValue);
    var chart1 = Highcharts.chart('chart1', {
    	chart: {
    		type: 'column'
    	},
        title: {
            text: 'Biểu đồ thống kê số vụ tai nạn'
        },
        xAxis: {
            categories: listOfMonth,
        },
        yAxis: {
        	title: {
        		text: 'Số lượng'
        	}
        },
        series: [{
            name: 'Số vụ',
            color: '#000080',
            // colorByPoint: true,
            data: listOfQuantity,
            showInLegend: false
        }]
    });
    var chart2 = Highcharts.chart('chart2', {
    	chart: {
    		type: 'column'
    	},
        title: {
            text: 'Biểu đồ thống kê số người chết'
        },
        xAxis: {
            categories: listOfMonth,
        },
        yAxis: {
        	title: {
        		text: 'Số lượng'
        	}
        },
        series: [{
            name: 'Số người chết',
            color: '#d2691e',
            // colorByPoint: true,
            data: listOfDied,
            showInLegend: false
        }]
    });
    var chart3 = Highcharts.chart('chart3', {
    	chart: {
    		type: 'column'
    	},
        title: {
            text: 'Biểu đồ thống kê số người bị thương'
        },
        xAxis: {
            categories: listOfMonth,
        },
        yAxis: {
        	title: {
        		text: 'Số lượng'
        	}
        },
        series: [{
            name: 'Số người bị thương',
            color: '#3cb371',
            // colorByPoint: true,
            data: listOfHurt,
            showInLegend: false
        }]
    });
});

</script>

