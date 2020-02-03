<div class=""> 
	<span class="bg-info chu-thich"></span><span><?php echo $this->lang->line('user_purchased_lux'); ?></span> <br>	
	<span class="bg-warning chu-thich"></span><span><?php echo $this->lang->line('lux_used_by_the_user'); ?></span>

    <button class="btn btn-primary btn_search_chart" style="float: right;margin-top: -22px;"><?php echo $this->lang->line('search_member'); ?></button>

	<select id="form_type" name="type" class="form-control" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;">
        <option value="day"><?php echo $this->lang->line('daily'); ?></option>
	    <option value="week"><?php echo $this->lang->line('weekly'); ?></option>
	    <option value="month"><?php echo $this->lang->line('monthly'); ?></option>
	</select>

    <input placeholder="<?php echo $this->lang->line('date_end'); ?>" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;" class="form-control" id="date_end_com_2" type="text" name="date_end_com">

    <input placeholder="<?php echo $this->lang->line('date_start'); ?>" style="width: 200px; margin-bottom: 10px;float: right; margin-top: -20px; margin-right: 20px;" class="form-control" id="date_start_com_2" type="text"  name="date_start_com">

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

