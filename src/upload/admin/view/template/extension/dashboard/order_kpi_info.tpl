<?php
$head = array();
$rows = array();
$totals = array();
foreach ($data as &$year) {
    if (!isset($head[$year['year']])) {
	    $head[$year['year']] = array();
	    $head[$year['year']]['month'] = array();

    }

    $total_key = $year['year'] . ':'  . $year['month'];
    if (!isset($totals[$total_key])) {
	    $totals[$total_key] = 0;

    }
	$totals[$total_key] += $year['count'];

	$head[$year['year']]['year'] = $year['year'];
	$head[$year['year']]['month'][$year['month']] = $year['month'];

	$row_key = $year['order_status_id'];
    if(!isset($rows[$row_key])) {
	    $rows[$row_key] = array();
	    $rows[$row_key]['total'] = 0;
    }
    $rows[$row_key]['order_status_id'] = $year['order_status_id'];
	$rows[$row_key]['total'] += $year['count'];

//	$rows[$row_key]['status_id_2'] = $year['status_id_2'];

	if(!isset($rows[$row_key][$year['year']])) {
		$rows[$row_key][$year['year']] = array();
	}
	$rows[$row_key][$year['year']][$year['month']] = $year['count'];

}
unset($year);

ksort($head);
foreach ($head as &$year) {
	ksort($year['month']);
}
unset($year);


//echo '<pre>';
//print_r($head);
//echo '</pre>';

?>
<style type="text/css">
    .t-abs{
        color: green;
    }
    .t-rel {
        color:#0E6A93;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $heading_title; ?></h3>
    </div>

    <table cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped table-responsive table-condensed">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">Статусы</th>
                <?php foreach ($head as $year) : ?>
                    <th colspan="<?=count($year['month']);?>" class="text-center"><?=$year['year']?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($head as $year) : ?>
                    <?php foreach ($year['month'] as $m) : ?>
                        <th class="text-center"><?=$m;?></th>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?=$order_statuses[$row['order_status_id']];?></td>
                    <?php foreach ($head as $year) : ?>
                        <?php foreach ($year['month'] as $month) : ?>
                            <td class="text-right">
                                <?php if (isset($row[$year['year']][$month])) : ?>
                                    <span class="t-rel"><?php echo round($row[$year['year']][$month] / $totals[$year['year'] . ':' . $month] , 2); ?></span><br />
                                    <span class="t-abs"><?php echo $row[$year['year']][$month];?></span>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <th>Итого:</th>
		        <?php foreach ($head as $year) : ?>
			        <?php foreach ($year['month'] as $month) : ?>
                        <td class="text-right"><?php echo $totals[$year['year'] . ':' . $month];?></td>
			        <?php endforeach; ?>
		        <?php endforeach; ?>
            </tr>
            <tr>
	            <?php $colspan = 0; foreach ($head as $y) $colspan += count($y['month']);?>
                <td colspan="<?=$colspan;?>">
                    Легенда: <span class="t-rel">Доля от всех</span>, <span class="t-abs">Абсолютное значение</span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
