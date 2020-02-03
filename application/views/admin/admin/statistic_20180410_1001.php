<div class=""> 
	<span class="bg-info chu-thich"></span><span>ユーザーが購入したLUX</span> <br>	
	<span class="bg-warning chu-thich"></span><span>ユーザーが使用したLUX</span>

    <button class="btn btn-primary btn_search_chart" style="float: right;margin-top: -22px;">検索</button>

	<select id="form_type" name="type" class="form-control" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;">
        <option value="day">日次</option>
	    <option value="week">週次</option>
	    <option value="month">月次</option>
	</select>

    <input placeholder="終了日" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;" class="form-control" id="date_end_com_2" type="text" name="date_end_com">

    <input placeholder="開始日" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;" class="form-control" id="date_start_com_2" type="text"  name="date_start_com">

</div>

<style type="text/css">
    .chart {
        width: 970px; height: 400px; margin: 0 auto
    }
    .chu-thich{
    	clear: both;
    	margin-right: 10px;
    	width: 40px;
    	height: 20px;
    	display: inline-block;
    }
</style>

<!-- Stats Top Graph Bot -->
<div class="panel" id="pchart8">
    <div class="panel-body pn load-chart" ></div>
</div>

